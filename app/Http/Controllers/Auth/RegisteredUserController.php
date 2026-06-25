<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        if (! $this->admissionOpen()) {
            return view('auth.registration-closed');
        }

        return view('auth.register');
    }

    // New-applicant registration is gated by the active school year's enrollment
    // window. No active year, or a closed window, means intake is closed.
    private function admissionOpen(): bool
    {
        return (bool) optional(SchoolYear::active())->is_enrollment_open;
    }

    // Registration only makes the applicant login. The full form, student
    // profile and School ID come later, after email verification + admission.
    public function store(Request $request): RedirectResponse|View
    {
        // Block submissions too — the window may have closed after the page loaded.
        if (! $this->admissionOpen()) {
            return view('auth.registration-closed');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'birthdate'  => ['required', 'date', 'before:today'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the account and send the verification email atomically — if the
        // email can't be sent, roll back so no orphan account is left behind.
        try {
            $user = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name'      => $validated['first_name'].' '.$validated['last_name'],
                    'email'     => $validated['email'],
                    'birthdate' => $validated['birthdate'],
                    'password'  => Hash::make($validated['password']),
                    'role'      => 'student',
                ]);

                event(new Registered($user)); // sends verification mail; throws → rolls back

                return $user;
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'We could not complete your registration right now (email service unavailable). Please try again.']);
        }

        Auth::login($user);
        return redirect()->route('verification.notice');
    }
}
