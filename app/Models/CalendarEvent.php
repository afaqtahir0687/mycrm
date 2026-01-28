<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'event_type',
        'related_type',
        'related_id',
        'user_id',
        'attendees',
        'location',
        'is_all_day',
        'recurrence_pattern',
        'send_reminder',
        'reminder_minutes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'attendees' => 'array',
            'is_all_day' => 'boolean',
            'send_reminder' => 'boolean',
            'reminder_minutes' => 'integer',
        ];
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
