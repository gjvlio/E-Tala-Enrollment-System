<x-layouts.student>
    <x-slot name="header">Dashboard</x-slot>

    <div class="flex flex-col h-full gap-6">

        {{-- Welcome --}}
        <div>
            <h1 class="text-4xl font-bold text-gray-800">
                Welcome, {{ $student->first_name ?? auth()->user()->name }}!
            </h1>
            <p class="text-lg text-gray-500 mt-1">
                Student No: {{ $student->student_number ?? '—' }}
            </p>
        </div>

        {{-- Active Semester Banner --}}
        @if ($semester)
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-6 py-4 flex items-center gap-4">
                <svg class="w-7 h-7 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg font-semibold text-indigo-700">
                    Active Semester: <span class="font-normal">{{ $semester->semester }} — S.Y. {{ $semester->school_year }}</span>
                </p>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-6 py-4">
                <p class="text-lg font-semibold text-yellow-700">No active semester at the moment. Please check back later.</p>
            </div>
        @endif

        {{-- Status Cards --}}
        <div class="grid grid-cols-3 gap-5">
            <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between" style="min-height: 130px;">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Enrollment Status</p>
                @if ($enrollment)
                    @php
                        $statusColor = match($enrollment->status) {
                            'approved' => 'text-green-700 bg-green-50 border border-green-200',
                            'rejected' => 'text-red-700 bg-red-50 border border-red-200',
                            default    => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                        };
                    @endphp
                    <span class="inline-block px-4 py-2 rounded-lg text-lg font-bold {{ $statusColor }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                @else
                    <span class="inline-block px-4 py-2 rounded-lg text-lg font-bold text-gray-500 bg-gray-100 border border-gray-200">
                        Not Enrolled
                    </span>
                @endif
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between" style="min-height: 130px;">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Section</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $enrollment->section->section_name ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between" style="min-height: 130px;">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Year Level</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $enrollment->section->year_level ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Quick Actions — flex-1 so it fills the remaining screen height --}}
        <div class="flex flex-col flex-1 min-h-0">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4 flex-1">

                @if (!$enrollment || $enrollment->status === 'rejected')
                    <a href="{{ route('student.showEnrollForm') }}"
                       class="flex items-center gap-4 border-2 border-indigo-300 bg-indigo-50 rounded-xl px-6 text-xl text-indigo-700 font-bold hover:bg-indigo-100 transition">
                        <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Enroll Now
                    </a>
                @else
                    <a href="{{ route('student.showEnrollStatus') }}"
                       class="flex items-center gap-4 border border-gray-200 bg-white rounded-xl px-6 text-xl text-gray-700 font-bold hover:bg-gray-50 transition">
                        <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-2"/>
                        </svg>
                        View Enrollment Status
                    </a>
                @endif

                <a href="{{ route('student.showSubjects') }}"
                   class="flex items-center gap-4 border border-gray-200 bg-white rounded-xl px-6 text-xl text-gray-700 font-bold hover:bg-gray-50 transition">
                    <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    My Subjects
                </a>

                <a href="{{ route('student.showRecords') }}"
                   class="flex items-center gap-4 border border-gray-200 bg-white rounded-xl px-6 text-xl text-gray-700 font-bold hover:bg-gray-50 transition">
                    <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    My Records
                </a>

            </div>
        </div>

    </div>

</x-layouts.student>