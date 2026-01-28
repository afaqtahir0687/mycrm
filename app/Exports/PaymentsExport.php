<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Payment::with(['invoice', 'account', 'contact'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Payment Number',
            'Date',
            'Invoice Number',
            'Account',
            'Contact',
            'Amount',
            'Currency',
            'Payment Method',
            'Reference',
            'Status',
            'Notes',
            'Created At',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->payment_number,
            $payment->payment_date->format('Y-m-d'),
            $payment->invoice ? $payment->invoice->invoice_number : '',
            $payment->account ? $payment->account->account_name : '',
            $payment->contact ? $payment->contact->first_name . ' ' . $payment->contact->last_name : '',
            $payment->amount,
            $payment->currency,
            $payment->payment_method,
            $payment->reference_number,
            $payment->status,
            $payment->notes,
            $payment->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
