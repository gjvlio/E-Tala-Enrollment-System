<?php

namespace App\Services;

use App\Models\Section;
use Carbon\Carbon;

class ScheduleGenerator
{
    private const DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

    public function generate(Section $section): int
    {
        $subjects = $section->subjects()->orderBy('subjects.subject_code')->get();

        if ($subjects->isEmpty()) {
            return 0;
        }

        $slots = $this->slotsFor($section->time_period);
        $room = 'Room '.$section->id;

        $assignments = [];
        foreach ($slots as $slot) {
            foreach (self::DAYS as $day) {
                $assignments[] = ['day' => $day, 'start' => $slot[0], 'end' => $slot[1]];
            }
        }

        $count = 0;

        foreach ($subjects->values() as $i => $subject) {
            if (! isset($assignments[$i])) {
                break;
            }

            $a = $assignments[$i];
            $section->subjects()->updateExistingPivot($subject->id, [
                'day_of_week' => $a['day'],
                'start_time' => $a['start'],
                'end_time' => $a['end'],
                'room' => $room,
            ]);

            $count++;
        }

        return $count;
    }

    private function slotsFor(string $timePeriod): array
    {
        $starts = $timePeriod === 'PM'
            ? ['13:00', '14:00', '15:00', '16:00']
            : ['07:30', '08:30', '09:30', '10:30'];

        return array_map(fn ($s) => [
            Carbon::parse($s)->format('H:i:s'),
            Carbon::parse($s)->addMinutes(60)->format('H:i:s'),
        ], $starts);
    }
}
