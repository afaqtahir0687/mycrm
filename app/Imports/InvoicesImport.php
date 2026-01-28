<?php

namespace App\Imports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class InvoicesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Invoice([
            'invoice_number'   => $row['invoice_number'],
            // Assuming account/contact IDs or names are handled. For simplicity, we might need to lookup IDs if names are provided, 
            // but for a basic import we'll assume the user provides data that matches DB requirements or we map purely scalar fields.
            // A robust import would need to look up Account by name, etc. For now, let's map what we can safely.
            // If the user exports then imports, IDs might be present if included in the export, but standard practice often involves name lookups.
            // Given the limited context, I'll map basic fields and assume IDs if provided, or leave nullable fields null.
            // 'account_id' => $row['account_id'] ?? null, 
            'invoice_date'     => isset($row['invoice_date']) ? Carbon::parse($row['invoice_date']) : now(),
            'due_date'         => isset($row['due_date']) ? Carbon::parse($row['due_date']) : null,
            'total_amount'     => $row['total_amount'] ?? 0,
            'balance'          => $row['balance'] ?? 0,
            'status'           => $row['status'] ?? 'draft',
            'notes'            => $row['notes'] ?? null,
            'created_by'       => auth()->id(),
        ]);
    }
}
