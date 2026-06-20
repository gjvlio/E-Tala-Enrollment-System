@extends('layouts.registrar')
@section('title', 'Sections')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Sections</h4>
        <a href="{{ route('registrar.sections.showCreateSection') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Section
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($sections->isEmpty())
        <div class="card shadow-sm"><div class="card-body text-center text-muted">No sections yet.</div></div>
    @else
        @foreach ($grouped as $gradeLabel => $byStrand)
            <h6 class="fw-bold text-uppercase text-muted mt-4 mb-2">
                <i class="bi bi-folder2-open me-1"></i> {{ $gradeLabel }}
            </h6>
            @foreach ($byStrand as $strandCode => $group)
                <div class="card shadow-sm mb-3">
                    <div class="card-header py-2 fw-bold">{{ $strandCode }}</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Section</th>
                                    <th>Time</th>
                                    <th>Semester</th>
                                    <th>S.Y.</th>
                                    <th class="text-center">Slots</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $section)
                                    <tr>
                                        <td class="fw-semibold">{{ $section->section_name }}</td>
                                        <td><span class="badge text-bg-light">{{ $section->time_period }}</span></td>
                                        <td class="text-muted">{{ $section->semester }}</td>
                                        <td class="text-muted">{{ $section->schoolYear->year_label ?? '—' }}</td>
                                        <td class="text-center">
                                            {{ $section->approved_count }} / {{ $section->max_capacity }}
                                            @if ($section->approved_count >= $section->max_capacity)
                                                <span class="badge text-bg-danger ms-1">Full</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('registrar.sections.showEditSection', $section->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('registrar.sections.deleteSection', $section->id) }}" class="d-inline" onsubmit="return confirm('Delete this section?')">
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
        @endforeach
    @endif

@endsection
