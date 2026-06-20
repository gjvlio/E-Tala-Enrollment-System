<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Student home — active semester info, enrollment status summary.
    public function showDashboard(Request $request)
    {
        $student   = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        $enrollment = $student
            ? $student->enrollments()
                ->with(['section.strand', 'section.schoolYear'])
                ->when($schoolYear, fn ($q) => $q->whereHas('section', fn ($s) => $s->where('school_year_id', $schoolYear->id)))
                ->latest('submitted_at')
                ->first()
            : null;

        return view('student.dashboard', compact('student', 'schoolYear', 'enrollment'));
    }
}
