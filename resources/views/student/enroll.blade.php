<x-layouts.student>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Online Enrollment Form') }}
        </h2>
    </x-slot>

    {{--
        DUMMY DATA NOTICE:
        $gradeLevels, $strands, and $schoolYears below are hardcoded so this page
        works standalone before the backend wires up real data. Once
        Student\EnrollmentController@showEnrollForm passes real data, replace
        this block with values from $gradeLevels / $strands / $schoolYears
        passed from the controller, e.g.:

            return view('student.enrollment-form', [
                'gradeLevels' => GradeLevel::all(),
                'strands'     => Strand::all(),
                'schoolYears' => SchoolYear::all(),
            ]);
    --}}
    @php
        $gradeLevels = $gradeLevels ?? ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands     = $strands ?? ['STEM', 'ABM', 'HUMSS', 'GAS', 'TVL'];
        $schoolYears = $schoolYears ?? ['2025-2026', '2026-2027'];
    @endphp

    <h3 class="text-lg font-semibold mb-4">Student Information</h3>

    <form method="POST" action="{{ route('student.postEnrollForm') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="block mt-1 w-full" :value="old('first_name')" required autofocus />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="block mt-1 w-full" :value="old('last_name')" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="birthdate" :value="__('Birthdate')" />
                <x-text-input id="birthdate" name="birthdate" type="date" class="block mt-1 w-full" :value="old('birthdate')" required />
                <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="contact_number" :value="__('Contact Number')" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="block mt-1 w-full" :value="old('contact_number')" required />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="block mt-1 w-full" :value="old('address')" required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <h3 class="text-lg font-semibold mt-6 mb-4">Enrollment Details</h3>

        <div class="grid grid-cols-3 gap-4">

            {{-- Grade level dropdown --}}
            <div>
                <x-input-label for="grade_level" :value="__('Grade Level')" />
                <select name="grade_level" id="grade_level" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="" disabled selected>Select grade level</option>
                    @foreach($gradeLevels as $level)
                        <option value="{{ $level }}" {{ old('grade_level') == $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
            </div>

            {{-- Strand dropdown (relevant for SHS) --}}
            <div>
                <x-input-label for="strand" :value="__('Strand')" />
                <select name="strand" id="strand"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="" selected>N/A</option>
                    @foreach($strands as $strand)
                        <option value="{{ $strand }}" {{ old('strand') == $strand ? 'selected' : '' }}>
                            {{ $strand }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('strand')" class="mt-2" />
            </div>

            {{-- School year dropdown --}}
            <div>
                <x-input-label for="school_year" :value="__('School Year')" />
                <select name="school_year" id="school_year" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="" disabled selected>Select school year</option>
                    @foreach($schoolYears as $sy)
                        <option value="{{ $sy }}" {{ old('school_year') == $sy ? 'selected' : '' }}>
                            {{ $sy }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('school_year')" class="mt-2" />
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('student.showDashboard') }}" class="text-sm text-gray-600 hover:underline">
                {{ __('Cancel') }}
            </a>
            <x-primary-button>
                {{ __('Submit Enrollment') }}
            </x-primary-button>
        </div>

    </form>

</x-layouts.student>
