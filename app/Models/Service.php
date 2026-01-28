<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'service_code',
        'service_name',
        'description',
        'category',
        'hourly_rate',
        'fixed_price',
        'pricing_type',
        'currency',
        'status',
        'service_details',
        'estimated_hours',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'fixed_price' => 'decimal:2',
            'estimated_hours' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
