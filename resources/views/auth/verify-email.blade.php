@extends('layouts.guest')
@section('title', 'Verify Email')
@section('content')

    <div class="text-center mb-4">
        <i class="bi bi-envelope-check-fill text-primary" style="font-size: 2.5rem;"></i>
    </div>

    <p class="text-muted small mb-4 text-center">
        Thanks for signing up! Before getting started, please verify your email address by clicking
        the verification link we sent to your inbox. If you didn't receive the email, click the
        button below and we'll send another one.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-grid mb-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-send me-1"></i> Resend Verification Email
            </button>
        </form>
    </div>

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-muted p-0 small">
                <i class="bi bi-box-arrow-right me-1"></i> Log Out
            </button>
        </form>
    </div>

@endsection
