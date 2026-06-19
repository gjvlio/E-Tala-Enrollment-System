<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrollment Queue') }}
        </h2>
    </x-slot>

    {{--
        DUMMY DATA NOTICE:
        $enrollments below is hardcoded so this page works standalone before
        the backend wires up real data. Once
        Registrar\EnrollmentController@showEnrollments uncomments its TODOs
        and passes a real paginated collection, replace this block.

        Expected real shape per enrollment: id, status, created_at,
        student->first_name, student->last_name, section->section_name,
        semester->school_year
    --}}
    @php
        $enrollments = $enrollments ?? collect([
            (object)[
                'id' => 1, 'status' => 'pending', 'created_at' => now()->subDays(2),
                'student' => (object)['first_name' => 'Maria', 'last_name' => 'Santos'],
                'section' => (object)['section_name' => 'Grade 11 - STEM A'],
            ],
            (object)[
                'id' => 2, 'status' => 'pending', 'created_at' => now()->subDays(1),
                'student' => (object)['first_name' => 'Carlos', 'last_name' => 'Mendoza'],
                'section' => (object)['section_name' => 'Grade 10 - Section B'],
            ],
            (object)[
                'id' => 3, 'status' => 'approved', 'created_at' => now()->subDays(3),
                'student' => (object)['first_name' => 'Juan', 'last_name' => 'Dela Cruz'],
                'section' => (object)['section_name' => 'Grade 12 - ABM A'],
            ],
            (object)[
                'id' => 4, 'status' => 'rejected', 'created_at' => now()->subDays(4),
                'student' => (object)['first_name' => 'Ana', 'last_name' => 'Reyes'],
                'section' => (object)['section_name' => 'Grade 9 - Section C'],
            ],
        ]);

        $statusStyles = [
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        $currentFilter = request('status');
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status filter tabs --}}
            <div class="flex gap-2 border-b border-gray-200">
                @php
                    $tabs = ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
                @endphp
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('registrar.showEnrollments', $value ? ['status' => $value] : []) }}"
                       class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentFilter === $value || (!$currentFilter && $value === '') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($enrollments->isEmpty())
                    <p class="px-6 py-8 text-center text-gray-400">No enrollments found.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Student</th>
                                <th class="px-6 py-3">Section</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Submitted</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td class="px-6 py-4 text-gray-800">
                                        <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="hover:underline">
                                            {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $enrollment->section->section_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusStyles[$enrollment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $enrollment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if ($enrollment->status === 'pending')
                                            <form method="POST" action="{{ route('registrar.approveEnrollment', $enrollment->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('registrar.rejectEnrollment', $enrollment->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('registrar.showEnrollment', $enrollment->id) }}" class="text-indigo-600 hover:underline">
                                                View
                                            </a>
                                        @endif
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