<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the student registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'strands' => Strand::orderBy('strand_code')->get(),
        ]);
    }

    /**
     * Handle an incoming student registration request.
     *
     * Creates a users row (role=student) and its linked students profile
     * in a single transaction, then fires email verification.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'       => ['nullable', 'string', 'max:20'],
            'birthdate'   => ['nullable', 'date'],
            'address'     => ['nullable', 'string', 'max:255'],
            'strand_id'   => ['required', 'exists:strands,id'],
            'grade_level' => ['required', 'in:11,12'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['first_name'].' '.$validated['last_name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'student',
            ]);

            Student::create([
                'user_id'        => $user->id,
                'student_number' => $this->generateStudentNumber(),
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'phone'          => $validated['phone'] ?? null,
                'birthdate'      => $validated['birthdate'] ?? null,
                'address'        => $validated['address'] ?? null,
                'strand_id'      => $validated['strand_id'],
                'grade_level'    => $validated['grade_level'],
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        // MustVerifyEmail → student is bounced to the verify-email prompt by the
        // 'verified' middleware until they confirm.
        return redirect()->route('student.showDashboard');
    }

    /** Generate a unique student number like 2026-00006. */
    private function generateStudentNumber(): string
    {
        $year = date('Y');
        $seq  = Student::count() + 1;

        do {
            $number = $year.'-'.str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
            $seq++;
        } while (Student::where('student_number', $number)->exists());

        return $number;
    }
}
