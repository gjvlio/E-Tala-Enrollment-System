<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Show registrar home — pending enrollment count, active semester, quick links
    public function showDashboard(Request $request)
    {
        // TODO: $pendingCount = \App\Models\Enrollment::where('status', 'pending')->count();
        // TODO: $semester = \App\Models\Semester::where('is_active', true)->first();
        // TODO: $recentEnrollments = \App\Models\Enrollment::with('student')->latest()->take(5)->get();
        // return view('registrar.dashboard', compact('pendingCount', 'semester', 'recentEnrollments'));
    }
}
