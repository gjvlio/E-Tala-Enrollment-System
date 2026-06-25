<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $strandId = fn (string $code) => DB::table('strands')->where('strand_code', $code)->value('id');
        $sy       = DB::table('school_years')->where('year_label', '2026-2027')->value('id');

        // Section names per grade level (fixed by school):
        // Grade 11 AM: Kasipagan, Kapanatagan | PM: Katapangan, Kabutihan
        // Grade 12 AM: Gabriela Silang, Melchora Aquino | PM: Macario Sakay, Vicente Lim
        $g11Names = [
            ['Kasipagan',   'AM'],
            ['Kapanatagan', 'AM'],
            ['Katapangan',  'PM'],
            ['Kabutihan',   'PM'],
        ];

        $sections = [];

        // Grade 11 sections for every strand — so no strand gets "0 / 0" slots
        // and is forced into the waitlist at qualify time.
        foreach (['STEM', 'ABM', 'HUMSS', 'GAS', 'TVL'] as $code) {
            foreach ($g11Names as [$name, $time]) {
                $sections[] = [$strandId($code), $sy, '11', '1st', $name, $time, 40];
            }
        }

        // STEM Grade 12 (other strands' G12 sections come from Grade12ScenarioSeeder).
        $stem = $strandId('STEM');
        $sections[] = [$stem, $sy, '12', '1st', 'Gabriela Silang', 'AM', 40];
        $sections[] = [$stem, $sy, '12', '1st', 'Melchora Aquino', 'AM', 40];
        $sections[] = [$stem, $sy, '12', '1st', 'Macario Sakay',   'PM', 40];
        $sections[] = [$stem, $sy, '12', '1st', 'Vicente Lim',     'PM', 40];

        foreach ($sections as [$strandId, $syId, $grade, $sem, $name, $time, $cap]) {
            DB::table('sections')->insert([
                'strand_id'      => $strandId,
                'school_year_id' => $syId,
                'grade_level'    => $grade,
                'semester'       => $sem,
                'section_name'   => $name,
                'time_period'    => $time,
                'max_capacity'   => $cap,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
