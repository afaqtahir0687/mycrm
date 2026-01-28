<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationWorkflow extends Model
{
    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_conditions',
        'actions',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'trigger_conditions' => 'array',
            'actions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
