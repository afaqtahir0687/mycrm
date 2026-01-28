@extends('layouts.app')

@section('title', 'Engagement Summary')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Engagement Summary</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'engagements.summary']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Assigned:</strong> {{ $summary['total'] }}</div>
            <div><strong>Completed:</strong> {{ $summary['completed'] }}</div>
            <div><strong>Pending:</strong> {{ $summary['pending'] }}</div>
            <div><strong>Overdue:</strong> {{ $summary['overdue'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('engagements.summary') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Lead name, company" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Assigned To:</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">All Staff</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Action Type:</label>
                    <select name="assignment_action" class="form-control">
                        <option value="">All Actions</option>
                        <option value="call" {{ request('assignment_action') == 'call' ? 'selected' : '' }}>Call</option>
                        <option value="sms" {{ request('assignment_action') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ request('assignment_action') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="whatsapp" {{ request('assignment_action') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="online_meeting" {{ request('assignment_action') == 'online_meeting' ? 'selected' : '' }}>Online Meeting</option>
                        <option value="personal_visit" {{ request('assignment_action') == 'personal_visit' ? 'selected' : '' }}>Personal Visit</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Engagement Status:</label>
                    <select name="engagement_status" class="form-control">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('engagement_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('engagement_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="overdue" {{ request('engagement_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                    <th>Lead Name</th>
                    <th>Company</th>
                    <th>Assigned To</th>
                    <th>Action Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Engagement Result</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $engagement = $engagementResults[$lead->id] ?? null;
                    $isOverdue = $lead->assignment_deadline && $lead->assignment_deadline < now() && !$engagement;
                @endphp
                <tr>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->company_name }}</td>
                    <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #0078d4; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $lead->assignment_action)) }}
                        </span>
                    </td>
                    <td style="color: {{ $isOverdue ? '#d13438' : '#201f1e' }};">
                        {{ $lead->assignment_deadline ? $lead->assignment_deadline->format('Y-m-d H:i') : '-' }}
                    </td>
                    <td>
                        @if($engagement)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #107c10; color: white;">Completed</span>
                        @elseif($isOverdue)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #d13438; color: white;">Overdue</span>
                        @else
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #ffaa44; color: white;">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($engagement)
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $engagement->engagement_outcome }}">
                                {{ Str::limit($engagement->engagement_outcome, 50) }}
                            </div>
                            <small style="color: #8a8886;">By: {{ $engagement->creator->name ?? 'System' }} on {{ $engagement->engagement_date->format('Y-m-d H:i') }}</small>
                        @else
                            <span style="color: #8a8886;">Not recorded</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            @if($engagement)
                                <a href="{{ route('communications.show', $engagement) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View Result</a>
                            @else
                                <span style="color: #8a8886; font-size: 12px;">Awaiting result</span>
                            @endif
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">View Lead</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No assigned leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $leads->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('engagements.summary') }}';
}
</script>
@endsection
