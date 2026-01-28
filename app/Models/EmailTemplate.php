<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'body',
        'type',
        'category',
        'file_path',
        'is_active',
        'is_official',
        'user_id',
        'variables',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'variables' => 'array',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
