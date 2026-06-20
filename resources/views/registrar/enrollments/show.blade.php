@extends('layouts.registrar')
@section('title', 'Enrollment Detail')
@section('content')

    @php
        $badgeClass = match($enrollment->status) {
            'approved' => 'text-bg-success',
            'rejected' => 'text-bg-danger',
            default    => 'text-bg-warning',
        };
    @endphp

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.showEnrollments') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="fw-bold mb-0">Enrollment #{{ $enrollment->id }}</h4>
        <span class="badge {{ $badgeClass }} fs-6">{{ ucfirst($enrollment->status) }}</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Student Information</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Student No.</dt>
                        <dd class="col-7">{{ $enrollment->student->student_number }}</dd>
                        <dt class="col-5 text-muted">Full Name</dt>
                        <dd class="col-7">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</dd>
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $enrollment->student->user->email ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Strand / Grade</dt>
                        <dd class="col-7">{{ $enrollment->student->strand->strand_code ?? '—' }} &middot; Grade {{ $enrollment->student->grade_level }}</dd>
                        <dt class="col-5 text-muted">Phone</dt>
                        <dd class="col-7">{{ $enrollment->student->phone ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Enrollment Details</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">School Year</dt>
                        <dd class="col-7">{{ $enrollment->section->schoolYear->year_label ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Semester</dt>
                        <dd class="col-7">{{ $enrollment->section->semester }}</dd>
                        <dt class="col-5 text-muted">Section</dt>
                        <dd class="col-7">{{ $enrollment->section->section_name }} ({{ $enrollment->section->time_period }})</dd>
                        <dt class="col-5 text-muted">Submitted</dt>
                        <dd class="col-7">{{ $enrollment->submitted_at?->format('M d, Y g:i A') }}</dd>
                        @if ($enrollment->reviewed_at)
                            <dt class="col-5 text-muted">Reviewed</dt>
                            <dd class="col-7">{{ $enrollment->reviewed_at->format('M d, Y') }} by {{ $enrollment->approver->first_name ?? '—' }}</dd>
                        @endif
                        @if ($enrollment->remarks)
                            <dt class="col-5 text-muted">Remarks</dt>
                            <dd class="col-7 text-danger">{{ $enrollment->remarks }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Subjects ({{ $enrollment->subjects->count() }})</span>
                    @if ($enrollment->status === 'approved')
                        <a href="{{ route('registrar.showGradeForm', $enrollment->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i> Encode Grades
                        </a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Subject Name</th>
                                <th class="text-center">Units</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollment->subjects as $subject)
                                <tr>
                                    <td class="text-muted fw-bold">{{ $subject->subject_code }}</td>
                                    <td>{{ $subject->subject_name }}</td>
                                    <td class="text-center">{{ $subject->units }}</td>
                                    <td class="text-center">{{ $subject->pivot->grade !== null ? number_format($subject->pivot->grade, 2) : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Revert action — reopen a rejected application --}}
    @if ($enrollment->status === 'rejected')
        <div class="mt-4">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revertModal">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reopen (Revert to Pending)
            </button>
        </div>

        <div class="modal fade" id="revertModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('registrar.revertEnrollment', $enrollment->id) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reopen Enrollment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            This sets the application back to <strong>pending</strong> so it can be reviewed again.
                            A valid reason is required (e.g. the student complied with the missing requirements).
                        </p>
                        <label for="revert_reason" class="form-label">Reason for reopening</label>
                        <textarea name="revert_reason" id="revert_reason" rows="3" class="form-control"
                                  minlength="5" required
                                  placeholder="e.g. Student submitted the missing Form 138."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Reopen Application</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Approve / Reject actions --}}
    @if ($enrollment->status === 'pending')
        <div class="d-flex gap-3 mt-4">
            <form method="POST" action="{{ route('registrar.approveEnrollment', $enrollment->id) }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i> Approve
                </button>
            </form>

            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-lg me-1"></i> Reject
            </button>
        </div>

        {{-- Reject modal with remarks --}}
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('registrar.rejectEnrollment', $enrollment->id) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Enrollment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="remarks" class="form-label">Reason for rejection</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control"
                                  placeholder="e.g. Incomplete requirements — missing Form 138"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection
