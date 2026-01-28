@extends('layouts.app')

@section('title', 'Payments Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Payments Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'payments.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">Record Payment</a>
            <a href="{{ route('payments.export_excel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('payments.export_pdf') }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div>
                <strong>Total Payments:</strong> {{ $summary['total'] }}
            </div>
            <div>
                <strong>Received:</strong> {{ $summary['received'] }}
            </div>
            <div>
                <strong>Total Amount:</strong> {{ number_format($summary['total_amount'], 2) }}
            </div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('payments.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Payment #, Reference" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Account:</label>
                    <select name="account_id" class="form-control">
                        <option value="">All Accounts</option>
                        @foreach($filterOptions['accounts'] as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Type:</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="received" {{ request('type') == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="made" {{ request('type') == 'made' ? 'selected' : '' }}>Made</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="payment_date" {{ request('sort_by') == 'payment_date' ? 'selected' : '' }}>Payment Date</option>
                        <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Amount</option>
                        <option value="payment_number" {{ request('sort_by') == 'payment_number' ? 'selected' : '' }}>Payment #</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort Order:</label>
                    <select name="sort_order" class="form-control">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Payment #</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_number }}</td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td>{{ $payment->reference_number ?? '-' }}</td>
                    <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_method ?? '-' }}</td>
                    <td>
                        @if($payment->account_id)
                            <span style="color: #4caf50;">Received</span>
                        @else
                            <span style="color: #ff9800;">Other</span>
                        @endif
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($payment->status == 'received') #4caf50
                            @elseif($payment->status == 'pending') #ff9800
                            @elseif($payment->status == 'failed') #f44336
                            @else #757575
                            @endif; color: white;">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No payments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $payments->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('payments.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('payments.import_excel') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Import completed');
            location.reload();
        })
        .catch(error => {
            alert('Error importing file');
        });
    }
}
</script>
@endsection
