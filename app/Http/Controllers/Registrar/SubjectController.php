<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // List all subjects
    public function showSubjects(Request $request)
    {
        // TODO: $subjects = \App\Models\Subject::paginate(20);
        // return view('registrar.subjects.index', compact('subjects'));
        return view('registrar.subjects.index'); // TEMP: remove once real data is wired up
    }

    // Show form to create a new subject
    public function showCreateSubject()
    {
        // return view('registrar.subjects.form');
        return view('registrar.subjects.form'); // TEMP: remove once real data is wired up
    }

    // Save new subject to database
    public function postCreateSubject(Request $request)
    {
        // TODO: validate $request (subject_code unique, subject_name, units required)
        // TODO: \App\Models\Subject::create($request->validated());
        // redirect to subjects index with success message
    }

    // Show a single subject detail
    public function showSubject(Request $request, $subject)
    {
        // TODO: $subject = \App\Models\Subject::findOrFail($subject);
        // return view('registrar.subjects.show', compact('subject'));
        return redirect()->route('registrar.subjects.showEditSubject', $subject); // no separate show view
    }

    // Show form to edit existing subject
    public function showEditSubject(Request $request, $subject)
    {
        // TODO: $subject = \App\Models\Subject::findOrFail($subject);
        // return view('registrar.subjects.form', compact('subject'));
        return view('registrar.subjects.form'); // TEMP: remove once real data is wired up
    }

    // Update existing subject
    public function updateSubject(Request $request, $subject)
    {
        // TODO: $subject = \App\Models\Subject::findOrFail($subject);
        // TODO: validate + $subject->update($request->validated());
        // redirect to subjects index with success message
    }

    // Delete a subject (only if not referenced by any enrollment_subjects)
    public function deleteSubject(Request $request, $subject)
    {
        // TODO: $subject = \App\Models\Subject::findOrFail($subject);
        // TODO: check no enrollment_subjects reference this subject before deleting
        // TODO: $subject->delete();
        // redirect to subjects index with success message
    }
}
