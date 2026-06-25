<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolYearSeeder::class,
            StrandSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
            SectionSeeder::class,
            SectionSubjectSeeder::class,
            EnrollmentSeeder::class,
            Grade12ScenarioSeeder::class,
            GradeSeeder::class,
        ]);
    }
}
