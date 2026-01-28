@extends('layouts.app')

@section('title', 'Accounts Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Accounts Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'accounts.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'accounts']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'accounts']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Accounts:</strong> {{ $summary['total'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
            <div><strong>Inactive:</strong> {{ $summary['inactive'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('accounts.create') }}" class="btn btn-success" style="text-align: left;">Create New Account</a>
                <a href="{{ route('contacts.index') }}" class="btn btn-primary" style="text-align: left;">View Contacts</a>
                <a href="{{ route('leads.index') }}" class="btn btn-primary" style="text-align: left;">View Leads</a>
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">View Deals</a>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="text-align: left;">View Opportunities</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Accounts Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Accounts represent organisations or companies that you do business with.
                Use this screen to manage company profiles, segment accounts by industry and status,
                and quickly navigate to related Contacts, Leads, Deals, and Opportunities for a complete
                360° customer view.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('accounts.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Account Name, Email" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Industry:</label>
                    <select name="industry" class="form-control">
                        <option value="">All Industries</option>
                        @foreach($filterOptions['industries'] as $industry)
                        <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Account Type:</label>
                    <select name="account_type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($filterOptions['account_types'] as $type)
                        <option value="{{ $type }}" {{ request('account_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
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
                        <option value="account_name" {{ request('sort_by') == 'account_name' ? 'selected' : '' }}>Account Name</option>
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
                    <th>Account Name</th>
                    <th>Type</th>
                    <th>Industry</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->id }}</td>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ $account->account_type }}</td>
                    <td>{{ $account->industry }}</td>
                    <td>{{ $account->email }}</td>
                    <td>{{ $account->phone }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($account->status == 'active') #4caf50
                            @elseif($account->status == 'inactive') #757575
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($account->status) }}
                        </span>
                    </td>
                    <td>{{ $account->owner->name ?? 'Unassigned' }}</td>
                    <td>{{ $account->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('accounts.show', $account) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No accounts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $accounts->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('accounts.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'accounts']) }}', {
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
