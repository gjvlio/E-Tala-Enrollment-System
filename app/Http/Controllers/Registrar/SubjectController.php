<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    // List all subjects, grouped by code prefix (CORE / STEM / ABM / ...).
    public function showSubjects(Request $request)
    {
        $subjects = Subject::orderBy('subject_code')->get();

        $grouped = $subjects->groupBy(function ($s) {
            return str_contains($s->subject_code, '-')
                ? strtok($s->subject_code, '-')
                : 'OTHER';
        });

        return view('registrar.subjects.index', compact('subjects', 'grouped'));
    }

    public function showCreateSubject()
    {
        return view('registrar.subjects.form', ['subject' => null]);
    }

    public function postCreateSubject(Request $request)
    {
        $validated = $this->validateSubject($request);

        $subject = Subject::create($validated);
        AuditLog::record('created_subject', 'Subject', $subject->id, 'Created subject '.$subject->subject_code);

        return redirect()->route('registrar.subjects.showSubjects')
            ->with('success', 'Subject created.');
    }

    public function showSubject(Request $request, $subject)
    {
        return redirect()->route('registrar.subjects.showEditSubject', $subject);
    }

    public function showEditSubject(Request $request, $subject)
    {
        $subject = Subject::findOrFail($subject);

        return view('registrar.subjects.form', compact('subject'));
    }

    public function updateSubject(Request $request, $subject)
    {
        $subject = Subject::findOrFail($subject);
        $validated = $this->validateSubject($request, $subject->id);

        $subject->update($validated);
        AuditLog::record('updated_subject', 'Subject', $subject->id, 'Updated subject '.$subject->subject_code);

        return redirect()->route('registrar.subjects.showSubjects')
            ->with('success', 'Subject updated.');
    }

    public function deleteSubject(Request $request, $subject)
    {
        $subject = Subject::findOrFail($subject);

        if ($subject->sections()->exists()) {
            return back()->with('error', 'Cannot delete — subject is assigned to one or more sections.');
        }

        $code = $subject->subject_code;
        $subject->delete();
        AuditLog::record('deleted_subject', 'Subject', null, 'Deleted subject '.$code);

        return redirect()->route('registrar.subjects.showSubjects')
            ->with('success', 'Subject deleted.');
    }

    private function validateSubject(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'subject_code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'subject_code')->ignore($ignoreId)],
            'subject_name' => ['required', 'string', 'max:150'],
            'units'        => ['required', 'integer', 'min:1', 'max:6'],
        ]);
    }
}
