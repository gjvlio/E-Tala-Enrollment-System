<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    // Show the 2FA challenge form (user enters 6-digit code)
    public function showChallenge(Request $request)
    {
        // TODO: return view('auth.two-factor-challenge');
    }

    // Verify submitted 2FA code and log the user in
    public function postChallenge(Request $request)
    {
        // TODO: validate $request->code against user's two_factor_secret
        // Success: redirect to dashboard based on role
        // Failure: redirect back with error
    }
}
