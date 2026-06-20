@extends('layouts.student')
@section('title', 'My Subjects')
@section('content')

    <h4 class="fw-bold mb-4">My Subjects</h4>

    @if (! $enrollment)
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            No enrollment found for the active semester. Enroll first to see your subjects.
        </div>
    @elseif ($subjects->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            No subjects attached to your enrollment yet.
        </div>
    @else
        @php
            $badgeClass = [
                'enrolled' => 'text-bg-primary',
                'passed'   => 'text-bg-success',
                'failed'   => 'text-bg-danger',
                'dropped'  => 'text-bg-secondary',
            ];
        @endphp

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">{{ $enrollment->section->section_name ?? 'Enrolled Subjects' }}</span>
                <span class="badge text-bg-secondary">{{ $subjects->sum('units') }} total units</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th class="text-center">Units</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td class="fw-bold text-muted">{{ $subject->subject_code }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td class="text-center">{{ $subject->units }}</td>
                                <td class="text-center">
                                    {{ $subject->pivot->grade !== null ? number_format($subject->pivot->grade, 2) : '—' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass[$subject->pivot->status] ?? 'text-bg-secondary' }}">
                                        {{ ucfirst($subject->pivot->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
