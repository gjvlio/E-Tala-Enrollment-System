<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Records') }}
        </h2>
    </x-slot>

    {{--
        DUMMY DATA NOTICE:
        $records below is hardcoded so this page works standalone before the
        backend wires up real data. Once Student\RecordController@showRecords
        uncomments its TODOs and passes real data, replace this block:

            return view('student.records', compact('records'));

        Each real $records item is expected to be a SemesterRecord model with:
        academic_year, semester, gpa, status (enum: e.g. 'completed', 'ongoing', 'incomplete')
    --}}
    @php
        $records = $records ?? collect([
            (object)['academic_year' => '2025-2026', 'semester' => '1st Semester', 'gpa' => 1.75, 'status' => 'completed'],
            (object)['academic_year' => '2025-2026', 'semester' => '2nd Semester', 'gpa' => 1.62, 'status' => 'completed'],
            (object)['academic_year' => '2026-2027', 'semester' => '1st Semester', 'gpa' => null, 'status' => 'ongoing'],
        ]);

        $statusStyles = [
            'completed' => 'bg-green-100 text-green-800',
            'ongoing'   => 'bg-blue-100 text-blue-800',
            'incomplete' => 'bg-red-100 text-red-800',
            'pending'   => 'bg-yellow-100 text-yellow-800',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">

            @include('layouts.student-sidebar')

            <div class="flex-1 bg-white overflow-hidden shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">Semester Records</h3>

                @if($records->isEmpty())
                    <p class="text-gray-500 text-sm">No semester records found yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($records as $record)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $record->academic_year }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $record->semester }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $record->gpa !== null ? number_format($record->gpa, 2) : '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusStyles[$record->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>