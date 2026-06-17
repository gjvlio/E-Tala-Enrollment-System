<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // List all students — searchable by name or student number
    public function showStudents(Request $request)
    {
        // TODO: $students = \App\Models\Student::with('user')
        //           ->when($request->search, fn($q) => $q->where('last_name', 'like', "%{$request->search}%")
        //               ->orWhere('student_number', 'like', "%{$request->search}%"))
        //           ->paginate(20);
        // return view('registrar.students.index', compact('students'));
    }

    // Show a single student's full profile and enrollment history
    public function showStudent(Request $request, $student)
    {
        // TODO: $student = \App\Models\Student::with(['enrollments.section', 'enrollments.subjects', 'semesterRecords'])->findOrFail($student);
        // return view('registrar.students.show', compact('student'));
    }
}
