@extends('layouts.app')

@section('title', 'Quotations Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Quotations Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'quotations.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('quotations.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'quotations']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'quotations']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Draft:</strong> {{ $summary['draft'] }}</div>
            <div><strong>Sent:</strong> {{ $summary['sent'] }}</div>
            <div><strong>Accepted:</strong> {{ $summary['accepted'] }}</div>
            <div><strong>Total Value:</strong> ${{ number_format($summary['total_value'], 2) }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('quotations.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Quotation Number, Account" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
                        <option value="quotation_number" {{ request('sort_by') == 'quotation_number' ? 'selected' : '' }}>Quotation Number</option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Total Amount</option>
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
                    <th>Quotation Number</th>
                    <th>Account</th>
                    <th>Contact</th>
                    <th>Quotation Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $quotation)
                <tr>
                    <td>{{ $quotation->id }}</td>
                    <td>{{ $quotation->quotation_number }}</td>
                    <td>{{ $quotation->account->account_name ?? 'N/A' }}</td>
                    <td>{{ $quotation->contact->first_name ?? 'N/A' }} {{ $quotation->contact->last_name ?? '' }}</td>
                    <td>{{ $quotation->quotation_date->format('Y-m-d') }}</td>
                    <td>${{ number_format($quotation->total_amount, 2) }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($quotation->status == 'draft') #757575
                            @elseif($quotation->status == 'sent') #2196f3
                            @elseif($quotation->status == 'accepted') #4caf50
                            @elseif($quotation->status == 'rejected') #f44336
                            @else #ff9800
                            @endif; color: white;">
                            {{ ucfirst($quotation->status) }}
                        </span>
                    </td>
                    <td>{{ $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $quotation->creator->name ?? 'System' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('quotations.print', $quotation) }}" target="_blank" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Print</a>
                            <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No quotations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $quotations->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('quotations.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'quotations']) }}', {
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
