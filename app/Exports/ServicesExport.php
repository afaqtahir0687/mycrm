<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServicesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Service::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Service Code',
            'Service Name',
            'Category',
            'Pricing Type',
            'Hourly Rate',
            'Fixed Price',
            'Currency',
            'Estimated Hours',
            'Status',
            'Description',
            'Created At',
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->service_code,
            $service->service_name,
            $service->category,
            $service->pricing_type,
            $service->hourly_rate,
            $service->fixed_price,
            $service->currency,
            $service->estimated_hours,
            $service->status,
            $service->description,
            $service->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
