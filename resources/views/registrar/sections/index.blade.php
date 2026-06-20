@extends('layouts.registrar')
@section('title', 'Sections')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $sections below is hardcoded so this page works standalone before
        Registrar\SectionController@showSections passes real data.
        Expected shape: id, section_name, year_level, slots,
        semester->{school_year, semester}
    --}}
    @php
        $sections = $sections ?? collect([
            (object)['id' => 1, 'section_name' => 'Grade 7 - Section A',  'year_level' => 'Grade 7',  'slots' => 40, 'semester' => (object)['school_year' => '2025-2026', 'semester' => '1st Semester']],
            (object)['id' => 2, 'section_name' => 'Grade 8 - Section B',  'year_level' => 'Grade 8',  'slots' => 38, 'semester' => (object)['school_year' => '2025-2026', 'semester' => '1st Semester']],
            (object)['id' => 3, 'section_name' => 'Grade 11 - STEM A',    'year_level' => 'Grade 11', 'slots' => 45, 'semester' => (object)['school_year' => '2025-2026', 'semester' => '1st Semester']],
            (object)['id' => 4, 'section_name' => 'Grade 12 - ABM A',     'year_level' => 'Grade 12', 'slots' => 40, 'semester' => (object)['school_year' => '2025-2026', 'semester' => '1st Semester']],
        ]);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Sections</h4>
        <a href="{{ route('registrar.sections.showCreateSection') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Section
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        @if ($sections->isEmpty())
            <div class="card-body text-center text-muted">No sections found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Section Name</th>
                            <th>Year Level</th>
                            <th class="text-center">Slots</th>
                            <th>Semester</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section)
                            <tr>
                                <td>
                                    <a href="{{ route('registrar.sections.showSection', $section->id) }}" class="fw-bold text-decoration-none">
                                        {{ $section->section_name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $section->year_level }}</td>
                                <td class="text-center">{{ $section->slots }}</td>
                                <td class="text-muted">{{ $section->semester->semester }} — S.Y. {{ $section->semester->school_year }}</td>
                                <td class="text-end">
                                    <a href="{{ route('registrar.sections.showEditSection', $section->id) }}"
                                       class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form method="POST" action="{{ route('registrar.sections.deleteSection', $section->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this section?')">
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
