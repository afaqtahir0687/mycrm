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
    <h1>Expense Report</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Date</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->expense_number }}</td>
                <td>{{ $expense->expense_name }}</td>
                <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                <td>{{ $expense->category }}</td>
                <td>{{ $expense->vendor ? $expense->vendor->account_name : '-' }}</td>
                <td>{{ $expense->currency }} {{ number_format($expense->amount, 2) }}</td>
                <td>{{ ucfirst($expense->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
