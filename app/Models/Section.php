<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'strand_id',
        'school_year_id',
        'grade_level',
        'semester',
        'section_name',
        'time_period',
        'max_capacity',
    ];

    public function strand()
    {
        return $this->belongsTo(Strand::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subjects')
            ->withPivot('day_of_week', 'start_time', 'end_time', 'room')
            ->withTimestamps();
    }

    public function hasSchedule(): bool
    {
        return $this->subjects()->whereNotNull('section_subjects.start_time')->exists();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function approvedCount(): int
    {
        return $this->approved_count ?? $this->enrollments()->where('status', 'approved')->count();
    }

    public function remainingSlots(): int
    {
        return max(0, $this->max_capacity - $this->approvedCount());
    }

    public function isFull(): bool
    {
        return $this->remainingSlots() <= 0;
    }

    public function isNearlyFull(): bool
    {
        return ! $this->isFull() && $this->remainingSlots() <= 5;
    }

    public function displayName(): string
    {
        return "Grade {$this->grade_level} - {$this->section_name} ({$this->time_period})";
    }
}
