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

       

        // TEMPORARY HARDCODED DATA — for local UI testing only.
        // Remove once Enrollment/Semester models + real queries are ready.

        $pendingCount = 7;

        $semester = (object) [
            'school_year' => '2025-2026',
            'semester' => '2nd',
        ];

        $recentEnrollments = collect([
            (object) [
                'id' => 1,
                'status' => 'pending',
                'created_at' => now()->subDays(2),
                'student' => (object) ['first_name' => 'Maria', 'last_name' => 'Santos'],
            ],
            (object) [
                'id' => 2,
                'status' => 'approved',
                'created_at' => now()->subDays(3),
                'student' => (object) ['first_name' => 'Juan', 'last_name' => 'Dela Cruz'],
            ],
            (object) [
                'id' => 3,
                'status' => 'rejected',
                'created_at' => now()->subDays(4),
                'student' => (object) ['first_name' => 'Ana', 'last_name' => 'Reyes'],
            ],
        ]);

        return view('registrar.dashboard', compact('pendingCount', 'semester', 'recentEnrollments'));
    }
}
