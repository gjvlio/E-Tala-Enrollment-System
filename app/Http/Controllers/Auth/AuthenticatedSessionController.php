<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $registrarPortal = $request->query('portal') === 'registrar';
        if ($registrarPortal !== ($request->user()->role === 'registrar')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => $registrarPortal
                    ? 'This is the registrar portal. Students and applicants must use the student login.'
                    : 'This is a staff account. Please log in through the registrar portal.']);
        }

        $request->session()->regenerate();

        $user = $request->user();

        return redirect()->intended(
            $user->role === 'registrar'
                ? route('registrar.showDashboard', absolute: false)
                : route('student.showDashboard', absolute: false)
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
