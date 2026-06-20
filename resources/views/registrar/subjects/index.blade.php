@extends('layouts.registrar')
@section('title', 'Subjects')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $subjects below is hardcoded so this page works standalone before
        Registrar\SubjectController@showSubjects passes real data.
        Expected shape: id, subject_code, subject_name, units
    --}}
    @php
        $subjects = $subjects ?? collect([
            (object)['id' => 1, 'subject_code' => 'MATH101', 'subject_name' => 'Mathematics in the Modern World', 'units' => 3],
            (object)['id' => 2, 'subject_code' => 'ENG101',  'subject_name' => 'Purposive Communication',          'units' => 3],
            (object)['id' => 3, 'subject_code' => 'SCI101',  'subject_name' => 'General Biology 1',                'units' => 4],
            (object)['id' => 4, 'subject_code' => 'FIL101',  'subject_name' => 'Komunikasyon at Pananaliksik',     'units' => 3],
            (object)['id' => 5, 'subject_code' => 'PE101',   'subject_name' => 'Physical Education 1',             'units' => 2],
        ]);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Subjects</h4>
        <a href="{{ route('registrar.subjects.showCreateSubject') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Subject
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        @if ($subjects->isEmpty())
            <div class="card-body text-center text-muted">No subjects found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th class="text-center">Units</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td class="fw-bold text-muted">{{ $subject->subject_code }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td class="text-center">{{ $subject->units }}</td>
                                <td class="text-end">
                                    <a href="{{ route('registrar.subjects.showEditSubject', $subject->id) }}"
                                       class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form method="POST" action="{{ route('registrar.subjects.deleteSubject', $subject->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this subject?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
