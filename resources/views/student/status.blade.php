@extends('layouts.student')
@section('title', 'Enrollment Status')
@section('content')

    <h4 class="fw-bold mb-4">Enrollment Status</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (! $enrollment)
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
                <h5 class="fw-bold mt-3">No enrollment yet</h5>
                <p class="text-muted">You haven't submitted an enrollment for the active semester.</p>
                <a href="{{ route('student.showEnrollForm') }}" class="btn btn-primary">Enroll Now</a>
            </div>
        </div>
    @else
        @php
            $badgeClass = match($enrollment->status) {
                'approved' => 'text-bg-success',
                'rejected' => 'text-bg-danger',
                default    => 'text-bg-warning',
            };
            $icon = match($enrollment->status) {
                'approved' => 'bi-check-circle-fill text-success',
                'rejected' => 'bi-x-circle-fill text-danger',
                default    => 'bi-hourglass-split text-warning',
            };
        @endphp

        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center gap-4 py-4">
                <i class="bi {{ $icon }} fs-1"></i>
                <div>
                    <p class="small text-muted text-uppercase fw-bold mb-1">Current Status</p>
                    <span class="badge {{ $badgeClass }} fs-5 px-3 py-2">{{ ucfirst($enrollment->status) }}</span>
                    @if ($enrollment->status === 'pending')
                        <p class="text-muted small mt-2 mb-0">Your enrollment is under review by the registrar.</p>
                    @elseif ($enrollment->status === 'approved')
                        <p class="text-muted small mt-2 mb-0">Approved — you may now view your subjects.</p>
                    @elseif ($enrollment->status === 'rejected')
                        <p class="text-danger small mt-2 mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <strong>Registrar feedback:</strong> {{ $enrollment->remarks ?? 'No reason given.' }}
                        </p>
                        <p class="text-muted small mb-0">
                            Your application is <strong>frozen for this semester</strong>. Please comply with the
                            requirements above. You cannot re-apply on your own — the registrar must reopen your
                            application once you've complied, or you may apply again next semester.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="small text-muted text-uppercase fw-bold mb-2">School Year</p>
                        <p class="fw-bold mb-0">{{ $enrollment->section->schoolYear->year_label ?? '—' }}</p>
                        <p class="text-muted small mb-0">{{ $enrollment->section->semester ?? '' }} Semester</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="small text-muted text-uppercase fw-bold mb-2">Section</p>
                        <p class="fw-bold mb-0">{{ $enrollment->section->section_name }}</p>
                        <p class="text-muted small mb-0">
                            {{ $enrollment->section->strand->strand_code ?? '' }} &middot;
                            Grade {{ $enrollment->section->grade_level }} &middot;
                            {{ $enrollment->section->time_period }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="small text-muted text-uppercase fw-bold mb-2">Date Submitted</p>
                        <p class="fw-bold mb-0">{{ $enrollment->submitted_at?->format('M d, Y') ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Enrolled Subjects ({{ $enrollment->subjects->count() }})</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th class="text-center">Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollment->subjects as $subject)
                            <tr>
                                <td class="text-muted fw-bold">{{ $subject->subject_code }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td class="text-center">{{ $subject->units }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

@endsection
