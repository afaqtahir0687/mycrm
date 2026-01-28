@extends('layouts.app')

@section('title', 'Deals Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Deals Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'deals.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('deals.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'deals']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'deals']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Deals:</strong> {{ $summary['total'] }}</div>
            <div><strong>Open:</strong> {{ $summary['open'] }}</div>
            <div><strong>Won:</strong> {{ $summary['won'] }}</div>
            <div><strong>Lost:</strong> {{ $summary['lost'] }}</div>
            <div><strong>Total Value:</strong> ${{ number_format($summary['total_value'], 2) }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('deals.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Deal Name" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Won</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Stage:</label>
                    <select name="stage" class="form-control">
                        <option value="">All Stages</option>
                        <option value="prospecting" {{ request('stage') == 'prospecting' ? 'selected' : '' }}>Prospecting</option>
                        <option value="qualification" {{ request('stage') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                        <option value="proposal" {{ request('stage') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negotiation" {{ request('stage') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="closed_won" {{ request('stage') == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                        <option value="closed_lost" {{ request('stage') == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
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
                    <label>Owner:</label>
                    <select name="owner_id" class="form-control">
                        <option value="">All Owners</option>
                        @foreach($filterOptions['users'] as $user)
                        <option value="{{ $user->id }}" {{ request('owner_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="deal_name" {{ request('sort_by') == 'deal_name' ? 'selected' : '' }}>Deal Name</option>
                        <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Amount</option>
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
                    <th>Deal Name</th>
                    <th>Account</th>
                    <th>Contact</th>
                    <th>Amount</th>
                    <th>Stage</th>
                    <th>Probability</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th>Expected Close</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                <tr>
                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->deal_name }}</td>
                    <td>{{ $deal->account->account_name ?? 'N/A' }}</td>
                    <td>{{ $deal->contact->first_name ?? 'N/A' }} {{ $deal->contact->last_name ?? '' }}</td>
                    <td>${{ number_format($deal->amount ?? 0, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $deal->stage)) }}</td>
                    <td>{{ $deal->probability }}%</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($deal->status == 'open') #2196f3
                            @elseif($deal->status == 'won') #4caf50
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($deal->status) }}
                        </span>
                    </td>
                    <td>{{ $deal->owner->name ?? 'Unassigned' }}</td>
                    <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('deals.show', $deal) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('deals.edit', $deal) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">No deals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $deals->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('deals.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'deals']) }}', {
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
