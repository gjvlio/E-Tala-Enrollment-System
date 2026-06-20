@extends('layouts.registrar')
@section('title', 'Registrar Dashboard')
@section('content')

    <h4 class="fw-bold mb-4">Registrar Dashboard</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Pending Enrollments</p>
                    <p class="h3 fw-bold text-warning mb-2">{{ $pendingCount }}</p>
                    <a href="{{ route('registrar.showEnrollments', ['status' => 'pending']) }}"
                       class="small text-primary">Review pending &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Active Semester</p>
                    @if ($semester)
                        <p class="h3 fw-bold mb-1">{{ $semester->school_year }}</p>
                        <p class="small text-muted mb-0">{{ ucfirst($semester->semester) }} semester</p>
                    @else
                        <p class="text-muted fst-italic mb-0">No active semester set</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <p class="small text-muted fw-bold text-uppercase mb-2">Quick Links</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1">
                            <a href="{{ route('registrar.showStudents') }}" class="small text-primary">View Students</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('registrar.sections.showSections') }}" class="small text-primary">Manage Sections</a>
                        </li>
                        <li>
                            <a href="{{ route('registrar.subjects.showSubjects') }}" class="small text-primary">Manage Subjects</a>
                        </li>
                    </ul>
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
                            <th>Status</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentEnrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</td>
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
                                    <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}"
                                       class="small text-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
