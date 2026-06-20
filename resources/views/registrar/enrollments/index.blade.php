@extends('layouts.registrar')
@section('title', 'Enrollment Queue')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $enrollments below is hardcoded so this page works standalone before
        the backend wires up real data. Once
        Registrar\EnrollmentController@showEnrollments uncomments its TODOs
        and passes a real paginated collection, replace this block.

        Expected real shape per enrollment: id, status, created_at,
        student->first_name, student->last_name, section->section_name
    --}}
    @php
        $enrollments = $enrollments ?? collect([
            (object)[
                'id' => 1, 'status' => 'pending', 'created_at' => now()->subDays(2),
                'student' => (object)['first_name' => 'Maria', 'last_name' => 'Santos'],
                'section' => (object)['section_name' => 'Grade 11 - STEM A'],
            ],
            (object)[
                'id' => 2, 'status' => 'pending', 'created_at' => now()->subDays(1),
                'student' => (object)['first_name' => 'Carlos', 'last_name' => 'Mendoza'],
                'section' => (object)['section_name' => 'Grade 10 - Section B'],
            ],
            (object)[
                'id' => 3, 'status' => 'approved', 'created_at' => now()->subDays(3),
                'student' => (object)['first_name' => 'Juan', 'last_name' => 'Dela Cruz'],
                'section' => (object)['section_name' => 'Grade 12 - ABM A'],
            ],
            (object)[
                'id' => 4, 'status' => 'rejected', 'created_at' => now()->subDays(4),
                'student' => (object)['first_name' => 'Ana', 'last_name' => 'Reyes'],
                'section' => (object)['section_name' => 'Grade 9 - Section C'],
            ],
        ]);

        $currentFilter = request('status');
    @endphp

    <h4 class="fw-bold mb-4">Enrollment Queue</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Status filter tabs --}}
    <ul class="nav nav-tabs mb-3">
        @php $tabs = ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']; @endphp
        @foreach ($tabs as $value => $label)
            <li class="nav-item">
                <a href="{{ route('registrar.showEnrollments', $value ? ['status' => $value] : []) }}"
                   class="nav-link {{ ($currentFilter === $value || (!$currentFilter && $value === '')) ? 'active' : '' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card shadow-sm">
        @if ($enrollments->isEmpty())
            <div class="card-body text-center text-muted">No enrollments found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}">
                                        {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $enrollment->section->section_name }}</td>
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
                                <td class="text-muted">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    @if ($enrollment->status === 'pending')
                                        <form method="POST" action="{{ route('registrar.approveEnrollment', $enrollment->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('registrar.rejectEnrollment', $enrollment->id) }}" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @else
                                        <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}"
                                           class="btn btn-sm btn-outline-secondary">View</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
