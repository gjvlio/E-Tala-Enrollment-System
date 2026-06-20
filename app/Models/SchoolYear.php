<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'year_label',
        'is_active',
        'active_semester',
        'is_enrollment_open',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_enrollment_open' => 'boolean',
        ];
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /** The single active school year, if any. */
    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
