<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\EnrollmentDocument;
use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function showEnrollForm(Request $request)
    {
        $student = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        if (! $schoolYear || ! $schoolYear->is_enrollment_open) {
            return view('student.enroll', [
                'student' => $student,
                'schoolYear' => $schoolYear,
                'sections' => collect(),
                'blocked' => 'closed',
            ]);
        }

        $existing = $this->activeEnrollment($student, $schoolYear);
        if ($existing) {
            return view('student.enroll', [
                'student' => $student,
                'schoolYear' => $schoolYear,
                'sections' => collect(),
                'blocked' => 'enrolled',
                'existing' => $existing,
            ]);
        }

        $invalid = $student->enrollments()
            ->where('status', 'invalid')
            ->whereHas('section', fn ($s) => $s
                ->where('school_year_id', $schoolYear->id)
                ->where('semester', $schoolYear->active_semester))
            ->latest('submitted_at')
            ->first();

        $sections = Section::with('subjects')
            ->withCount(['enrollments as approved_count' => fn ($q) => $q->where('status', 'approved')])
            ->where('school_year_id', $schoolYear->id)
            ->where('semester', $schoolYear->active_semester)
            ->where('strand_id', $student->strand_id)
            ->where('grade_level', $student->grade_level)
            ->get()

            ->reject(fn ($section) => $section->isFull())
            ->values();

        return view('student.enroll', [
            'student' => $student,
            'schoolYear' => $schoolYear,
            'sections' => $sections,
            'blocked' => null,
            'invalidRemarks' => $invalid?->remarks,
        ]);
    }

    public function postEnrollForm(Request $request)
    {
        $student = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        if (! $schoolYear || ! $schoolYear->is_enrollment_open) {
            return redirect()->route('student.showEnrollForm')
                ->with('error', 'Enrollment is currently closed.');
        }

        if ($this->activeEnrollment($student, $schoolYear)) {
            return redirect()->route('student.showEnrollStatus')
                ->with('error', 'You already have an active enrollment this semester.');
        }

        $rules = ['section_id' => ['required', 'exists:sections,id']];

        if ($student->grade_level === '12') {
            $rules['documents'] = ['required', 'array'];
            $rules['documents.sf9'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            $rules['documents.photo'] = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        }

        $validated = $request->validate($rules);

        $section = Section::with('subjects')->findOrFail($validated['section_id']);

        if (
            $section->school_year_id !== $schoolYear->id ||
            $section->semester !== $schoolYear->active_semester ||
            $section->strand_id !== $student->strand_id ||
            $section->grade_level !== $student->grade_level
        ) {
            return redirect()->route('student.showEnrollForm')
                ->with('error', 'That section is not available for your strand and grade level.');
        }

        if ($section->isFull()) {
            return redirect()->route('student.showEnrollForm')
                ->with('error', 'Sorry, "'.$section->section_name.'" just filled up. Please pick another section.');
        }

        DB::transaction(function () use ($student, $section, $request) {
            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'section_id' => $section->id,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            if ($student->grade_level === '12') {
                foreach (array_keys(EnrollmentDocument::TYPES) as $type) {
                    if ($file = $request->file("documents.$type")) {
                        EnrollmentDocument::create([
                            'enrollment_id' => $enrollment->id,
                            'type' => $type,
                            'path' => $file->store("enrollments/{$enrollment->id}", 'public'),
                            'original_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }

            $rows = $section->subjects->map(fn ($subject) => [
                'enrollment_id' => $enrollment->id,
                'subject_id' => $subject->id,
                'status' => 'enrolled',
                'created_at' => now(),
                'updated_at' => now(),
            ])->all();

            if ($rows) {
                DB::table('enrollment_subjects')->insert($rows);
            }
        });

        return redirect()->route('student.showEnrollStatus')
            ->with('success', 'Enrollment submitted! It is now pending registrar approval.');
    }

    public function showEnrollStatus(Request $request)
    {
        $student = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        $enrollment = $student->enrollments()
            ->with(['section.strand', 'section.schoolYear', 'subjects', 'approver'])
            ->when($schoolYear, fn ($q) => $q->whereHas('section', fn ($s) => $s->where('school_year_id', $schoolYear->id)))
            ->latest('submitted_at')
            ->first();

        return view('student.status', compact('student', 'schoolYear', 'enrollment'));
    }

    public function showCertificate(Request $request)
    {
        $student = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        $enrollment = $student->enrollments()
            ->with(['section.strand', 'section.schoolYear', 'section.subjects', 'subjects', 'approver'])
            ->where('status', 'approved')
            ->when($schoolYear, fn ($q) => $q->whereHas('section', fn ($s) => $s->where('school_year_id', $schoolYear->id)))
            ->latest('submitted_at')
            ->first();

        if (! $enrollment) {
            return redirect()->route('student.showEnrollStatus')
                ->with('error', 'A Certificate of Registration is only available once your enrollment is approved.');
        }

        return view('student.cor', compact('student', 'schoolYear', 'enrollment'));
    }

    private function activeEnrollment($student, ?SchoolYear $schoolYear): ?Enrollment
    {
        if (! $student || ! $schoolYear) {
            return null;
        }

        return $student->enrollments()
            ->whereIn('status', ['pending', 'approved'])
            ->whereHas('section', fn ($s) => $s
                ->where('school_year_id', $schoolYear->id)
                ->where('semester', $schoolYear->active_semester))
            ->latest('submitted_at')
            ->first();
    }
}
