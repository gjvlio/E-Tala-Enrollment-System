<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash message (set by approve/reject redirects elsewhere) --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Pending Enrollments</p>
                    <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                    <a href="{{ route('registrar.showEnrollments', ['status' => 'pending']) }}"
                       class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                        Review pending &rarr;
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Active Semester</p>
                    @if ($semester)
                        <p class="mt-2 text-3xl font-bold text-gray-800">
                            {{ $semester->school_year }}
                        </p>
                        <p class="text-sm text-gray-500">{{ ucfirst($semester->semester) }} semester</p>
                    @else
                        <p class="mt-2 text-lg text-gray-400 italic">No active semester set</p>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Quick Links</p>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li>
                            <a href="{{ route('registrar.showStudents') }}" class="text-indigo-600 hover:text-indigo-800">
                                View Students
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('registrar.sections.showSections') }}" class="text-indigo-600 hover:text-indigo-800">
                                Manage Sections
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('registrar.subjects.showSubjects') }}" class="text-indigo-600 hover:text-indigo-800">
                                Manage Subjects
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Recent enrollments --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-800">Recent Enrollments</h3>
                    <a href="{{ route('registrar.showEnrollments') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        View all &rarr;
                    </a>
                </div>

                @if ($recentEnrollments->isEmpty())
                    <p class="px-6 py-8 text-center text-gray-400">No enrollments yet.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Student</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Submitted</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentEnrollments as $enrollment)
                                <tr>
                                    <td class="px-6 py-4 text-gray-800">
                                        {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @class([
                                                'bg-yellow-100 text-yellow-800' => $enrollment->status === 'pending',
                                                'bg-green-100 text-green-800' => $enrollment->status === 'approved',
                                                'bg-red-100 text-red-800' => $enrollment->status === 'rejected',
                                            ])">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $enrollment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}"
                                           class="text-indigo-600 hover:text-indigo-800">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>