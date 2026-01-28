<?php

namespace App\Imports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PaymentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Payment([
            'payment_number' => $row['payment_number'],
            'payment_date'   => Carbon::parse($row['payment_date']),
            'amount'         => $row['amount'],
            'currency'       => $row['currency'],
            'payment_method' => $row['payment_method'],
            'reference_number' => $row['reference_number'],
            'status'         => $row['status'],
            'notes'          => $row['notes'],
            'created_by'     => auth()->id(),
        ]);
    }
}
