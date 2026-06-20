@extends('layouts.registrar')
@section('title', 'Encode Grades')
@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="fw-bold mb-0">Encode Grades</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-bold mb-1">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</h6>
            <span class="text-muted small">
                {{ $enrollment->student->student_number }} &middot;
                {{ $enrollment->section->strand->strand_code ?? '' }} - {{ $enrollment->section->section_name }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('registrar.updateGrades', $enrollment->id) }}">
        @csrf @method('PUT')

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th style="width: 130px;">Grade (1.00–5.00)</th>
                            <th style="width: 160px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollment->enrollmentSubjects as $es)
                            <tr>
                                <td class="text-muted fw-bold">{{ $es->subject->subject_code }}</td>
                                <td>{{ $es->subject->subject_name }}</td>
                                <td>
                                    <input type="number" step="0.01" min="1" max="5"
                                           name="grades[{{ $es->id }}][grade]" value="{{ $es->grade }}"
                                           class="form-control form-control-sm">
                                </td>
                                <td>
                                    <select name="grades[{{ $es->id }}][status]" class="form-select form-select-sm">
                                        @foreach (['enrolled', 'passed', 'failed', 'dropped'] as $st)
                                            <option value="{{ $st }}" {{ $es->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Grades</button>
        </div>
    </form>

@endsection
