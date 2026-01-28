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
            font-size: 10px;
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
    <h1>Payment History</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Account</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->payment_number }}</td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td>{{ $payment->account ? $payment->account->account_name : '-' }}</td>
                <td>{{ $payment->invoice ? $payment->invoice->invoice_number : '-' }}</td>
                <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
