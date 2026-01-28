@extends('layouts.app')

@section('title', 'Contacts Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Contacts Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'contacts.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'contacts']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'contacts']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Contacts:</strong> {{ $summary['total'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
            <div><strong>Inactive:</strong> {{ $summary['inactive'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('contacts.create') }}" class="btn btn-success" style="text-align: left;">Create New Contact</a>
                <a href="{{ route('accounts.index') }}" class="btn btn-primary" style="text-align: left;">View Accounts</a>
                <a href="{{ route('leads.index') }}" class="btn btn-primary" style="text-align: left;">View Leads</a>
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">View Deals</a>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="text-align: left;">View Opportunities</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Contacts Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Contacts represent individual people associated with your Accounts and Leads.
                Use this screen to maintain up-to-date contact details, track their relationship to Accounts and Deals,
                and quickly move between Contacts, Accounts, Leads, and Opportunities while working on customer engagements.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('contacts.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Phone" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <label>Assigned To:</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">All Users</option>
                        @foreach($filterOptions['users'] as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                        <option value="last_name" {{ request('sort_by') == 'last_name' ? 'selected' : '' }}>Last Name</option>
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
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->title }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->account->account_name ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $contact->status == 'active' ? '#4caf50' : '#757575' }}; color: white;">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </td>
                    <td>{{ $contact->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>{{ $contact->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('contacts.show', $contact) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">No contacts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $contacts->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('contacts.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'contacts']) }}', {
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
