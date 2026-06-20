@extends('layouts.guest')
@section('title', 'Student Registration')
@section('content')

    <h4 class="fw-bold mb-1">Student Registration</h4>
    <p class="text-muted small mb-4">Create your account to start enrolling.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"
                       class="form-control @error('first_name') is-invalid @enderror"
                       required autofocus>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"
                       class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autocomplete="username">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone <span class="text-muted">(optional)</span></label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="birthdate" class="form-label">Birthdate <span class="text-muted">(optional)</span></label>
                <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}"
                       class="form-control @error('birthdate') is-invalid @enderror">
                @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="address" class="form-label">Address <span class="text-muted">(optional)</span></label>
                <input id="address" type="text" name="address" value="{{ old('address') }}"
                       class="form-control @error('address') is-invalid @enderror">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-7">
                <label for="strand_id" class="form-label">Strand</label>
                <select id="strand_id" name="strand_id" class="form-select @error('strand_id') is-invalid @enderror" required>
                    <option value="" disabled {{ old('strand_id') ? '' : 'selected' }}>Select strand</option>
                    @foreach ($strands as $strand)
                        <option value="{{ $strand->id }}" {{ old('strand_id') == $strand->id ? 'selected' : '' }}>
                            {{ $strand->strand_code }} — {{ $strand->strand_name }}
                        </option>
                    @endforeach
                </select>
                @error('strand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-5">
                <label for="grade_level" class="form-label">Grade Level</label>
                <select id="grade_level" name="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                    <option value="" disabled {{ old('grade_level') ? '' : 'selected' }}>Select grade</option>
                    <option value="11" {{ old('grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                    <option value="12" {{ old('grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                </select>
                @error('grade_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="new-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control" required autocomplete="new-password">
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            <a class="text-muted small" href="{{ route('login') }}">Already have an account?</a>
            <button type="submit" class="btn btn-primary">Create Account</button>
        </div>
    </form>

@endsection
