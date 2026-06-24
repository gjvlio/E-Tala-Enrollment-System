<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterRecord extends Model
{
    protected $fillable = [
        'student_id',
        'school_year_id',
        'semester',
        'gpa',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
