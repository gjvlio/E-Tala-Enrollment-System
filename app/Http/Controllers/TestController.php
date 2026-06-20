<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    // Landing / role selection page.
    // Logged-in users skip straight to their dashboard.
    public function startPage(Request $request)
    {
        if ($user = $request->user()) {
            return $user->isRegistrar()
                ? redirect()->route('registrar.showDashboard')
                : redirect()->route('student.showDashboard');
        }

        return view('landing');
    }
}
