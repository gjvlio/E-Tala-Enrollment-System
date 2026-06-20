@extends('layouts.registrar')
@section('title', 'Registrar Dashboard')
@section('content')

    <h4 class="fw-bold mb-4">Registrar Dashboard</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($schoolYear)
        <div class="alert {{ $schoolYear->is_enrollment_open ? 'alert-success' : 'alert-secondary' }} d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-calendar3 me-2"></i>
                Active S.Y. <strong>{{ $schoolYear->year_label }}</strong>
                &middot; <strong>{{ $schoolYear->active_semester }} Semester</strong> —
                Enrollment is <strong>{{ $schoolYear->is_enrollment_open ? 'OPEN' : 'CLOSED' }}</strong>
            </span>
            <a href="{{ route('registrar.semester.index') }}" class="btn btn-sm btn-outline-dark">Manage</a>
        </div>
    @else
        <div class="alert alert-warning">No active school year. <a href="{{ route('registrar.semester.index') }}">Set one up</a>.</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Pending</p>
                    <p class="h3 fw-bold text-warning mb-2">{{ $pendingCount }}</p>
                    <a href="{{ route('registrar.showEnrollments', ['status' => 'pending']) }}" class="small text-primary">Review &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Approved</p>
                    <p class="h3 fw-bold text-success mb-0">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Rejected</p>
                    <p class="h3 fw-bold text-danger mb-0">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Approved by Strand</p>
                    @forelse ($perStrand as $strand => $total)
                        <div class="d-flex justify-content-between small">
                            <span>{{ $strand }}</span><span class="fw-bold">{{ $total }}</span>
                        </div>
                    @empty
                        <span class="text-muted small">—</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Recent Enrollments</h6>
            <a href="{{ route('registrar.showEnrollments') }}" class="small text-primary">View all &rarr;</a>
        </div>
        @if ($recentEnrollments->isEmpty())
            <div class="card-body text-center text-muted">No enrollments yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentEnrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</td>
                                <td class="text-muted">
                                    {{ $enrollment->section->strand->strand_code ?? '' }} - {{ $enrollment->section->section_name }}
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($enrollment->status) {
                                            'approved' => 'text-bg-success',
                                            'rejected' => 'text-bg-danger',
                                            default    => 'text-bg-warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($enrollment->status) }}</span>
                                </td>
                                <td class="text-muted">{{ $enrollment->submitted_at?->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="small text-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
