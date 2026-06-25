<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Services\ScheduleGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Grade12ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $sy = DB::table('school_years')->where('is_active', true)->value('id')
            ?? DB::table('school_years')->value('id');

        $strandId = fn (string $code) => DB::table('strands')->where('strand_code', $code)->value('id');
        $core = DB::table('subjects')->where('subject_code', 'like', 'CORE-%')->pluck('id')->all();
        $strandSubjects = fn (string $code) => DB::table('subjects')->where('subject_code', 'like', $code.'-%')->pluck('id')->all();

        $password = Hash::make('password');

        $scenarios = [
            ['STEM',  'Gabriela Silang', 'AM', 40, 40],
            ['STEM',  'Melchora Aquino', 'AM', 40, 24],
            ['ABM',   'Gabriela Silang', 'AM', 40, 10],
            ['HUMSS', 'Gabriela Silang', 'AM', 40, 35],
            ['GAS',   'Gabriela Silang', 'AM', 40, 5],
            ['TVL',   'Macario Sakay',   'PM', 40, 18],
        ];

        $seq = 0;

        foreach ($scenarios as [$strandCode, $name, $time, $capacity, $fill]) {
            $sid = $strandId($strandCode);

            $sectionId = DB::table('sections')
                ->where('strand_id', $sid)->where('grade_level', '12')
                ->where('section_name', $name)->where('semester', '1st')->value('id');

            if (! $sectionId) {
                $sectionId = DB::table('sections')->insertGetId([
                    'strand_id' => $sid, 'school_year_id' => $sy, 'grade_level' => '12',
                    'semester' => '1st', 'section_name' => $name, 'time_period' => $time,
                    'max_capacity' => $capacity, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            $fill = min($fill, $capacity);

            $subjectIds = array_values(array_unique(array_merge($core, $strandSubjects($strandCode))));
            Section::find($sectionId)->subjects()->sync($subjectIds);

            for ($i = 0; $i < $fill; $i++) {
                $seq++;
                $sn = '2025-'.str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
                $first = fake()->firstName();
                $last = fake()->lastName();

                $uid = DB::table('users')->insertGetId([
                    'name' => "$first $last", 'email' => "g12.$seq@student.edu.ph",
                    'email_verified_at' => now(), 'school_id' => $sn,
                    'password' => $password, 'must_change_password' => false,
                    'role' => 'student', 'created_at' => now(), 'updated_at' => now(),
                ]);

                $stuId = DB::table('students')->insertGetId([
                    'user_id' => $uid, 'student_number' => $sn,
                    'first_name' => $first, 'last_name' => $last,
                    'strand_id' => $sid, 'grade_level' => '12',
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                $eid = DB::table('enrollments')->insertGetId([
                    'student_id' => $stuId, 'section_id' => $sectionId, 'status' => 'approved',
                    'submitted_at' => now(), 'reviewed_at' => now(),
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                if ($subjectIds) {
                    DB::table('enrollment_subjects')->insert(array_map(fn ($subId) => [
                        'enrollment_id' => $eid, 'subject_id' => $subId, 'status' => 'enrolled',
                        'created_at' => now(), 'updated_at' => now(),
                    ], $subjectIds));
                }
            }
        }

        $testers = [
            ['2026-11900', 'STEM', '11'],
            ['2025-12900', 'STEM', '12'],
            ['2025-12901', 'ABM',  '12'],
        ];
        foreach ($testers as [$sn, $code, $grade]) {
            $sid = $strandId($code);
            $first = fake()->firstName();
            $last = fake()->lastName();

            $uid = DB::table('users')->insertGetId([
                'name' => "$first $last", 'email' => strtolower("tester.$sn@student.edu.ph"),
                'email_verified_at' => now(), 'school_id' => $sn,
                'password' => $password, 'must_change_password' => false,
                'role' => 'student', 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('students')->insert([
                'user_id' => $uid, 'student_number' => $sn,
                'first_name' => $first, 'last_name' => $last,
                'strand_id' => $sid, 'grade_level' => $grade,
                'created_at' => now(), 'updated_at' => now(),
            ]);

        }

        $generator = new ScheduleGenerator;
        Section::all()->each(fn (Section $section) => $generator->generate($section));
    }
}
