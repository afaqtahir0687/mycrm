<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Expense::with(['vendor', 'payment'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Expense Number',
            'Name',
            'Category',
            'Date',
            'Vendor',
            'Amount',
            'Currency',
            'Payment Method',
            'Status',
            'Related Payment',
            'Notes',
            'Created At',
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->expense_number,
            $expense->expense_name,
            $expense->category,
            $expense->expense_date->format('Y-m-d'),
            $expense->vendor ? $expense->vendor->account_name : '',
            $expense->amount,
            $expense->currency,
            $expense->payment_method,
            $expense->status,
            $expense->payment ? $expense->payment->payment_number : '',
            $expense->notes,
            $expense->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
