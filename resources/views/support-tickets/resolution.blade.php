@extends('layouts.app')

@section('title', 'Resolution Summary')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Resolution Summary</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'support-tickets.resolution']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Resolved:</strong> {{ $summary['total'] }}</div>
            <div><strong>Today:</strong> {{ $summary['today'] }}</div>
            <div><strong>This Week:</strong> {{ $summary['this_week'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('support-tickets.resolution') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket number, subject, task" class="form-control">
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
                    <th>Ticket Number</th>
                    <th>Ticket Subject</th>
                    <th>Task Subject</th>
                    <th>Assigned To</th>
                    <th>Completed Date</th>
                    <th>Task Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>
                        <a href="{{ route('support-tickets.show', $task->supportTicket) }}" style="color: #0078d4; text-decoration: none;">
                            {{ $task->supportTicket->ticket_number }}
                        </a>
                    </td>
                    <td>{{ $task->supportTicket->subject }}</td>
                    <td>{{ $task->subject }}</td>
                    <td>{{ $task->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>{{ $task->completed_at ? $task->completed_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $task->description }}">
                            {{ Str::limit($task->description, 50) }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View Task</a>
                            <a href="{{ route('support-tickets.show', $task->supportTicket) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">View Ticket</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No resolved tasks found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $tasks->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('support-tickets.resolution') }}';
}
</script>
@endsection
