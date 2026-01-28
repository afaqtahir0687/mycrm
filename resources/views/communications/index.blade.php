@extends('layouts.app')

@section('title', 'Communications Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Communications Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'communications.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('communications.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'communications']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'communications']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Email:</strong> {{ $summary['email'] }}</div>
            <div><strong>Phone:</strong> {{ $summary['phone'] }}</div>
            <div><strong>SMS:</strong> {{ $summary['sms'] }}</div>
            <div><strong>WhatsApp:</strong> {{ $summary['whatsapp'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('communications.create') }}" class="btn btn-success" style="text-align: left;">Log New Communication</a>
                <a href="{{ route('leads.index') }}" class="btn btn-primary" style="text-align: left;">View Leads</a>
                <a href="{{ route('contacts.index') }}" class="btn btn-primary" style="text-align: left;">View Contacts</a>
                <a href="{{ route('accounts.index') }}" class="btn btn-primary" style="text-align: left;">View Accounts</a>
                <a href="{{ route('support-tickets.index') }}" class="btn btn-primary" style="text-align: left;">View Support Tickets</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Communications Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Communications track emails, calls, messages, and other interactions with your customers.
                Use this screen to review all inbound and outbound activity and jump directly to related Leads,
                Contacts, Accounts, and Support Tickets for full context around every conversation.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('communications.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Subject, Content" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Type:</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="phone" {{ request('type') == 'phone' ? 'selected' : '' }}>Phone</option>
                        <option value="sms" {{ request('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="whatsapp" {{ request('type') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="note" {{ request('type') == 'note' ? 'selected' : '' }}>Note</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Direction:</label>
                    <select name="direction" class="form-control">
                        <option value="">All Directions</option>
                        <option value="inbound" {{ request('direction') == 'inbound' ? 'selected' : '' }}>Inbound</option>
                        <option value="outbound" {{ request('direction') == 'outbound' ? 'selected' : '' }}>Outbound</option>
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
                    <label>Contact:</label>
                    <select name="contact_id" class="form-control">
                        <option value="">All Contacts</option>
                        @foreach($filterOptions['contacts'] as $contact)
                        <option value="{{ $contact->id }}" {{ request('contact_id') == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Lead:</label>
                    <select name="lead_id" class="form-control">
                        <option value="">All Leads</option>
                        @foreach($filterOptions['leads'] as $lead)
                        <option value="{{ $lead->id }}" {{ request('lead_id') == $lead->id ? 'selected' : '' }}>{{ $lead->first_name }} {{ $lead->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Type</option>
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
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Direction</th>
                    <th>Account</th>
                    <th>Contact</th>
                    <th>Lead</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($communications as $communication)
                <tr>
                    <td>{{ $communication->id }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #2196f3; color: white;">
                            {{ ucfirst($communication->type) }}
                        </span>
                    </td>
                    <td>{{ $communication->subject ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            {{ $communication->direction == 'inbound' ? '#4caf50' : '#2196f3' }}; color: white;">
                            {{ ucfirst($communication->direction) }}
                        </span>
                    </td>
                    <td>{{ $communication->account->account_name ?? 'N/A' }}</td>
                    <td>{{ $communication->contact->first_name ?? 'N/A' }} {{ $communication->contact->last_name ?? '' }}</td>
                    <td>{{ $communication->lead->first_name ?? 'N/A' }} {{ $communication->lead->last_name ?? '' }}</td>
                    <td>{{ $communication->status ? ucfirst($communication->status) : 'N/A' }}</td>
                    <td>{{ $communication->creator->name ?? 'System' }}</td>
                    <td>{{ $communication->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('communications.show', $communication) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('communications.edit', $communication) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('communications.destroy', $communication) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">No communications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $communications->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('communications.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'communications']) }}', {
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
