@extends('layouts.registrar')
@section('title', 'Student Semester Records')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $student and $records are hardcoded so this page works standalone before
        Registrar\SemesterRecordController@showSemesterRecord passes real data.
        Expected: student->{student_number, first_name, last_name},
        records[]->{academic_year, semester, gpa, status}
    --}}
    @php
        $student = $student ?? (object)[
            'student_number' => '2025-0001',
            'first_name'     => 'Maria',
            'last_name'      => 'Santos',
        ];
        $records = $records ?? collect([
            (object)['academic_year' => '2025-2026', 'semester' => '1st Semester', 'gpa' => 1.75, 'status' => 'completed'],
            (object)['academic_year' => '2025-2026', 'semester' => '2nd Semester', 'gpa' => 1.62, 'status' => 'completed'],
            (object)['academic_year' => '2026-2027', 'semester' => '1st Semester', 'gpa' => null,  'status' => 'ongoing'],
        ]);
        $badgeClass = [
            'completed'  => 'text-bg-success',
            'ongoing'    => 'text-bg-primary',
            'incomplete' => 'text-bg-danger',
            'pending'    => 'text-bg-warning',
        ];
    @endphp

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.showStudents') }}" class="btn btn-sm btn-outline-secondary">
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

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Semester Records</div>
        @if ($records->isEmpty())
            <div class="card-body text-muted text-center">No semester records yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th class="text-center">GPA</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $i => $record)
                            <tr>
                                <td>{{ $record->academic_year }}</td>
                                <td>{{ $record->semester }}</td>
                                <td class="text-center">
                                    {{ $record->gpa !== null ? number_format($record->gpa, 2) : '—' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass[$record->status] ?? 'text-bg-secondary' }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{-- Update GPA inline form --}}
                                    <button class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#updateForm{{ $i }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="updateForm{{ $i }}">
                                <td colspan="5" class="bg-light">
                                    <form method="POST" action="{{ route('registrar.updateSemesterRecord', 0) }}" class="d-flex gap-2 align-items-center p-2">
                                        {{-- Replace 0 with real $student->id when backend is wired --}}
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="academic_year" value="{{ $record->academic_year }}">
                                        <input type="hidden" name="semester" value="{{ $record->semester }}">
                                        <div>
                                            <label class="form-label small mb-1">GPA</label>
                                            <input type="number" step="0.01" min="1" max="5"
                                                   name="gpa" class="form-control form-control-sm"
                                                   value="{{ $record->gpa }}" style="width: 100px;">
                                        </div>
                                        <div>
                                            <label class="form-label small mb-1">Status</label>
                                            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                                @foreach (['ongoing', 'completed', 'incomplete'] as $s)
                                                    <option value="{{ $s }}" {{ $record->status === $s ? 'selected' : '' }}>
                                                        {{ ucfirst($s) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
