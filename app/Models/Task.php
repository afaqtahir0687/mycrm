<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'priority',
        'status',
        'due_date',
        'due_time',
        'completed_at',
        'assigned_to',
        'created_by',
        'related_type',
        'related_id',
        'support_ticket_id',
        'is_reminder',
        'reminder_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'due_time' => 'datetime',
            'completed_at' => 'datetime',
            'reminder_at' => 'datetime',
            'is_reminder' => 'boolean',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
