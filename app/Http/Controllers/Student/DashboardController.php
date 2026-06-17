<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Show student home — active semester info, enrollment status summary
    public function showDashboard(Request $request)
    {
        // TODO: $student = auth()->user()->student;
        // TODO: $semester = \App\Models\Semester::where('is_active', true)->first();
        // TODO: $enrollment = $student->enrollments()->where('semester_id', $semester->id)->first();
        // return view('student.dashboard', compact('student', 'semester', 'enrollment'));
    }
}
