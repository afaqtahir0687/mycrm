<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'activity_type',
        'title',
        'description',
        'subject_type',
        'subject_id',
        'user_id',
        'activity_date',
        'duration_minutes',
        'location',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'datetime',
            'duration_minutes' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
