<?php

namespace App\Imports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServicesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Service([
            'service_code'    => $row['service_code'],
            'service_name'    => $row['service_name'],
            'category'        => $row['category'],
            'pricing_type'    => $row['pricing_type'],
            'hourly_rate'     => $row['hourly_rate'] ?? null,
            'fixed_price'     => $row['fixed_price'] ?? null,
            'currency'        => $row['currency'],
            'estimated_hours' => $row['estimated_hours'],
            'status'          => $row['status'],
            'description'     => $row['description'],
            'created_by'      => auth()->id(),
        ]);
    }
}
