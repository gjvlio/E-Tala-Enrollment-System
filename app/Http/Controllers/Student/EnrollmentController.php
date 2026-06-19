<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // Show enrollment form — list available sections and subjects for active semester
    public function showEnrollForm(Request $request)
    {
        // TODO: $semester = \App\Models\Semester::where('is_active', true)->firstOrFail();
        // TODO: $sections = \App\Models\Section::where('semester_id', $semester->id)->get();
        // TODO: $subjects = \App\Models\Subject::all();
        // return view('student.enroll', compact('semester', 'sections', 'subjects'));
        return view('student.enroll'); // TEMP: Matan added this to preview UI, remove once real data is wired up
    }

    // Submit enrollment form — create enrollment + attach selected subjects
    public function postEnrollForm(Request $request)
    {
        // TODO: validate $request (section_id required, subjects array required)
        // TODO: check student has no existing enrollment for this semester
        // TODO: create Enrollment record with status = 'pending'
        // TODO: attach subjects via enrollment_subjects pivot
        // redirect to status page on success
    }

    // Show current enrollment status for the active semester
    public function showEnrollStatus(Request $request)
    {
        // TODO: $student = auth()->user()->student;
        // TODO: $enrollment = $student->enrollments()->with(['section', 'semester', 'subjects'])->latest()->first();
        // return view('student.status', compact('enrollment'));
    }
}
