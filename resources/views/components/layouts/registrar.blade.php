<x-app-layout>
    <x-slot name="header">{{ $header ?? '' }}</x-slot>

    <div class="flex h-full">

        {{-- Registrar sidebar --}}
        <aside class="w-56 bg-white border-r border-gray-200 shrink-0 overflow-y-auto p-4">
            <nav class="space-y-1">
                <a href="{{ route('registrar.showDashboard') }}"
                   class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('registrar.showDashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('registrar.showEnrollments') }}"
                   class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('registrar.showEnrollments') || request()->routeIs('registrar.showEnrollment') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Enrollments
                </a>
                <a href="{{ route('registrar.sections.showSections') }}"
                   class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('registrar.sections.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Sections
                </a>
                <a href="{{ route('registrar.subjects.showSubjects') }}"
                   class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('registrar.subjects.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Subjects
                </a>
                <a href="{{ route('registrar.showStudents') }}"
                   class="block px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('registrar.showStudents') || request()->routeIs('registrar.showStudent') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    Students
                </a>
            </nav>
        </aside>

        {{-- Page content --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>