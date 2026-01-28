@extends('layouts.app')

@section('title', 'Invoices Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Invoices Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'invoices.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('invoices.export_excel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('invoices.export_pdf') }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Draft:</strong> {{ $summary['draft'] }}</div>
            <div><strong>Sent:</strong> {{ $summary['sent'] }}</div>
            <div><strong>Paid:</strong> {{ $summary['paid'] }}</div>
            <div><strong>Overdue:</strong> {{ $summary['overdue'] }}</div>
            <div><strong>Total Amount:</strong> ${{ number_format($summary['total_amount'], 2) }}</div>
            <div><strong>Outstanding:</strong> ${{ number_format($summary['outstanding'], 2) }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('invoices.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice Number, Account" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                    <label>Created By:</label>
                    <select name="created_by" class="form-control">
                        <option value="">All Users</option>
                        @foreach($filterOptions['users'] as $user)
                        <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="invoice_number" {{ request('sort_by') == 'invoice_number' ? 'selected' : '' }}>Invoice Number</option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Total Amount</option>
                        <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Due Date</option>
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
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Account</th>
                    <th>Contact</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Total Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->account->account_name ?? 'N/A' }}</td>
                    <td>{{ $invoice->contact->first_name ?? 'N/A' }} {{ $invoice->contact->last_name ?? '' }}</td>
                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td>{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                    <td>${{ number_format($invoice->balance, 2) }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($invoice->status == 'draft') #757575
                            @elseif($invoice->status == 'sent') #2196f3
                            @elseif($invoice->status == 'paid') #4caf50
                            @elseif($invoice->status == 'partial') #ff9800
                            @elseif($invoice->status == 'overdue') #f44336
                            @else #9e9e9e
                            @endif; color: white;">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>{{ $invoice->creator->name ?? 'System' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $invoices->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('invoices.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('invoices.import_excel') }}', {
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
