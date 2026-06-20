@extends('layouts.registrar')
@section('title', $subject ? 'Edit Subject' : 'Add Subject')
@section('content')

    @php
        $isEdit = $subject !== null;
        $formAction = $isEdit
            ? route('registrar.subjects.updateSubject', $subject->id)
            : route('registrar.subjects.postCreateSubject');
    @endphp

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('registrar.subjects.showSubjects') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h4 class="fw-bold mb-0">{{ $isEdit ? 'Edit Subject' : 'Add Subject' }}</h4>
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
                @if ($isEdit) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input id="subject_code" name="subject_code" type="text" class="form-control"
                               value="{{ old('subject_code', $subject->subject_code ?? '') }}"
                               placeholder="e.g. CORE-ORALCOM" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input id="subject_name" name="subject_name" type="text" class="form-control"
                               value="{{ old('subject_name', $subject->subject_name ?? '') }}"
                               placeholder="e.g. Oral Communication" required>
                    </div>
                    <div class="col-md-2">
                        <label for="units" class="form-label">Units</label>
                        <input id="units" name="units" type="number" min="1" max="6" class="form-control"
                               value="{{ old('units', $subject->units ?? '') }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('registrar.subjects.showSubjects') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Subject' : 'Create Subject' }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
