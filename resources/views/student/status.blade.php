@extends('layouts.student')
@section('title', 'Enrollment Status')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $enrollment and $semester below are hardcoded so this page works standalone
        before Student\EnrollmentController@showEnrollStatus passes real data.
        Expected shape: enrollment->status, enrollment->created_at,
        enrollment->section->section_name, enrollment->section->year_level,
        semester->school_year, semester->semester
    --}}
    @php
        $enrollment = $enrollment ?? (object)[
            'id'         => 1,
            'status'     => 'pending',
            'created_at' => now()->subDays(2),
            'section'    => (object)['section_name' => 'Grade 11 - STEM A', 'year_level' => 'Grade 11'],
        ];
        $semester = $semester ?? (object)['school_year' => '2025-2026', 'semester' => '1st Semester'];
    @endphp

    <h4 class="fw-bold mb-4">Enrollment Status</h4>

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
                    <p class="text-muted small mt-2 mb-0">Your enrollment is under review by the registrar. Please wait.</p>
                @elseif ($enrollment->status === 'approved')
                    <p class="text-muted small mt-2 mb-0">Your enrollment has been approved. You may now view your subjects.</p>
                @elseif ($enrollment->status === 'rejected')
                    <p class="text-muted small mt-2 mb-0">Your enrollment was rejected. Please contact the registrar for details.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-2">Active Semester</p>
                    <p class="fw-bold mb-0">{{ $semester->school_year }}</p>
                    <p class="text-muted small mb-0">{{ $semester->semester }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-2">Section</p>
                    <p class="fw-bold mb-0">{{ $enrollment->section->section_name }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted text-uppercase fw-bold mb-2">Date Submitted</p>
                    <p class="fw-bold mb-0">{{ $enrollment->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        @if ($enrollment->status === 'approved')
            <a href="{{ route('student.showSubjects') }}" class="btn btn-primary">
                <i class="bi bi-book me-1"></i> View My Subjects
            </a>
        @endif
        @if ($enrollment->status === 'rejected')
            <a href="{{ route('student.showEnrollForm') }}" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-1"></i> Re-apply
            </a>
        @endif
        <a href="{{ route('student.showDashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

@endsection
