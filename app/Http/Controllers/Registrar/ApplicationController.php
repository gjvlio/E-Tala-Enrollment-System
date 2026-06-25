<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Section;
use App\Models\Student;
use App\Notifications\ApplicationDesignatedNotification;
use App\Notifications\ApplicationQualifiedNotification;
use App\Notifications\ApplicationReturnedNotification;
use App\Notifications\ApplicationWaitlistedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    // submitted applications, filterable by status
    public function showApplications(Request $request): View
    {
        $status = $request->query('status');

        $applications = Application::with(['user', 'strand'])
            ->where('status', '!=', 'draft')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('submitted_at')
            ->paginate(20)
            ->withQueryString();

        $counts = Application::where('status', '!=', 'draft')
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('registrar.applications.index', [
            'applications' => $applications,
            'counts'       => $counts,
            'activeStatus' => $status,
        ]);
    }

    // review screen — details, documents, and slot availability
    public function showApplication(Application $application): View
    {
        $application->load(['user', 'strand', 'documents', 'reviewer']);

        $seats    = $this->seatsFor($application);
        $admitted = $this->admittedFor($application);

        // For waitlisted applicants the registrar designates a specific vacant
        // section matching the applicant's strand + grade.
        $vacantSections = collect();
        if ($application->isWaitlisted()) {
            $vacantSections = Section::with('strand')
                ->withCount(['enrollments as approved_count' => fn ($q) => $q->where('status', 'approved')])
                ->where('strand_id', $application->strand_id)
                ->where('grade_level', $application->grade_level)
                ->when(\App\Models\SchoolYear::active(), fn ($q, $sy) => $q->where('school_year_id', $sy->id))
                ->orderBy('section_name')
                ->get()
                ->reject(fn ($section) => $section->isFull())
                ->values();
        }

        return view('registrar.applications.show', [
            'application'    => $application,
            'seats'          => $seats,
            'admitted'       => $admitted,
            'hasSlot'        => $seats > 0 && $admitted < $seats,
            'vacantSections' => $vacantSections,
        ]);
    }

    // return the application as invalid with a reason — applicant reuploads + resubmits
    public function returnApplication(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ]);

        if (! $application->isPending()) {
            return back()->withErrors(['remarks' => 'Only pending applications can be returned.']);
        }

        $application->update([
            'status'      => 'invalid',
            'remarks'     => $validated['remarks'],
            'reviewed_by' => $request->user()->registrar?->id,
            'reviewed_at' => now(),
        ]);

        $application->user->notify(new ApplicationReturnedNotification($validated['remarks']));

        return redirect()
            ->route('registrar.showApplications')
            ->with('status', 'application-returned');
    }

    // qualify: issue School ID + default password and create the student — or waitlist if full
    public function qualifyApplication(Request $request, Application $application): RedirectResponse
    {
        if (! $application->isPending()) {
            return back()->withErrors(['qualify' => 'Only pending applications can be qualified.']);
        }

        $registrarId = $request->user()->registrar?->id;

        // no slot left for this strand + grade → waitlist instead
        if (! ($this->seatsFor($application) > 0 && $this->admittedFor($application) < $this->seatsFor($application))) {
            $application->update([
                'status'      => 'waitlisted',
                'reviewed_by' => $registrarId,
                'reviewed_at' => now(),
            ]);

            $application->user->notify(
                new ApplicationWaitlistedNotification(optional($application->strand)->strand_code ?? 'your strand')
            );

            return redirect()
                ->route('registrar.showApplications')
                ->with('status', 'application-waitlisted');
        }

        $schoolId = Student::generateNumber();
        $plainPassword = strtoupper(Str::random(8));

        DB::transaction(function () use ($application, $schoolId, $plainPassword, $registrarId) {
            Student::create([
                'user_id'        => $application->user_id,
                'student_number' => $schoolId,
                'first_name'     => $application->first_name,
                'last_name'      => $application->last_name,
                'phone'          => $application->mobile,
                'birthdate'      => $application->birthdate,
                'address'        => trim(implode(', ', array_filter([
                    $application->current_address,
                    $application->current_barangay,
                    $application->current_city,
                    $application->current_province,
                ]))),
                'strand_id'      => $application->strand_id,
                'grade_level'    => $application->grade_level,
            ]);

            $user = $application->user;
            $user->school_id = $schoolId;
            $user->password = $plainPassword;          // hashed by the cast
            $user->must_change_password = true;
            $user->save();

            $application->update([
                'status'      => 'qualified',
                'reviewed_by' => $registrarId,
                'reviewed_at' => now(),
            ]);
        });

        $application->user->notify(new ApplicationQualifiedNotification($schoolId, $plainPassword));

        return redirect()
            ->route('registrar.showApplications')
            ->with('status', 'application-qualified');
    }

    // designate a waitlisted applicant to a chosen vacant section: issue School ID
    // + default password and create the student (the student then self-enrolls).
    public function designateApplication(Request $request, Application $application): RedirectResponse
    {
        if (! $application->isWaitlisted()) {
            return back()->withErrors(['designate' => 'Only waitlisted applicants can be designated to a section.']);
        }

        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
        ]);

        $section = Section::withCount(['enrollments as approved_count' => fn ($q) => $q->where('status', 'approved')])
            ->findOrFail($validated['section_id']);

        // Section must match the applicant's strand + grade and still have a slot.
        if (
            $section->strand_id !== $application->strand_id ||
            $section->grade_level !== $application->grade_level
        ) {
            return back()->withErrors(['designate' => 'That section does not match the applicant\'s strand and grade level.']);
        }

        if ($section->isFull()) {
            return back()->withErrors(['designate' => 'That section just filled up. Pick another vacant section.']);
        }

        $registrarId   = $request->user()->registrar?->id;
        $schoolId      = Student::generateNumber();
        $plainPassword = strtoupper(Str::random(8));

        DB::transaction(function () use ($application, $schoolId, $plainPassword, $registrarId, $section) {
            Student::create([
                'user_id'        => $application->user_id,
                'student_number' => $schoolId,
                'first_name'     => $application->first_name,
                'last_name'      => $application->last_name,
                'phone'          => $application->mobile,
                'birthdate'      => $application->birthdate,
                'address'        => trim(implode(', ', array_filter([
                    $application->current_address,
                    $application->current_barangay,
                    $application->current_city,
                    $application->current_province,
                ]))),
                'strand_id'      => $section->strand_id,
                'grade_level'    => $section->grade_level,
            ]);

            $user = $application->user;
            $user->school_id = $schoolId;
            $user->password = $plainPassword;          // hashed by the cast
            $user->must_change_password = true;
            $user->save();

            $application->update([
                'status'      => 'qualified',
                'reviewed_by' => $registrarId,
                'reviewed_at' => now(),
            ]);
        });

        $application->user->notify(
            new ApplicationDesignatedNotification($schoolId, $plainPassword, $section->displayName())
        );

        return redirect()
            ->route('registrar.showApplications')
            ->with('status', 'application-designated');
    }

    // total seats across the strand's sections for that grade, in the active year
    private function seatsFor(Application $application): int
    {
        return (int) Section::where('strand_id', $application->strand_id)
            ->where('grade_level', $application->grade_level)
            ->when(\App\Models\SchoolYear::active(), fn ($q, $sy) => $q->where('school_year_id', $sy->id))
            ->sum('max_capacity');
    }

    // students already admitted into that strand + grade
    private function admittedFor(Application $application): int
    {
        return Student::where('strand_id', $application->strand_id)
            ->where('grade_level', $application->grade_level)
            ->count();
    }
}
