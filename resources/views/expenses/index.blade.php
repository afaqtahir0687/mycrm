@extends('layouts.app')

@section('title', 'Expenses Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Expenses Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'expenses.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">Record Expense</a>
            <a href="{{ route('expenses.export_excel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('expenses.export_pdf') }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div>
                <strong>Total Expenses:</strong> {{ $summary['total'] }}
            </div>
            <div>
                <strong>Paid:</strong> {{ $summary['paid'] }}
            </div>
            <div>
                <strong>Pending:</strong> {{ $summary['pending'] }}
            </div>
            <div>
                <strong>Total Amount:</strong> {{ number_format($summary['total_amount'], 2) }}
            </div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('expenses.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Expense #, Name" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Vendor:</label>
                    <select name="vendor_id" class="form-control">
                        <option value="">All Vendors</option>
                        @foreach($filterOptions['vendors'] as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->account_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Category:</label>
                    <input type="text" name="category" value="{{ request('category') }}" placeholder="Category" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="expense_date" {{ request('sort_by') == 'expense_date' ? 'selected' : '' }}>Expense Date</option>
                        <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Amount</option>
                        <option value="expense_number" {{ request('sort_by') == 'expense_number' ? 'selected' : '' }}>Expense #</option>
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
                    <th>Expense #</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Vendor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_number }}</td>
                    <td>{{ $expense->expense_name }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                    <td>{{ $expense->currency }} {{ number_format($expense->amount, 2) }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($expense->status == 'paid') #4caf50
                            @elseif($expense->status == 'approved') #2196f3
                            @elseif($expense->status == 'pending') #ff9800
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                    <td>{{ $expense->vendor->account_name ?? '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No expenses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $expenses->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('expenses.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('expenses.import_excel') }}', {
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
