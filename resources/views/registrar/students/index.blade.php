@extends('layouts.registrar')
@section('title', 'Students')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Students</h4>
        <span class="text-muted small">{{ $students->count() }} total</span>
    </div>

    {{-- Search + filters --}}
    <form method="GET" action="{{ route('registrar.showStudents') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="Search by name or student number">
        </div>
        <div class="col-auto">
            <select name="strand" class="form-select">
                <option value="">All strands</option>
                @foreach ($strands as $strand)
                    <option value="{{ $strand->id }}" {{ request('strand') == $strand->id ? 'selected' : '' }}>{{ $strand->strand_code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="grade" class="form-select">
                <option value="">All grades</option>
                <option value="11" {{ request('grade') == '11' ? 'selected' : '' }}>Grade 11</option>
                <option value="12" {{ request('grade') == '12' ? 'selected' : '' }}>Grade 12</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if (request()->hasAny(['search', 'strand', 'grade']))
                <a href="{{ route('registrar.showStudents') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

    @if ($students->isEmpty())
        <div class="card shadow-sm"><div class="card-body text-center text-muted">No students found.</div></div>
    @else
        {{-- Folder-style grouping: Grade level → Strand --}}
        @foreach ($grouped as $gradeLabel => $byStrand)
            <h6 class="fw-bold text-uppercase text-muted mt-4 mb-2">
                <i class="bi bi-folder2-open me-1"></i> {{ $gradeLabel }}
            </h6>
            @foreach ($byStrand as $strandCode => $group)
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <span class="fw-bold">{{ $strandCode }}</span>
                        <span class="badge text-bg-secondary">{{ $group->count() }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $student)
                                    <tr>
                                        <td class="text-muted fw-bold">{{ $student->student_number }}</td>
                                        <td>
                                            <a href="{{ route('registrar.showStudent', $student->id) }}" class="text-decoration-none fw-semibold">
                                                {{ $student->last_name }}, {{ $student->first_name }}
                                            </a>
                                        </td>
                                        <td class="text-muted">{{ $student->user->email ?? '—' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('registrar.showStudent', $student->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                            <a href="{{ route('registrar.showSemesterRecord', $student->id) }}" class="btn btn-sm btn-outline-primary ms-1">Records</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif

@endsection
