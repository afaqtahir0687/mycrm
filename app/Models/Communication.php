<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Communication extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'content',
        'engagement_outcome',
        'engagement_status',
        'engagement_date',
        'communication_category',
        'visit_report',
        'attachment_path',
        'direction',
        'from_email',
        'to_email',
        'from_phone',
        'to_phone',
        'account_id',
        'contact_id',
        'lead_id',
        'assigned_lead_id',
        'template_id',
        'created_by',
        'duration_minutes',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'engagement_date' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedLead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'assigned_lead_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
