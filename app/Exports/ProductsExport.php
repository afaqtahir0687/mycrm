<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Code',
            'Product Name',
            'Category',
            'Unit Price',
            'Currency',
            'Stock Quantity',
            'Unit',
            'Status',
            'Description',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->product_code,
            $product->product_name,
            $product->category,
            $product->unit_price,
            $product->currency,
            $product->stock_quantity,
            $product->unit,
            $product->status,
            $product->description,
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
