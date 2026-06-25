<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\SemesterRecord;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $testers = ['2026-11900', '2025-12900', '2025-12901'];
        $pastSy = SchoolYear::where('year_label', '2025-2026')->first();
        if (! $pastSy) {
            return;
        }

        $core = Subject::where('subject_code', 'like', 'CORE-%')->pluck('id')->all();
        $strandSubs = fn (?string $code) => $code
            ? Subject::where('subject_code', 'like', $code.'-%')->pluck('id')->all()
            : [];

        DB::transaction(function () use ($testers, $pastSy, $core, $strandSubs) {
            $g12 = Student::with('strand')
                ->where('grade_level', '12')
                ->whereNotIn('student_number', $testers)
                ->get();

            $sectionCache = [];
            $placed = [];
            $capacity = 50;

            $sectionFor = function (Student $student, string $sem) use (&$sectionCache, &$placed, $capacity, $pastSy, $core, $strandSubs) {
                $group = $student->strand_id.'-'.$sem;
                $index = intdiv($placed[$group] ?? 0, $capacity);
                $key = $group.'-'.$index;

                if (! isset($sectionCache[$key])) {
                    $base = $sem === '1st' ? 'Kasipagan' : 'Katapangan';
                    $name = $index === 0 ? $base : $base.' '.($index + 1);

                    $section = Section::firstOrCreate(
                        [
                            'strand_id' => $student->strand_id,
                            'school_year_id' => $pastSy->id,
                            'grade_level' => '11',
                            'semester' => $sem,
                            'section_name' => $name,
                        ],
                        ['time_period' => 'AM', 'max_capacity' => $capacity],
                    );

                    $subjects = array_values(array_unique(array_merge($core, $strandSubs(optional($student->strand)->strand_code))));
                    $section->subjects()->syncWithoutDetaching($subjects);

                    $sectionCache[$key] = ['section' => $section, 'subjects' => $subjects];
                }

                $placed[$group] = ($placed[$group] ?? 0) + 1;

                return $sectionCache[$key];
            };

            foreach ($g12 as $student) {
                foreach (['1st', '2nd'] as $sem) {
                    ['section' => $section, 'subjects' => $subjects] = $sectionFor($student, $sem);

                    $enrollment = Enrollment::create([
                        'student_id' => $student->id,
                        'section_id' => $section->id,
                        'status' => 'approved',
                        'submitted_at' => now(),
                        'reviewed_at' => now(),
                    ]);

                    $rows = [];
                    $grades = [];
                    foreach ($subjects as $subjectId) {
                        $grade = $this->randomGrade();
                        $grades[] = $grade;
                        $rows[] = [
                            'enrollment_id' => $enrollment->id,
                            'subject_id' => $subjectId,
                            'grade' => $grade,
                            'status' => $grade >= 75 ? 'passed' : 'failed',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('enrollment_subjects')->insert($rows);

                    SemesterRecord::updateOrCreate(
                        ['student_id' => $student->id, 'school_year_id' => $pastSy->id, 'semester' => $sem],
                        ['gpa' => round(array_sum($grades) / count($grades), 2), 'is_locked' => true],
                    );
                }
            }
        });
    }

    private function randomGrade(): int
    {
        return mt_rand(1, 6) === 1 ? mt_rand(72, 84) : mt_rand(85, 97);
    }
}
