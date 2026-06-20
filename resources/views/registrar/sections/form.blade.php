@extends('layouts.registrar')
@section('title', isset($section) ? 'Edit Section' : 'Add Section')
@section('content')

    {{--
        Used for both Create (GET /registrar/sections/create)
        and Edit (GET /registrar/sections/{section}/edit).
        When editing, the controller passes $section with: id, section_name,
        year_level, slots, semester_id.
        $semesters is a list of available semesters for the dropdown.
    --}}
    @php
        $section = $section ?? null;
        $semesters = $semesters ?? collect([
            (object)['id' => 1, 'school_year' => '2025-2026', 'semester' => '1st Semester'],
            (object)['id' => 2, 'school_year' => '2025-2026', 'semester' => '2nd Semester'],
        ]);
        $yearLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $isEdit = $section !== null;
        $formAction = $isEdit
            ? route('registrar.sections.updateSection', $section->id)
            : route('registrar.sections.postCreateSection');
    @endphp

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.sections.showSections') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="fw-bold mb-0">{{ $isEdit ? 'Edit Section' : 'Add Section' }}</h4>
    </div>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ $formAction }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="section_name" class="form-label">Section Name</label>
                        <input id="section_name" name="section_name" type="text"
                               class="form-control"
                               value="{{ old('section_name', $section->section_name ?? '') }}"
                               placeholder="e.g. Grade 11 - STEM A" required>
                    </div>

                    <div class="col-md-6">
                        <label for="year_level" class="form-label">Year Level</label>
                        <select id="year_level" name="year_level" class="form-select" required>
                            <option value="" disabled>Select year level</option>
                            @foreach ($yearLevels as $level)
                                <option value="{{ $level }}"
                                    {{ old('year_level', $section->year_level ?? '') === $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="slots" class="form-label">Total Slots</label>
                        <input id="slots" name="slots" type="number" min="1" max="100"
                               class="form-control"
                               value="{{ old('slots', $section->slots ?? '') }}"
                               placeholder="e.g. 40" required>
                    </div>

                    <div class="col-md-8">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select id="semester_id" name="semester_id" class="form-select" required>
                            <option value="" disabled>Select semester</option>
                            @foreach ($semesters as $sem)
                                <option value="{{ $sem->id }}"
                                    {{ old('semester_id', $section->semester_id ?? '') == $sem->id ? 'selected' : '' }}>
                                    {{ $sem->semester }} — S.Y. {{ $sem->school_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('registrar.sections.showSections') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update Section' : 'Create Section' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
