<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Show subjects enrolled in the active semester with grades if available
    public function showSubjects(Request $request)
    {
        // TODO: $student = auth()->user()->student;
        // TODO: $semester = \App\Models\Semester::where('is_active', true)->firstOrFail();
        // TODO: $enrollment = $student->enrollments()->where('semester_id', $semester->id)->firstOrFail();
        // TODO: $subjects = $enrollment->subjects; // includes pivot grade + status
        // return view('student.subjects', compact('subjects', 'enrollment'));
        return view('student.subjects'); // TEMP: remove once real data is wired up
    }
}
