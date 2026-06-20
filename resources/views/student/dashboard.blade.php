@extends('layouts.student')
@section('title', 'Dashboard')
@section('content')

    <h2 class="fw-bold mb-1">Welcome, {{ $student->first_name ?? auth()->user()?->name ?? 'Student' }}!</h2>
    <p class="text-muted mb-4">Student No: {{ $student->student_number ?? '—' }}</p>

    @if ($semester)
        <div class="alert alert-primary d-flex align-items-center gap-3 mb-4">
            <i class="bi bi-calendar3 fs-4"></i>
            <span class="fw-semibold">Active Semester: {{ $semester->semester }} — S.Y. {{ $semester->school_year }}</span>
        </div>
    @else
        <div class="alert alert-warning mb-4">No active semester at the moment. Please check back later.</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-3">Enrollment Status</p>
                    @if ($enrollment)
                        @php
                            $badgeClass = match($enrollment->status) {
                                'approved' => 'text-bg-success',
                                'rejected' => 'text-bg-danger',
                                default    => 'text-bg-warning',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} fs-6">{{ ucfirst($enrollment->status) }}</span>
                    @else
                        <span class="badge text-bg-secondary fs-6">Not Enrolled</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-3">Section</p>
                    <p class="h4 fw-bold mb-0">{{ $enrollment->section->section_name ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-3">Year Level</p>
                    <p class="h4 fw-bold mb-0">{{ $enrollment->section->year_level ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <h6 class="text-muted text-uppercase fw-bold mb-3">Quick Actions</h6>
    <div class="row g-3">
        @if (!$enrollment || $enrollment->status === 'rejected')
            <div class="col-md-6">
                <a href="{{ route('student.showEnrollForm') }}" class="card text-decoration-none h-100 border-primary">
                    <div class="card-body d-flex align-items-center gap-3">
                        <i class="bi bi-plus-circle fs-3 text-primary"></i>
                        <span class="fw-bold fs-5 text-primary">Enroll Now</span>
                    </div>
                </a>
            </div>
        @else
            <div class="col-md-6">
                <a href="{{ route('student.showEnrollStatus') }}" class="card text-decoration-none h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <i class="bi bi-check2-square fs-3 text-secondary"></i>
                        <span class="fw-bold fs-5 text-dark">View Enrollment Status</span>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-md-6">
            <a href="{{ route('student.showSubjects') }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-book fs-3 text-secondary"></i>
                    <span class="fw-bold fs-5 text-dark">My Subjects</span>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('student.showRecords') }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-bar-chart-line fs-3 text-secondary"></i>
                    <span class="fw-bold fs-5 text-dark">My Records</span>
                </div>
            </a>
        </div>
    </div>

@endsection
