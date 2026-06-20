@extends('layouts.registrar')
@section('title', $section ? 'Edit Section' : 'Add Section')
@section('content')

    @php
        $isEdit = $section !== null;
        $formAction = $isEdit
            ? route('registrar.sections.updateSection', $section->id)
            : route('registrar.sections.postCreateSection');
        $assigned = $isEdit ? $section->subjects->pluck('id')->all() : [];
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

    <form method="POST" action="{{ $formAction }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Section Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="section_name" class="form-label">Section Name</label>
                        <input id="section_name" name="section_name" type="text" class="form-control"
                               value="{{ old('section_name', $section->section_name ?? '') }}"
                               placeholder="e.g. Kasipagan" required>
                    </div>
                    <div class="col-md-3">
                        <label for="strand_id" class="form-label">Strand</label>
                        <select id="strand_id" name="strand_id" class="form-select" required>
                            <option value="" disabled {{ old('strand_id', $section->strand_id ?? '') ? '' : 'selected' }}>Select</option>
                            @foreach ($strands as $strand)
                                <option value="{{ $strand->id }}" {{ old('strand_id', $section->strand_id ?? '') == $strand->id ? 'selected' : '' }}>{{ $strand->strand_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="grade_level" class="form-label">Grade</label>
                        <select id="grade_level" name="grade_level" class="form-select" required>
                            <option value="11" {{ old('grade_level', $section->grade_level ?? '') == '11' ? 'selected' : '' }}>Grade 11</option>
                            <option value="12" {{ old('grade_level', $section->grade_level ?? '') == '12' ? 'selected' : '' }}>Grade 12</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select id="semester" name="semester" class="form-select" required>
                            <option value="1st" {{ old('semester', $section->semester ?? '') == '1st' ? 'selected' : '' }}>1st</option>
                            <option value="2nd" {{ old('semester', $section->semester ?? '') == '2nd' ? 'selected' : '' }}>2nd</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="time_period" class="form-label">Time</label>
                        <select id="time_period" name="time_period" class="form-select" required>
                            <option value="AM" {{ old('time_period', $section->time_period ?? '') == 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="PM" {{ old('time_period', $section->time_period ?? '') == 'PM' ? 'selected' : '' }}>PM</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="max_capacity" class="form-label">Max Capacity</label>
                        <input id="max_capacity" name="max_capacity" type="number" min="1" max="100" class="form-control"
                               value="{{ old('max_capacity', $section->max_capacity ?? 40) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="school_year_id" class="form-label">School Year</label>
                        <select id="school_year_id" name="school_year_id" class="form-select" required>
                            @foreach ($schoolYears as $sy)
                                <option value="{{ $sy->id }}" {{ old('school_year_id', $section->school_year_id ?? '') == $sy->id ? 'selected' : '' }}>
                                    {{ $sy->year_label }}{{ $sy->is_active ? ' (active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Assigned Subjects <span class="text-muted small fw-normal">— these are auto-enrolled for students in this section</span></div>
            <div class="card-body">
                <div class="row">
                    @foreach ($allSubjects as $subject)
                        <div class="col-md-6 col-lg-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="subject_ids[]"
                                       value="{{ $subject->id }}" id="subj{{ $subject->id }}"
                                       {{ in_array($subject->id, old('subject_ids', $assigned)) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="subj{{ $subject->id }}">
                                    <span class="text-muted">{{ $subject->subject_code }}</span> — {{ $subject->subject_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('registrar.sections.showSections') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Section' : 'Create Section' }}</button>
        </div>
    </form>

@endsection
