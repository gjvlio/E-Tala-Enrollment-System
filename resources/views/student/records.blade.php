@extends('layouts.student')
@section('title', 'My Records')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $records below is hardcoded so this page works standalone before the
        backend wires up real data. Once Student\RecordController@showRecords
        uncomments its TODOs and passes real data, replace this block:

            return view('student.records', compact('records'));

        Each real $records item is expected to be a SemesterRecord model with:
        academic_year, semester, gpa, status (enum: 'completed', 'ongoing', 'incomplete')
    --}}
    @php
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

    <h4 class="fw-bold mb-4">Semester Records</h4>

    @if ($records->isEmpty())
        <p class="text-muted">No semester records found yet.</p>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>GPA</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <td>{{ $record->academic_year }}</td>
                                <td>{{ $record->semester }}</td>
                                <td>{{ $record->gpa !== null ? number_format($record->gpa, 2) : '—' }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass[$record->status] ?? 'text-bg-secondary' }}">
                                        {{ ucfirst($record->status) }}
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
