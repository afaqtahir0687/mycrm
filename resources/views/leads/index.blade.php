@extends('layouts.app')

@section('title', 'Leads Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Leads Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'leads.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('leads.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'leads']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'leads']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div>
                <strong>Total Leads:</strong> {{ $summary['total'] }}
            </div>
            <div>
                <strong>New:</strong> {{ $summary['new'] }}
            </div>
            <div>
                <strong>Contacted:</strong> {{ $summary['contacted'] }}
            </div>
            <div>
                <strong>Qualified:</strong> {{ $summary['qualified'] }}
            </div>
            <div>
                <strong>Converted:</strong> {{ $summary['converted'] }}
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('leads.create') }}" class="btn btn-success" style="text-align: left;">Create New Lead</a>
                <a href="{{ route('accounts.index') }}" class="btn btn-primary" style="text-align: left;">View Accounts</a>
                <a href="{{ route('contacts.index') }}" class="btn btn-primary" style="text-align: left;">View Contacts</a>
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">View Deals</a>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="text-align: left;">View Opportunities</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Leads Information</h2>
            <p style="line-height: 1.8; color: #666;">
                The Leads module captures potential customers and inquiries before they are qualified.
                Use this screen to filter and prioritise leads by status, source, industry, and assigned owner,
                then convert the most promising leads into Accounts, Contacts, Opportunities, and Deals as they progress
                through your sales pipeline.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('leads.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Company" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Lead Source:</label>
                    <select name="lead_source" class="form-control">
                        <option value="">All Sources</option>
                        @foreach($filterOptions['lead_sources'] as $source)
                        <option value="{{ $source }}" {{ request('lead_source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
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
                    <label>Assigned To:</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">All Users</option>
                        @foreach($filterOptions['assigned_users'] as $user)
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
                        <option value="company_name" {{ request('sort_by') == 'company_name' ? 'selected' : '' }}>Company</option>
                        <option value="lead_score" {{ request('sort_by') == 'lead_score' ? 'selected' : '' }}>Lead Score</option>
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
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Lead Source</th>
                    <th>Lead Score</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Assignment Action</th>
                    <th>Deadline</th>
                    <th>Engagement Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $lead->id }}</td>
                    <td>{{ $lead->first_name }}</td>
                    <td>{{ $lead->last_name }}</td>
                    <td>{{ $lead->company_name }}</td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ $lead->lead_source }}</td>
                    <td>{{ $lead->lead_score }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($lead->status == 'new') #2196f3
                            @elseif($lead->status == 'contacted') #ff9800
                            @elseif($lead->status == 'qualified') #4caf50
                            @elseif($lead->status == 'converted') #8bc34a
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>
                        @if($lead->assignment_action)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #0078d4; color: white;">
                                {{ ucfirst(str_replace('_', ' ', $lead->assignment_action)) }}
                            </span>
                        @else
                            <span style="color: #8a8886;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->assignment_deadline)
                            <span style="color: {{ $lead->assignment_deadline < now() && !$lead->communications()->where('engagement_status', 'completed')->exists() ? '#d13438' : '#201f1e' }};">
                                {{ $lead->assignment_deadline->format('Y-m-d H:i') }}
                            </span>
                        @else
                            <span style="color: #8a8886;">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $engagement = $lead->communications()->where('engagement_status', 'completed')->first();
                            $isOverdue = $lead->assignment_deadline && $lead->assignment_deadline < now() && !$engagement;
                        @endphp
                        @if($engagement)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #107c10; color: white;">Completed</span>
                        @elseif($isOverdue)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #d13438; color: white;">Overdue</span>
                        @elseif($lead->assigned_to)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #ffaa44; color: white;">Pending</span>
                        @else
                            <span style="color: #8a8886;">-</span>
                        @endif
                    </td>
                    <td>{{ $lead->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;" onclick="openAssignModal({{ $lead->id }}, '{{ $lead->first_name }} {{ $lead->last_name }}', {{ $lead->assigned_to ?? 'null' }}, '{{ $lead->assignment_action ?? '' }}', '{{ $lead->assignment_deadline ? $lead->assignment_deadline->format('Y-m-d\TH:i') : '' }}')">Assign</button>
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="15" style="text-align: center; padding: 20px;">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $leads->links() }}
    </div>
</div>

<!-- Assign Lead Modal -->
<div id="assignModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 24px; border-radius: 4px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: var(--ms-blue, #0078d4);">Assign Lead</h2>
            <button type="button" onclick="closeAssignModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
        </div>
        <form id="assignForm" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Lead:</label>
                <div id="leadName" style="padding: 8px; background: #f3f2f1; border-radius: 2px; color: #201f1e;"></div>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="assign_user" style="display: block; margin-bottom: 5px; font-weight: 600;">Assign To Staff Member *</label>
                <select id="assign_user" name="assigned_to" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;">
                    <option value="">Select Staff Member</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="assign_action" style="display: block; margin-bottom: 5px; font-weight: 600;">Action Type *</label>
                <select id="assign_action" name="assignment_action" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;">
                    <option value="">Select Action</option>
                    <option value="call">Call</option>
                    <option value="sms">SMS</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="online_meeting">Online Meeting</option>
                    <option value="personal_visit">Personal Visit</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="assign_deadline" style="display: block; margin-bottom: 5px; font-weight: 600;">Deadline *</label>
                <input type="datetime-local" id="assign_deadline" name="assignment_deadline" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;" min="{{ date('Y-m-d\TH:i') }}">
                <small style="color: #8a8886; font-size: 12px;">Select deadline for completing the action and reporting back</small>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeAssignModal()" class="btn btn-secondary" style="padding: 8px 16px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Assign Lead</button>
            </div>
        </form>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('leads.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'leads']) }}', {
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

function openAssignModal(leadId, leadName, currentAssignedTo, currentAction, currentDeadline) {
    document.getElementById('leadName').textContent = leadName;
    document.getElementById('assignForm').action = '{{ route('leads.assign', ':id') }}'.replace(':id', leadId);
    
    if (currentAssignedTo) {
        document.getElementById('assign_user').value = currentAssignedTo;
    } else {
        document.getElementById('assign_user').value = '';
    }
    
    if (currentAction) {
        document.getElementById('assign_action').value = currentAction;
    } else {
        document.getElementById('assign_action').value = '';
    }
    
    if (currentDeadline) {
        // Convert datetime to local datetime-local format
        const deadline = new Date(currentDeadline);
        const year = deadline.getFullYear();
        const month = String(deadline.getMonth() + 1).padStart(2, '0');
        const day = String(deadline.getDate()).padStart(2, '0');
        const hours = String(deadline.getHours()).padStart(2, '0');
        const minutes = String(deadline.getMinutes()).padStart(2, '0');
        document.getElementById('assign_deadline').value = `${year}-${month}-${day}T${hours}:${minutes}`;
    } else {
        document.getElementById('assign_deadline').value = '';
    }
    
    document.getElementById('assignModal').style.display = 'block';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
    document.getElementById('assignForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('assignModal');
    if (event.target == modal) {
        closeAssignModal();
    }
}
</script>
@endsection

