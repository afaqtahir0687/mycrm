<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'product_code' => $row['product_code'],
            'product_name' => $row['product_name'],
            'category'     => $row['category'],
            'unit_price'   => $row['unit_price'],
            'currency'     => $row['currency'],
            'stock_quantity' => $row['stock_quantity'],
            'unit'         => $row['unit'],
            'status'       => $row['status'],
            'description'  => $row['description'],
            'created_by'   => auth()->id(),
        ]);
    }
}
