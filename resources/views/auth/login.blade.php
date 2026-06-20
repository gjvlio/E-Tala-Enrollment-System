@extends('layouts.guest')
@section('title', 'Log In')
@section('content')

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control border-start-0 ps-1 @error('email') is-invalid @enderror"
                       placeholder="you@example.com"
                       required autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-semibold mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-primary text-decoration-none" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-password-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password"
                       required autocomplete="current-password">
                <button type="button" class="password-toggle" id="toggleLoginPassword"
                        aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="toggleLoginPasswordIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Password hint based on actual backend rules: minimum 8 characters --}}
            <p class="password-hint">
                <i class="bi bi-info-circle-fill text-primary opacity-75"></i>
                Your password must be at least 8 characters long.
            </p>
        </div>

        {{-- Remember me --}}
        <div class="mb-4 form-check">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label text-muted">Remember me</label>
        </div>

        {{-- Submit --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-1"></i> Log In
            </button>
        </div>

        <p class="text-center small text-muted mt-3 mb-0">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Register here</a>
        </p>
    </form>

    <script>
        // Show/hide password toggle — UI only, no backend logic
        document.getElementById('toggleLoginPassword')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('toggleLoginPasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>

@endsection
