<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // List all enrollments — filterable by status (pending/approved/rejected)
    public function showEnrollments(Request $request)
    {
        // TODO: $enrollments = \App\Models\Enrollment::with(['student', 'semester', 'section'])
        //           ->when($request->status, fn($q) => $q->where('status', $request->status))
        //           ->latest()->paginate(20);
        // return view('registrar.enrollments.index', compact('enrollments'));
        return view('registrar.enrollments.index'); // TEMP: Matan added this to preview UI
    }

    // Show a single enrollment with student info and enrolled subjects
    public function showEnrollment(Request $request, $enrollment)
    {
        // TODO: $enrollment = \App\Models\Enrollment::with(['student', 'section', 'subjects'])->findOrFail($enrollment);
        // return view('registrar.enrollments.show', compact('enrollment'));
        return view('registrar.enrollments.show'); // TEMP: remove once real data is wired up
    }

    // Approve an enrollment — set status = 'approved', record approver and timestamp
    public function approveEnrollment(Request $request, $enrollment)
    {
        // TODO: $enrollment = \App\Models\Enrollment::findOrFail($enrollment);
        // TODO: $registrar = auth()->user()->registrar;
        // TODO: $enrollment->update(['status' => 'approved', 'approved_by' => $registrar->id, 'approved_at' => now()]);
        // redirect back with success message
    }

    // Reject an enrollment — set status = 'rejected', record approver and timestamp
    public function rejectEnrollment(Request $request, $enrollment)
    {
        // TODO: $enrollment = \App\Models\Enrollment::findOrFail($enrollment);
        // TODO: $registrar = auth()->user()->registrar;
        // TODO: $enrollment->update(['status' => 'rejected', 'approved_by' => $registrar->id, 'approved_at' => now()]);
        // redirect back with success message
    }
}
