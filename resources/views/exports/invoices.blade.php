<!DOCTYPE html>
<html>
<head>
    <title>Invoices Export</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Invoices List</h1>
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Account</th>
                <th>Date</th>
                <th>Total</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->account->account_name ?? '-' }}</td>
                <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                <td>{{ number_format($invoice->balance, 2) }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
