<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\SemesterRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    // List school years with active / enrollment-open status.
    public function index(Request $request)
    {
        $schoolYears = SchoolYear::orderByDesc('year_label')->get();

        return view('registrar.semester.index', compact('schoolYears'));
    }

    // Create a new school year. Marking it active deactivates the others.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_label'      => ['required', 'string', 'max:20', 'unique:school_years,year_label'],
            'active_semester' => ['required', 'in:1st,2nd'],
        ]);

        // Created inactive — activate via the "Set Active" button in the list.
        $sy = SchoolYear::create([
            'year_label'         => $validated['year_label'],
            'is_active'          => false,
            'active_semester'    => $validated['active_semester'],
            'is_enrollment_open' => false,
        ]);

        AuditLog::record('created_school_year', 'SchoolYear', $sy->id, 'Created school year '.$sy->year_label);

        return back()->with('success', 'School year created.');
    }

    // Mark a school year + semester active (single active at a time).
    public function activate(Request $request, $schoolYear)
    {
        $schoolYear = SchoolYear::findOrFail($schoolYear);

        $validated = $request->validate([
            'active_semester' => ['required', 'in:1st,2nd'],
        ]);

        DB::transaction(function () use ($schoolYear, $validated) {
            SchoolYear::query()->update(['is_active' => false]);
            $schoolYear->update([
                'is_active'       => true,
                'active_semester' => $validated['active_semester'],
            ]);
            AuditLog::record('activated_school_year', 'SchoolYear', $schoolYear->id,
                'Activated '.$schoolYear->year_label.' '.$validated['active_semester'].' sem');
        });

        return back()->with('success', $schoolYear->year_label.' ('.$validated['active_semester'].' sem) is now active.');
    }

    // Switch the active semester (1st/2nd) of an already-active school year.
    public function setSemester(Request $request, $schoolYear)
    {
        $schoolYear = SchoolYear::findOrFail($schoolYear);

        $validated = $request->validate([
            'active_semester' => ['required', 'in:1st,2nd'],
        ]);

        $schoolYear->update(['active_semester' => $validated['active_semester']]);
        AuditLog::record('set_active_semester', 'SchoolYear', $schoolYear->id,
            'Set active semester to '.$validated['active_semester'].' for '.$schoolYear->year_label);

        return back()->with('success', 'Active semester set to '.$validated['active_semester'].' for '.$schoolYear->year_label.'.');
    }

    // Open / close the enrollment period for a school year.
    public function toggleEnrollment(Request $request, $schoolYear)
    {
        $schoolYear = SchoolYear::findOrFail($schoolYear);
        $schoolYear->update(['is_enrollment_open' => ! $schoolYear->is_enrollment_open]);

        $state = $schoolYear->is_enrollment_open ? 'opened' : 'closed';
        AuditLog::record('toggled_enrollment', 'SchoolYear', $schoolYear->id, "Enrollment {$state} for ".$schoolYear->year_label);

        return back()->with('success', "Enrollment {$state} for {$schoolYear->year_label}.");
    }

    // Finalize a semester — compute GPA per student from encoded grades and lock records.
    public function finalize(Request $request, $schoolYear)
    {
        $schoolYear = SchoolYear::findOrFail($schoolYear);

        $validated = $request->validate([
            'semester' => ['required', 'in:1st,2nd'],
        ]);

        $count = DB::transaction(function () use ($schoolYear, $validated) {
            // All approved enrollments for this year + semester
            $enrollments = Enrollment::with(['student', 'enrollmentSubjects'])
                ->where('status', 'approved')
                ->whereHas('section', fn ($s) => $s->where('school_year_id', $schoolYear->id)->where('semester', $validated['semester']))
                ->get();

            $locked = 0;
            foreach ($enrollments as $enrollment) {
                $grades = $enrollment->enrollmentSubjects->whereNotNull('grade')->pluck('grade');
                $gpa = $grades->isNotEmpty() ? round($grades->avg(), 2) : null;

                SemesterRecord::updateOrCreate(
                    [
                        'student_id'     => $enrollment->student_id,
                        'school_year_id' => $schoolYear->id,
                        'semester'       => $validated['semester'],
                    ],
                    [
                        'gpa'       => $gpa,
                        'is_locked' => true,
                    ]
                );

                // Archive the enrollment so it no longer counts as active — every
                // student must submit a fresh enrollment for the next wave.
                $enrollment->update(['status' => 'completed']);

                $locked++;
            }

            // Close enrollment; the registrar reopens it to start the next wave.
            $schoolYear->update(['is_enrollment_open' => false]);

            AuditLog::record('finalized_semester', 'SchoolYear', $schoolYear->id,
                "Finalized {$validated['semester']} sem of {$schoolYear->year_label} ({$locked} records)");

            return $locked;
        });

        return back()->with('success', "Semester finalized. {$count} record(s) locked.");
    }
}
