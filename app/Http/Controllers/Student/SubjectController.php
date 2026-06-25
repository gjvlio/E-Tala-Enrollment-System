<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function showSubjects(Request $request)
    {
        $student = Auth::user()->student;
        $schoolYear = SchoolYear::active();

        $enrollment = $student->enrollments()
            ->with(['subjects', 'section'])
            ->when($schoolYear, fn ($q) => $q->whereHas('section', fn ($s) => $s->where('school_year_id', $schoolYear->id)))
            ->latest('submitted_at')
            ->first();

        $subjects = $enrollment ? $enrollment->subjects : collect();

        return view('student.subjects', compact('student', 'enrollment', 'subjects'));
    }
}
