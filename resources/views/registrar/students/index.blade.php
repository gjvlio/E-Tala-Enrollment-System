@extends('layouts.registrar')
@section('title', 'Students')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $students below is hardcoded so this page works standalone before
        Registrar\StudentController@showStudents passes real data.
        Expected shape: id, student_number, first_name, last_name,
        user->{email}, latestEnrollment->{status} (nullable)
    --}}
    @php
        $students = $students ?? collect([
            (object)['id' => 1, 'student_number' => '2025-0001', 'first_name' => 'Maria',  'last_name' => 'Santos',    'user' => (object)['email' => 'maria@example.com'],   'latestEnrollment' => (object)['status' => 'approved']],
            (object)['id' => 2, 'student_number' => '2025-0002', 'first_name' => 'Carlos', 'last_name' => 'Mendoza',   'user' => (object)['email' => 'carlos@example.com'],  'latestEnrollment' => (object)['status' => 'pending']],
            (object)['id' => 3, 'student_number' => '2025-0003', 'first_name' => 'Juan',   'last_name' => 'Dela Cruz', 'user' => (object)['email' => 'juan@example.com'],    'latestEnrollment' => (object)['status' => 'rejected']],
            (object)['id' => 4, 'student_number' => '2025-0004', 'first_name' => 'Ana',    'last_name' => 'Reyes',     'user' => (object)['email' => 'ana@example.com'],     'latestEnrollment' => null],
        ]);
        $badgeClass = [
            'approved' => 'text-bg-success',
            'pending'  => 'text-bg-warning',
            'rejected' => 'text-bg-danger',
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Students</h4>
        <span class="text-muted small">{{ $students->count() }} total</span>
    </div>

    <div class="card shadow-sm">
        @if ($students->isEmpty())
            <div class="card-body text-center text-muted">No students found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Enrollment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td class="text-muted fw-bold">{{ $student->student_number }}</td>
                                <td>
                                    <a href="{{ route('registrar.showStudent', $student->id) }}" class="fw-bold text-decoration-none">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $student->user->email }}</td>
                                <td class="text-center">
                                    @if ($student->latestEnrollment)
                                        <span class="badge {{ $badgeClass[$student->latestEnrollment->status] ?? 'text-bg-secondary' }}">
                                            {{ ucfirst($student->latestEnrollment->status) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('registrar.showStudent', $student->id) }}"
                                       class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('registrar.showSemesterRecord', $student->id) }}"
                                       class="btn btn-sm btn-outline-primary ms-1">Records</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
