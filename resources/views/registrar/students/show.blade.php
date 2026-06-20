@extends('layouts.registrar')
@section('title', 'Student Detail')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $student and $enrollments are hardcoded so this page works standalone before
        Registrar\StudentController@showStudent passes real data.
        Expected: student->{id, student_number, first_name, last_name, phone, birthdate, user->{email}},
        enrollments[]->{id, status, created_at, section->{section_name}, semester->{school_year, semester}}
    --}}
    @php
        $student = $student ?? (object)[
            'id'             => 1,
            'student_number' => '2025-0001',
            'first_name'     => 'Maria',
            'last_name'      => 'Santos',
            'phone'          => '09171234567',
            'birthdate'      => '2009-03-15',
            'user'           => (object)['email' => 'maria@example.com'],
        ];
        $enrollments = $enrollments ?? collect([
            (object)[
                'id'         => 1,
                'status'     => 'approved',
                'created_at' => now()->subDays(30),
                'section'    => (object)['section_name' => 'Grade 11 - STEM A'],
                'semester'   => (object)['school_year' => '2025-2026', 'semester' => '1st Semester'],
            ],
        ]);
        $badgeClass = [
            'approved' => 'text-bg-success',
            'pending'  => 'text-bg-warning',
            'rejected' => 'text-bg-danger',
        ];
    @endphp

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
                <div class="card-header fw-bold">Student Profile</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Student No.</dt>
                        <dd class="col-7">{{ $student->student_number }}</dd>

                        <dt class="col-5 text-muted">Full Name</dt>
                        <dd class="col-7">{{ $student->first_name }} {{ $student->last_name }}</dd>

                        <dt class="col-5 text-muted">Birthdate</dt>
                        <dd class="col-7">{{ $student->birthdate }}</dd>

                        <dt class="col-5 text-muted">Phone</dt>
                        <dd class="col-7">{{ $student->phone }}</dd>

                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $student->user->email }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-flex flex-column gap-3">
            <a href="{{ route('registrar.showSemesterRecord', $student->id) }}"
               class="card text-decoration-none h-50">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-bar-chart-line fs-3 text-primary"></i>
                    <span class="fw-bold text-dark">View Semester Records</span>
                </div>
            </a>
            <a href="{{ route('registrar.showEnrollments', ['student' => $student->id]) }}"
               class="card text-decoration-none h-50">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-list-check fs-3 text-secondary"></i>
                    <span class="fw-bold text-dark">View All Enrollments</span>
                </div>
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Enrollment History</div>
        @if ($enrollments->isEmpty())
            <div class="card-body text-muted text-center">No enrollment records found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Semester</th>
                            <th>Section</th>
                            <th class="text-center">Status</th>
                            <th>Submitted</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->semester->semester }} — S.Y. {{ $enrollment->semester->school_year }}</td>
                                <td class="text-muted">{{ $enrollment->section->section_name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass[$enrollment->status] ?? 'text-bg-secondary' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}"
                                       class="btn btn-sm btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
