<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Lead extends Model
{
    protected $fillable = [
        'ai_score',
        'ai_insights',
        'ai_recommendations',
        'sentiment',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'lead_source',
        'industry',
        'lead_score',
        'status',
        'notes',
        'assigned_to',
        'assignment_action',
        'assignment_deadline',
        'created_by',
        'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'converted_at' => 'datetime',
            'assignment_deadline' => 'datetime',
            'lead_score' => 'integer',
            'ai_score' => 'integer',
            'ai_recommendations' => 'array',
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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function communications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Communication::class, 'assigned_lead_id');
    }
}
