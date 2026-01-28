<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
        'type',
    ];

    public function leads(): MorphToMany
    {
        return $this->morphedByMany(Lead::class, 'taggable');
    }

    public function accounts(): MorphToMany
    {
        return $this->morphedByMany(Account::class, 'taggable');
    }

    public function contacts(): MorphToMany
    {
        return $this->morphedByMany(Contact::class, 'taggable');
    }

    public function deals(): MorphToMany
    {
        return $this->morphedByMany(Deal::class, 'taggable');
    }
}
