@extends('layouts.guest')
@section('title', 'Confirm Password')
@section('content')

    <p class="text-muted small mb-4">
        <i class="bi bi-shield-lock me-1 text-warning"></i>
        This is a secure area of the application. Please confirm your current password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Current Password</label>
            <div class="input-password-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your current password"
                       required autocomplete="current-password">
                <button type="button" class="password-toggle" id="toggleConfirmPassword"
                        aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="toggleConfirmPasswordIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-shield-check me-1"></i> Confirm
            </button>
        </div>
    </form>

    <script>
        document.getElementById('toggleConfirmPassword')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('toggleConfirmPasswordIcon');
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
