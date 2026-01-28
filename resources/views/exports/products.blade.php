<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->currency }} {{ $product->unit_price }}</td>
                <td>{{ $product->stock_quantity }} {{ $product->unit }}</td>
                <td>{{ $product->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
