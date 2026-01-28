@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Notifications</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'notifications.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <button onclick="markAllRead()" class="btn btn-secondary">Mark All as Read</button>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Unread:</strong> {{ $summary['unread'] }}</div>
            <div><strong>Read:</strong> {{ $summary['read'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('notifications.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Type:</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                        <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
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
                    <th>Title</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr style="background: {{ !$notification->read_at ? '#e3f2fd' : 'white' }};">
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($notification->type == 'success') #4caf50
                            @elseif($notification->type == 'warning') #ff9800
                            @elseif($notification->type == 'error') #f44336
                            @elseif($notification->type == 'reminder') #2196f3
                            @else #757575
                            @endif; color: white;">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </td>
                    <td><strong>{{ $notification->title }}</strong></td>
                    <td>{{ Str::limit($notification->message, 100) }}</td>
                    <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($notification->read_at)
                            <span style="color: #757575;">Read</span>
                        @else
                            <span style="color: #1976d2; font-weight: bold;">Unread</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('notifications.show', $notification) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            @if(!$notification->read_at)
                            <button onclick="markAsRead({{ $notification->id }})" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Mark Read</button>
                            @endif
                            @if($notification->action_url)
                            <a href="{{ $notification->action_url }}" class="btn btn-success" target="_blank" style="padding: 5px 10px; font-size: 12px;">Go to Link</a>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No notifications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $notifications->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('notifications.index') }}';
}

function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAllRead() {
    fetch('{{ route('notifications.mark-all-read') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection

