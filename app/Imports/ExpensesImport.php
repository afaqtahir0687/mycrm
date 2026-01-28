<?php

namespace App\Imports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ExpensesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Expense([
            'expense_number' => $row['expense_number'],
            'expense_name'   => $row['expense_name'],
            'category'       => $row['category'],
            'expense_date'   => Carbon::parse($row['expense_date']),
            'amount'         => $row['amount'],
            'currency'       => $row['currency'],
            'payment_method' => $row['payment_method'],
            'status'         => $row['status'],
            'notes'          => $row['notes'],
            'created_by'     => auth()->id(),
        ]);
    }
}
