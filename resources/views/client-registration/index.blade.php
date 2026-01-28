@extends('layouts.app')

@section('title', 'Client Registration')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Client Registration</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'client-registration.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('client-registration.create') }}" class="btn btn-primary">Add New Client</a>
            <a href="{{ route('export.excel', ['resource' => 'client-registration']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'client-registration']) }}" class="btn btn-success">Export PDF</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Clients:</strong> {{ $summary['total'] }}</div>
            <div><strong>Contacts:</strong> {{ $summary['contacts'] }}</div>
            <div><strong>Accounts:</strong> {{ $summary['accounts'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('client-registration.create') }}" class="btn btn-success" style="text-align: left;">Register New Client</a>
                <a href="{{ route('quotations.index') }}" class="btn btn-primary" style="text-align: left;">Create Quotation</a>
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">View Deals</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Client Registration Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Register new clients as either individual contacts or company accounts. 
                Contacts represent individual people, while Accounts represent companies or organizations.
                Both types are displayed in this unified view for easy management.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('client-registration.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Phone" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Client Type:</label>
                    <select name="client_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="contact" {{ request('client_type') == 'contact' ? 'selected' : '' }}>Contact (Individual)</option>
                        <option value="account" {{ request('client_type') == 'account' ? 'selected' : '' }}>Account (Company)</option>
                    </select>
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
                    <th>Type</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: {{ $client->type == 'account' ? '#0078d4' : '#107c10' }}; color: white;">
                            {{ ucfirst($client->type) }}
                        </span>
                    </td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email ?? '-' }}</td>
                    <td>{{ $client->phone ?? '-' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($client->status == 'active') #107c10
                            @elseif($client->status == 'inactive') #757575
                            @else #d13438
                            @endif; color: white;">
                            {{ ucfirst($client->status) }}
                        </span>
                    </td>
                    <td>{{ $client->assigned_to }}</td>
                    <td>{{ $client->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            @if($client->type == 'contact')
                                <a href="{{ route('contacts.show', $client->model) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                                <a href="{{ route('contacts.edit', $client->model) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            @else
                                <a href="{{ route('accounts.show', $client->model) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                                <a href="{{ route('accounts.edit', $client->model) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No clients found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $clients->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('client-registration.index') }}';
}
</script>
@endsection
