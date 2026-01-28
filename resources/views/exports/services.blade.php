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
    <h1>Service List</h1>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Rate/Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->service_code }}</td>
                <td>{{ $service->service_name }}</td>
                <td>{{ $service->category }}</td>
                <td>{{ ucfirst($service->pricing_type) }}</td>
                <td>
                    @if($service->pricing_type == 'hourly')
                        {{ $service->currency }} {{ $service->hourly_rate }}/hr
                    @elseif($service->pricing_type == 'fixed')
                        {{ $service->currency }} {{ $service->fixed_price }}
                    @else
                        Custom
                    @endif
                </td>
                <td>{{ $service->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
