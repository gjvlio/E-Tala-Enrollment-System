@extends('layouts.guest')
@section('title', 'Verify Email')
@section('content')

    <p class="text-muted small mb-3">
        Thanks for signing up! Before getting started, verify your email address by clicking the link
        we just emailed to you. If you didn't receive the email, we'll send another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted p-0">Log Out</button>
        </form>
    </div>

@endsection
