@extends('layouts.registrar')
@section('title', 'Student Semester Records')
@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.showStudent', $student->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</h4>
            <span class="text-muted small">Student No: {{ $student->student_number }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Semester Records</div>
        @if ($records->isEmpty())
            <div class="card-body text-muted text-center">No semester records yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th class="text-center">GPA</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <td>{{ $record->schoolYear->year_label ?? '—' }}</td>
                                <td>{{ $record->semester }} Semester</td>
                                <td class="text-center">{{ $record->gpa !== null ? number_format($record->gpa, 2) : '—' }}</td>
                                <td class="text-center">
                                    @if ($record->is_locked)
                                        <span class="badge text-bg-success">Locked</span>
                                    @else
                                        <span class="badge text-bg-warning">Open</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Manual record entry/override --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Add / Update a Record</div>
        <div class="card-body">
            <form method="POST" action="{{ route('registrar.updateSemesterRecord', $student->id) }}" class="row g-2 align-items-end">
                @csrf @method('PUT')
                <div class="col-md-4">
                    <label for="school_year_id" class="form-label">School Year</label>
                    <select id="school_year_id" name="school_year_id" class="form-select" required>
                        @foreach ($schoolYears as $sy)
                            <option value="{{ $sy->id }}">{{ $sy->year_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="semester" class="form-label">Semester</label>
                    <select id="semester" name="semester" class="form-select" required>
                        <option value="1st">1st</option>
                        <option value="2nd">2nd</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="gpa" class="form-label">GPA</label>
                    <input id="gpa" name="gpa" type="number" step="0.01" min="1" max="5" class="form-control">
                </div>
                <div class="col-auto">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="is_locked" value="1" id="is_locked">
                        <label class="form-check-label" for="is_locked">Lock</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection
