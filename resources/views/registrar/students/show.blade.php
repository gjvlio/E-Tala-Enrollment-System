@extends('layouts.registrar')
@section('title', 'Student Detail')
@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.showStudents') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</h4>
        <span class="text-muted">{{ $student->student_number }}</span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Profile</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Student No.</dt>
                        <dd class="col-7">{{ $student->student_number }}</dd>
                        <dt class="col-5 text-muted">Full Name</dt>
                        <dd class="col-7">{{ $student->first_name }} {{ $student->last_name }}</dd>
                        <dt class="col-5 text-muted">Strand / Grade</dt>
                        <dd class="col-7">{{ $student->strand->strand_code ?? '—' }} &middot; Grade {{ $student->grade_level }}</dd>
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $student->user->email ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Phone</dt>
                        <dd class="col-7">{{ $student->phone ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Birthdate</dt>
                        <dd class="col-7">{{ $student->birthdate ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex flex-column gap-3">
            <a href="{{ route('registrar.showSemesterRecord', $student->id) }}" class="card text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-bar-chart-line fs-3 text-primary"></i>
                    <span class="fw-bold text-dark">View Semester Records</span>
                </div>
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Enrollment History</div>
        @if ($student->enrollments->isEmpty())
            <div class="card-body text-muted text-center">No enrollment records.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>School Year</th>
                            <th>Section</th>
                            <th class="text-center">Status</th>
                            <th>Submitted</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($student->enrollments->sortByDesc('submitted_at') as $enrollment)
                            <tr>
                                <td>{{ $enrollment->section->schoolYear->year_label ?? '—' }}</td>
                                <td class="text-muted">
                                    {{ $enrollment->section->strand->strand_code ?? '' }} - {{ $enrollment->section->section_name }}
                                </td>
                                <td class="text-center">
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
                                    <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
