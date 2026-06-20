@extends('layouts.student')
@section('title', 'My Section')
@section('content')

    <h4 class="fw-bold mb-4">My Section</h4>

    @if (! $section)
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            You don't have an assigned section yet. Enroll first — your section is set when you submit.
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <i class="bi bi-people-fill text-primary fs-2"></i>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $section->section_name }}</h5>
                        <span class="text-muted small">
                            {{ $section->strand->strand_code ?? '' }} &middot;
                            Grade {{ $section->grade_level }} &middot;
                            {{ $section->time_period }} &middot;
                            {{ $section->semester }} Semester
                        </span>
                    </div>
                    @php
                        $badgeClass = match($enrollment->status) {
                            'approved' => 'text-bg-success',
                            'rejected' => 'text-bg-danger',
                            default    => 'text-bg-warning',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} ms-auto">{{ ucfirst($enrollment->status) }}</span>
                </div>
                <p class="text-muted small mb-0">School Year {{ $section->schoolYear->year_label ?? '' }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Section Subjects ({{ $section->subjects->count() }})</div>
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
                        @foreach ($section->subjects as $subject)
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
