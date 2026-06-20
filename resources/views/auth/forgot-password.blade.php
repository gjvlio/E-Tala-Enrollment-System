@extends('layouts.guest')
@section('title', 'Forgot Password')
@section('content')

    <p class="text-muted small mb-4">
        <i class="bi bi-envelope-open me-1 text-primary"></i>
        Forgot your password? No problem — enter your registered email address below and we'll
        send you a link to reset it.
    </p>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control border-start-0 ps-1 @error('email') is-invalid @enderror"
                       placeholder="you@example.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-send me-1"></i> Send Reset Link
            </button>
        </div>

        <p class="text-center small text-muted mb-0">
            <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Log In
            </a>
        </p>
    </form>

@endsection
