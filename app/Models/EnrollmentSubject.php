<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSubject extends Model
{
    protected $fillable = [
        'enrollment_id',
        'subject_id',
        'grade',
        'status',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
