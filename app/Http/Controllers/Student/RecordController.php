<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    // Show all semester records for the authenticated student (GPA history)
    public function showRecords(Request $request)
    {
        // TODO: $student = auth()->user()->student;
        // TODO: $records = $student->semesterRecords()->orderBy('academic_year')->orderBy('semester')->get();
        // return view('student.records', compact('records'));
        return view('student.records'); // TEMP: Matan added this to preview UI, remove once real data is wired up
    }
}
