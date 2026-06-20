@extends('layouts.registrar')
@section('title', 'Enrollment Detail')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $enrollment below is hardcoded so this page works standalone before
        Registrar\EnrollmentController@showEnrollment passes real data.
        Expected shape: id, status, created_at,
        student->{first_name, last_name, student_number, phone, birthdate},
        section->{section_name, year_level},
        semester->{school_year, semester},
        subjects[]->{subject_code, subject_name, units}
    --}}
    @php
        $enrollment = $enrollment ?? (object)[
            'id'         => 1,
            'status'     => 'pending',
            'created_at' => now()->subDays(2),
            'student'    => (object)[
                'first_name'     => 'Maria',
                'last_name'      => 'Santos',
                'student_number' => '2025-0001',
                'phone'          => '09171234567',
                'birthdate'      => '2009-03-15',
            ],
            'section'    => (object)['section_name' => 'Grade 11 - STEM A', 'year_level' => 'Grade 11'],
            'semester'   => (object)['school_year' => '2025-2026', 'semester' => '1st Semester'],
            'subjects'   => collect([
                (object)['subject_code' => 'MATH101', 'subject_name' => 'Mathematics in the Modern World', 'units' => 3],
                (object)['subject_code' => 'ENG101',  'subject_name' => 'Purposive Communication',          'units' => 3],
                (object)['subject_code' => 'SCI101',  'subject_name' => 'General Biology 1',                'units' => 4],
            ]),
        ];
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

    <div class="row g-4">
        {{-- Student Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Student Information</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Student No.</dt>
                        <dd class="col-7">{{ $enrollment->student->student_number }}</dd>

                        <dt class="col-5 text-muted">Full Name</dt>
                        <dd class="col-7">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</dd>

                        <dt class="col-5 text-muted">Birthdate</dt>
                        <dd class="col-7">{{ $enrollment->student->birthdate }}</dd>

                        <dt class="col-5 text-muted">Phone</dt>
                        <dd class="col-7">{{ $enrollment->student->phone }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Enrollment Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Enrollment Details</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Semester</dt>
                        <dd class="col-7">{{ $enrollment->semester->semester }} — S.Y. {{ $enrollment->semester->school_year }}</dd>

                        <dt class="col-5 text-muted">Section</dt>
                        <dd class="col-7">{{ $enrollment->section->section_name }}</dd>

                        <dt class="col-5 text-muted">Year Level</dt>
                        <dd class="col-7">{{ $enrollment->section->year_level }}</dd>

                        <dt class="col-5 text-muted">Submitted</dt>
                        <dd class="col-7">{{ $enrollment->created_at->format('M d, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Subjects --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Enrolled Subjects</span>
                    <span class="badge text-bg-secondary">{{ $enrollment->subjects->sum('units') }} units</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Subject Name</th>
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
        </div>
    </div>

    {{-- Approve / Reject actions --}}
    @if ($enrollment->status === 'pending')
        <div class="d-flex gap-2 mt-4">
            <form method="POST" action="{{ route('registrar.approveEnrollment', $enrollment->id) }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i> Approve
                </button>
            </form>
            <form method="POST" action="{{ route('registrar.rejectEnrollment', $enrollment->id) }}">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-lg me-1"></i> Reject
                </button>
            </form>
        </div>
    @endif

@endsection
