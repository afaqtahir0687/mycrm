<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::with(['account', 'contact', 'creator'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Invoice Number',
            'Account',
            'Contact',
            'Invoice Date',
            'Due Date',
            'Total Amount',
            'Balance',
            'Status',
            'Created By',
            'Notes',
            'Created At',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->invoice_number,
            $invoice->account->account_name ?? '',
            ($invoice->contact->first_name ?? '') . ' ' . ($invoice->contact->last_name ?? ''),
            $invoice->invoice_date->format('Y-m-d'),
            $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '',
            $invoice->total_amount,
            $invoice->balance,
            $invoice->status,
            $invoice->creator->name ?? '',
            $invoice->notes,
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
