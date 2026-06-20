@extends('layouts.student')
@section('title', 'My Records')
@section('content')

    <h4 class="fw-bold mb-4">Semester Records</h4>

    @if ($records->isEmpty())
        <p class="text-muted">No semester records yet. Records appear after the registrar finalizes a semester.</p>
    @else
        <div class="card shadow-sm">
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
                                        <span class="badge text-bg-success">Finalized</span>
                                    @else
                                        <span class="badge text-bg-warning">Ongoing</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
