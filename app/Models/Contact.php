<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Contact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'email',
        'phone',
        'mobile',
        'department',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'birthdate',
        'notes',
        'account_id',
        'assigned_to',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
