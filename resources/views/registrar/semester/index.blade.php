@extends('layouts.registrar')
@section('title', 'Semester Management')
@section('content')

    <h4 class="fw-bold mb-4">Semester Management</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    {{-- Create new school year --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Create School Year</div>
        <div class="card-body">
            <form method="POST" action="{{ route('registrar.semester.store') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label for="year_label" class="form-label">Year Label</label>
                    <input id="year_label" name="year_label" type="text" class="form-control"
                           placeholder="e.g. 2027-2028" required>
                </div>
                <div class="col-md-3">
                    <label for="active_semester" class="form-label">Semester</label>
                    <select id="active_semester" name="active_semester" class="form-select" required>
                        <option value="1st">1st Semester</option>
                        <option value="2nd">2nd Semester</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- School year list --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">School Years</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Year</th>
                        <th>Active</th>
                        <th>Active Semester</th>
                        <th>Enrollment</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schoolYears as $sy)
                        <tr>
                            <td class="fw-semibold">{{ $sy->year_label }}</td>
                            <td>
                                @if ($sy->is_active)
                                    <span class="badge text-bg-success">Active</span>
                                @else
                                    <span class="badge text-bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if ($sy->is_active)
                                    {{-- Switch active semester inline --}}
                                    <form method="POST" action="{{ route('registrar.semester.setSemester', $sy->id) }}" class="d-flex gap-1 align-items-center">
                                        @csrf @method('PATCH')
                                        <select name="active_semester" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                            <option value="1st" {{ $sy->active_semester === '1st' ? 'selected' : '' }}>1st Sem</option>
                                            <option value="2nd" {{ $sy->active_semester === '2nd' ? 'selected' : '' }}>2nd Sem</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-muted small">{{ $sy->active_semester }} sem</span>
                                @endif
                            </td>
                            <td>
                                @if ($sy->is_enrollment_open)
                                    <span class="badge text-bg-primary">Open</span>
                                @else
                                    <span class="badge text-bg-light text-dark">Closed</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @unless ($sy->is_active)
                                    <form method="POST" action="{{ route('registrar.semester.activate', $sy->id) }}" class="d-inline-flex gap-1 align-items-center">
                                        @csrf @method('PATCH')
                                        <select name="active_semester" class="form-select form-select-sm" style="width: auto;">
                                            <option value="1st" {{ $sy->active_semester === '1st' ? 'selected' : '' }}>1st Sem</option>
                                            <option value="2nd" {{ $sy->active_semester === '2nd' ? 'selected' : '' }}>2nd Sem</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-success">Set Active</button>
                                    </form>
                                @endunless

                                <form method="POST" action="{{ route('registrar.semester.toggleEnrollment', $sy->id) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $sy->is_enrollment_open ? 'btn-outline-danger' : 'btn-outline-primary' }}">
                                        {{ $sy->is_enrollment_open ? 'Close Enrollment' : 'Open Enrollment' }}
                                    </button>
                                </form>

                                <button type="button" class="btn btn-sm btn-outline-dark"
                                        data-bs-toggle="modal" data-bs-target="#finalize{{ $sy->id }}">
                                    Finalize
                                </button>

                                {{-- Finalize modal --}}
                                <div class="modal fade" id="finalize{{ $sy->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('registrar.semester.finalize', $sy->id) }}" class="modal-content text-start">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Finalize {{ $sy->year_label }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted small">
                                                    Computes GPA per student from encoded grades and locks their semester records.
                                                </p>
                                                <label for="semester{{ $sy->id }}" class="form-label">Semester</label>
                                                <select name="semester" id="semester{{ $sy->id }}" class="form-select" required>
                                                    <option value="1st">1st Semester</option>
                                                    <option value="2nd">2nd Semester</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-dark">Finalize &amp; Lock</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
