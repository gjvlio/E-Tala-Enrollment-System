<x-app-layout>
    <x-slot name="header">{{ $header ?? '' }}</x-slot>

    <div class="flex h-full w-full overflow-hidden">

        {{-- Student sidebar --}}
        <aside class="w-64 shrink-0 bg-white border-r border-gray-200 overflow-y-auto p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Student Portal</p>
            <nav class="space-y-1">
                <a href="{{ route('student.showDashboard') }}"
                   class="block px-4 py-3 rounded-lg text-base font-semibold {{ request()->routeIs('student.showDashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('student.showEnrollForm') }}"
                   class="block px-4 py-3 rounded-lg text-base font-semibold {{ request()->routeIs('student.showEnrollForm') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Enrollment Form
                </a>
                <a href="{{ route('student.showEnrollStatus') }}"
                   class="block px-4 py-3 rounded-lg text-base font-semibold {{ request()->routeIs('student.showEnrollStatus') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Enrollment Status
                </a>
                <a href="{{ route('student.showSubjects') }}"
                   class="block px-4 py-3 rounded-lg text-base font-semibold {{ request()->routeIs('student.showSubjects') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    My Subjects
                </a>
                <a href="{{ route('student.showRecords') }}"
                   class="block px-4 py-3 rounded-lg text-base font-semibold {{ request()->routeIs('student.showRecords') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    My Records
                </a>
            </nav>
        </aside>

        {{-- Page content --}}
        <div class="flex-1 min-w-0 overflow-y-auto overflow-x-hidden p-8 bg-gray-50 h-full">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>