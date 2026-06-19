{{--
    layouts/student-sidebar.blade.php

    A simple partial (not a component), included directly via:
        @include('layouts.student-sidebar')

    This avoids Blade's <x-layouts.x> component auto-discovery entirely,
    which was failing to resolve on this Laravel/Breeze setup.
--}}
<aside class="w-64 bg-white shadow-sm rounded-lg p-4 shrink-0">
    <nav class="space-y-1">
        <a href="{{ route('student.showDashboard') }}"
           class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('student.showDashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            Dashboard
        </a>
        <a href="{{ route('student.showEnrollForm') }}"
           class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('student.showEnrollForm') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            Enrollment Form
        </a>
        <a href="{{ route('student.showEnrollStatus') }}"
           class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('student.showEnrollStatus') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            Enrollment Status
        </a>
        <a href="{{ route('student.showSubjects') }}"
           class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('student.showSubjects') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            My Subjects
        </a>
        <a href="{{ route('student.showRecords') }}"
           class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('student.showRecords') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            My Records
        </a>
    </nav>
</aside>