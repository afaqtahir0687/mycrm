<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    protected $fillable = [
        'agreement_number',
        'agreement_type',
        'quotation_id',
        'deal_id',
        'account_id',
        'contact_id',
        'agreement_date',
        'start_date',
        'end_date',
        'status',
        'terms_conditions',
        'sla_terms',
        'deliverables',
        'total_value',
        'currency',
        'agreement_file_path',
        'signed_file_path',
        'signed_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'agreement_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'signed_date' => 'date',
            'total_value' => 'decimal:2',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
