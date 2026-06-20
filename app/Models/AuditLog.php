<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Convenience writer for registrar actions. */
    public static function record(string $action, ?string $modelType = null, ?int $modelId = null, ?string $description = null): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
        ]);
    }
}
