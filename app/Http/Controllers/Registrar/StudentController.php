<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Strand;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // List students — searchable, grouped by grade level → strand.
    public function showStudents(Request $request)
    {
        $students = Student::query()
            ->with(['user', 'strand'])
            ->when($request->search, function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('student_number', 'like', "%{$term}%");
                });
            })
            ->when($request->strand, fn ($q) => $q->where('strand_id', $request->strand))
            ->when($request->grade, fn ($q) => $q->where('grade_level', $request->grade))
            ->orderBy('grade_level')
            ->orderBy('last_name')
            ->get();

        // Group for the folder-style view: grade level → strand code
        $grouped = $students->groupBy([
            fn ($s) => 'Grade '.$s->grade_level,
            fn ($s) => $s->strand?->strand_code ?? 'Unassigned',
        ]);

        $strands = Strand::orderBy('strand_code')->get();

        return view('registrar.students.index', compact('students', 'grouped', 'strands'));
    }

    // Single student profile + enrollment history + semester records.
    public function showStudent(Request $request, $student)
    {
        $student = Student::with([
            'user', 'strand',
            'enrollments.section.strand', 'enrollments.section.schoolYear',
            'semesterRecords.schoolYear',
        ])->findOrFail($student);

        return view('registrar.students.show', compact('student'));
    }
}
