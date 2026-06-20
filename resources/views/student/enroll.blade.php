@extends('layouts.student')
@section('title', 'Online Enrollment')
@section('content')

    <h4 class="fw-bold mb-4">Online Enrollment</h4>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    {{-- Gate: enrollment closed --}}
    @if ($blocked === 'closed')
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-lock-fill text-muted" style="font-size: 2.5rem;"></i>
                <h5 class="fw-bold mt-3">Enrollment is currently closed</h5>
                <p class="text-muted mb-0">
                    @if ($schoolYear)
                        Enrollment for S.Y. {{ $schoolYear->year_label }} has not been opened by the registrar yet.
                    @else
                        There is no active school year at the moment.
                    @endif
                </p>
            </div>
        </div>

    {{-- Gate: rejected — frozen for this semester --}}
    @elseif ($blocked === 'rejected')
        <div class="card shadow-sm border-danger">
            <div class="card-body py-5">
                <div class="text-center mb-3">
                    <i class="bi bi-lock-fill text-danger" style="font-size: 2.5rem;"></i>
                    <h5 class="fw-bold mt-3 text-danger">Application Frozen</h5>
                </div>
                <div class="alert alert-danger">
                    <strong>Registrar feedback:</strong> {{ $existing->remarks ?? 'No reason given.' }}
                </div>
                <p class="text-muted text-center mb-4">
                    Your enrollment was rejected and is frozen for this semester. Please comply with the
                    requirements above. You cannot re-apply on your own — the registrar must reopen your
                    application once you've complied, or you may apply again next semester.
                </p>
                <div class="text-center">
                    <a href="{{ route('student.showEnrollStatus') }}" class="btn btn-outline-danger">View Status</a>
                </div>
            </div>
        </div>

    {{-- Gate: already enrolled --}}
    @elseif ($blocked === 'enrolled')
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                <h5 class="fw-bold mt-3">You're already enrolled this semester</h5>
                <p class="text-muted">
                    Your enrollment is currently
                    <span class="fw-semibold">{{ ucfirst($existing->status) }}</span>.
                </p>
                <a href="{{ route('student.showEnrollStatus') }}" class="btn btn-primary">View Status</a>
            </div>
        </div>

    {{-- Section picker --}}
    @else
        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-info-circle"></i>
            <span>
                Showing sections for <strong>{{ $student->strand?->strand_code }}</strong>,
                <strong>Grade {{ $student->grade_level }}</strong>,
                S.Y. {{ $schoolYear->year_label }} &middot; {{ $schoolYear->active_semester }} Semester.
                Subjects are fixed per section — picking a section enrolls you in all its subjects.
            </span>
        </div>

        @if ($sections->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    No sections available for your strand and grade level yet.
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('student.postEnrollForm') }}">
                @csrf
                <div class="row g-3">
                    @foreach ($sections as $section)
                        <div class="col-md-6">
                            <label class="card h-100" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="section_id"
                                               value="{{ $section->id }}" id="section{{ $section->id }}"
                                               {{ old('section_id') == $section->id ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-bold" for="section{{ $section->id }}">
                                            {{ $section->section_name }}
                                            <span class="badge text-bg-light ms-1">{{ $section->time_period }}</span>
                                        </label>
                                    </div>
                                    <p class="text-muted small mt-2 mb-2">
                                        Capacity: {{ $section->max_capacity }} &middot;
                                        {{ $section->subjects->count() }} subjects
                                    </p>
                                    <details>
                                        <summary class="small text-primary">View subjects</summary>
                                        <ul class="small text-muted mt-2 mb-0">
                                            @foreach ($section->subjects as $subject)
                                                <li>{{ $subject->subject_code }} — {{ $subject->subject_name }}</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('student.showDashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Enrollment</button>
                </div>
            </form>
        @endif
    @endif

@endsection
