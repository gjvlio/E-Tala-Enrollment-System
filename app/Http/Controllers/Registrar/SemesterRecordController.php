<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemesterRecordController extends Controller
{
    // Show all semester records for a student (GPA per semester)
    public function showSemesterRecord(Request $request, $student)
    {
        // TODO: $student = \App\Models\Student::with('semesterRecords')->findOrFail($student);
        // return view('registrar.records.show', compact('student'));
    }

    // Update a student's semester record — set GPA, status, remarks
    public function updateSemesterRecord(Request $request, $student)
    {
        // TODO: validate $request (academic_year, semester, gpa, status, remarks)
        // TODO: $student = \App\Models\Student::findOrFail($student);
        // TODO: $student->semesterRecords()->updateOrCreate(
        //           ['academic_year' => $request->academic_year, 'semester' => $request->semester],
        //           ['gpa' => $request->gpa, 'status' => $request->status, 'remarks' => $request->remarks]
        //       );
        // redirect back with success message
    }
}
