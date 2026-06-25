@extends('layouts.guest')
@section('title', 'Enrollment Closed')
@section('content')

    <div class="text-center py-3">
        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center text-warning mb-3" style="width:72px;height:72px;">
            <i class="bi bi-lock-fill" style="font-size:2.2rem;"></i>
        </div>

        <h4 class="fw-bold mb-2">Enrollment is Currently Closed</h4>
        <p class="text-muted mb-4">
            New student registration is not open right now. The enrollment period has ended or
            has not yet started. Please wait for further updates from the school.
        </p>

        <div class="d-flex flex-column gap-2">
            <a href="{{ route('login') }}" class="btn btn-success">
                <i class="bi bi-box-arrow-in-right me-1"></i> Already have an account? Log In
            </a>
            <a href="{{ route('landing') }}" class="btn btn-link text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to portal selection
            </a>
        </div>
    </div>

@endsection
