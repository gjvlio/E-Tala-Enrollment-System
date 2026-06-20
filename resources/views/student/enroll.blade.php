@extends('layouts.student')
@section('title', 'Online Enrollment Form')
@section('content')

    {{--
        DUMMY DATA NOTICE:
        $gradeLevels, $strands, and $schoolYears below are hardcoded so this page
        works standalone before the backend wires up real data. Once
        Student\EnrollmentController@showEnrollForm passes real data, replace
        this block with values from $gradeLevels / $strands / $schoolYears
        passed from the controller.
    --}}
    @php
        $gradeLevels = $gradeLevels ?? ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands     = $strands ?? ['STEM', 'ABM', 'HUMSS', 'GAS', 'TVL'];
        $schoolYears = $schoolYears ?? ['2025-2026', '2026-2027'];
    @endphp

    <h4 class="fw-bold mb-4">Online Enrollment Form</h4>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('student.postEnrollForm') }}">
                @csrf

                <h5 class="mb-3">Student Information</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input id="first_name" name="first_name" type="text"
                               class="form-control" value="{{ old('first_name') }}" required autofocus>
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input id="last_name" name="last_name" type="text"
                               class="form-control" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input id="birthdate" name="birthdate" type="date"
                               class="form-control" value="{{ old('birthdate') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input id="contact_number" name="contact_number" type="text"
                               class="form-control" value="{{ old('contact_number') }}" required>
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input id="address" name="address" type="text"
                               class="form-control" value="{{ old('address') }}" required>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Enrollment Details</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-select" required>
                            <option value="" disabled selected>Select grade level</option>
                            @foreach ($gradeLevels as $level)
                                <option value="{{ $level }}" {{ old('grade_level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="strand" class="form-label">Strand</label>
                        <select name="strand" id="strand" class="form-select">
                            <option value="" selected>N/A</option>
                            @foreach ($strands as $strand)
                                <option value="{{ $strand }}" {{ old('strand') == $strand ? 'selected' : '' }}>
                                    {{ $strand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="school_year" class="form-label">School Year</label>
                        <select name="school_year" id="school_year" class="form-select" required>
                            <option value="" disabled selected>Select school year</option>
                            @foreach ($schoolYears as $sy)
                                <option value="{{ $sy }}" {{ old('school_year') == $sy ? 'selected' : '' }}>
                                    {{ $sy }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('student.showDashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Enrollment</button>
                </div>
            </form>
        </div>
    </div>

@endsection
