<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // List all sections — grouped or filtered by semester
    public function showSections(Request $request)
    {
        // TODO: $sections = \App\Models\Section::with('semester')->paginate(20);
        // return view('registrar.sections.index', compact('sections'));
        return view('registrar.sections.index'); // TEMP: remove once real data is wired up
    }

    // Show form to create a new section
    public function showCreateSection()
    {
        // TODO: $semesters = \App\Models\Semester::all();
        // return view('registrar.sections.form', compact('semesters'));
        return view('registrar.sections.form'); // TEMP: remove once real data is wired up
    }

    // Save new section to database
    public function postCreateSection(Request $request)
    {
        // TODO: validate $request (semester_id, section_name, year_level required)
        // TODO: \App\Models\Section::create($request->validated());
        // redirect to sections index with success message
    }

    // Show a single section detail
    public function showSection(Request $request, $section)
    {
        // TODO: $section = \App\Models\Section::with('semester')->findOrFail($section);
        // return view('registrar.sections.show', compact('section'));
        return redirect()->route('registrar.sections.showEditSection', $section); // no separate show view
    }

    // Show form to edit existing section
    public function showEditSection(Request $request, $section)
    {
        // TODO: $section = \App\Models\Section::findOrFail($section);
        // TODO: $semesters = \App\Models\Semester::all();
        // return view('registrar.sections.form', compact('section', 'semesters'));
        return view('registrar.sections.form'); // TEMP: remove once real data is wired up
    }

    // Update existing section
    public function updateSection(Request $request, $section)
    {
        // TODO: $section = \App\Models\Section::findOrFail($section);
        // TODO: validate + $section->update($request->validated());
        // redirect to sections index with success message
    }

    // Delete a section (only if no enrollments reference it)
    public function deleteSection(Request $request, $section)
    {
        // TODO: $section = \App\Models\Section::findOrFail($section);
        // TODO: check no enrollments reference this section before deleting
        // TODO: $section->delete();
        // redirect to sections index with success message
    }
}
