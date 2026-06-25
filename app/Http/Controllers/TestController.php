<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
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
