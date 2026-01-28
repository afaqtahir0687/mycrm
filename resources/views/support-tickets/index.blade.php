@extends('layouts.app')

@section('title', 'Support Tickets Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Support Tickets Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'support-tickets.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('support-tickets.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'support-tickets']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'support-tickets']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>New:</strong> {{ $summary['new'] }}</div>
            <div><strong>Open:</strong> {{ $summary['open'] }}</div>
            <div><strong>In Progress:</strong> {{ $summary['in_progress'] }}</div>
            <div><strong>Resolved:</strong> {{ $summary['resolved'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('support-tickets.create') }}" class="btn btn-success" style="text-align: left;">Create New Ticket</a>
                <a href="{{ route('accounts.index') }}" class="btn btn-primary" style="text-align: left;">View Accounts</a>
                <a href="{{ route('contacts.index') }}" class="btn btn-primary" style="text-align: left;">View Contacts</a>
                <a href="{{ route('tasks.index') }}" class="btn btn-primary" style="text-align: left;">View Tasks</a>
                <a href="{{ route('communications.index') }}" class="btn btn-primary" style="text-align: left;">View Communications</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Support Tickets Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Support Tickets capture customer issues, requests, and incidents.
                Use this screen to prioritise work by status and priority, and quickly navigate to related Accounts,
                Contacts, Tasks, and Communications to ensure timely and complete resolution for every ticket.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('support-tickets.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket Number, Subject" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Priority:</label>
                    <select name="priority" class="form-control">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="ticket_number" {{ request('sort_by') == 'ticket_number' ? 'selected' : '' }}>Ticket Number</option>
                        <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
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
                    <th>Ticket Number</th>
                    <th>Subject</th>
                    <th>Account</th>
                    <th>Contact</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Assigned To</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->account->account_name ?? 'N/A' }}</td>
                    <td>{{ $ticket->contact->first_name ?? 'N/A' }} {{ $ticket->contact->last_name ?? '' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($ticket->priority == 'urgent') #f44336
                            @elseif($ticket->priority == 'high') #ff9800
                            @elseif($ticket->priority == 'medium') #ffc107
                            @else #4caf50
                            @endif; color: white;">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($ticket->status == 'new') #2196f3
                            @elseif($ticket->status == 'open') #ff9800
                            @elseif($ticket->status == 'in_progress') #9c27b0
                            @elseif($ticket->status == 'resolved') #4caf50
                            @else #757575
                            @endif; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($ticket->type) }}</td>
                    <td>{{ $ticket->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('support-tickets.show', $ticket) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('support-tickets.edit', $ticket) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            @php
                                $relatedTask = $ticket->tasks()->first();
                            @endphp
                            @if($relatedTask)
                                <a href="{{ route('tasks.show', $relatedTask) }}" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">View Task</a>
                            @endif
                            <form action="{{ route('support-tickets.destroy', $ticket) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">No support tickets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $tickets->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('support-tickets.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'support-tickets']) }}', {
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
