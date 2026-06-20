@extends('layouts.registrar')
@section('title', 'Subjects')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Subjects</h4>
        <a href="{{ route('registrar.subjects.showCreateSubject') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Subject
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($subjects->isEmpty())
        <div class="card shadow-sm"><div class="card-body text-center text-muted">No subjects yet.</div></div>
    @else
        @foreach ($grouped as $prefix => $group)
            <h6 class="fw-bold text-uppercase text-muted mt-4 mb-2">{{ $prefix }}</h6>
            <div class="card shadow-sm mb-3">
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
                            @foreach ($group as $subject)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $subject->subject_code }}</td>
                                    <td>{{ $subject->subject_name }}</td>
                                    <td class="text-center">{{ $subject->units }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('registrar.subjects.showEditSubject', $subject->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('registrar.subjects.deleteSubject', $subject->id) }}" class="d-inline" onsubmit="return confirm('Delete this subject?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

@endsection
