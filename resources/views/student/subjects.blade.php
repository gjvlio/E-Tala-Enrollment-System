@extends('layouts.student')
@section('title', 'My Subjects')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $subjects below is hardcoded so this page works standalone before
        Student\SubjectController@showSubjects passes real data.
        Expected shape per item: subject_code, subject_name, units,
        grade (nullable), status (enrolled/dropped/passed/failed)
    --}}
    @php
        $subjects = $subjects ?? collect([
            (object)['subject_code' => 'MATH101', 'subject_name' => 'Mathematics in the Modern World', 'units' => 3, 'grade' => null,  'status' => 'enrolled'],
            (object)['subject_code' => 'ENG101',  'subject_name' => 'Purposive Communication',          'units' => 3, 'grade' => null,  'status' => 'enrolled'],
            (object)['subject_code' => 'SCI101',  'subject_name' => 'General Biology 1',                'units' => 4, 'grade' => null,  'status' => 'enrolled'],
            (object)['subject_code' => 'FIL101',  'subject_name' => 'Komunikasyon at Pananaliksik',     'units' => 3, 'grade' => null,  'status' => 'enrolled'],
            (object)['subject_code' => 'PE101',   'subject_name' => 'Physical Education 1',             'units' => 2, 'grade' => null,  'status' => 'enrolled'],
        ]);

        $badgeClass = [
            'enrolled' => 'text-bg-primary',
            'passed'   => 'text-bg-success',
            'failed'   => 'text-bg-danger',
            'dropped'  => 'text-bg-secondary',
        ];
    @endphp

    <h4 class="fw-bold mb-4">My Subjects</h4>

    @if ($subjects->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            No subjects found. Make sure your enrollment is approved first.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Enrolled Subjects</span>
                <span class="badge text-bg-secondary">
                    {{ $subjects->sum('units') }} total units
                </span>
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
                                <td><span class="fw-mono fw-bold text-muted">{{ $subject->subject_code }}</span></td>
                                <td>{{ $subject->subject_name }}</td>
                                <td class="text-center">{{ $subject->units }}</td>
                                <td class="text-center">
                                    {{ $subject->grade !== null ? number_format($subject->grade, 2) : '—' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass[$subject->status] ?? 'text-bg-secondary' }}">
                                        {{ ucfirst($subject->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="fw-bold text-end">Total Units</td>
                            <td class="text-center fw-bold">{{ $subjects->sum('units') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

@endsection
