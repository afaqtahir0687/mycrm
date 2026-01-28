@extends('layouts.app')

@section('title', 'Activity Feed')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Activity Feed</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'activities.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('activities.create') }}" class="btn btn-primary">Log Activity</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Activities:</strong> {{ $summary['total'] }}</div>
            <div><strong>Today:</strong> {{ $summary['today'] }}</div>
            <div><strong>This Week:</strong> {{ $summary['this_week'] }}</div>
            <div><strong>This Month:</strong> {{ $summary['this_month'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('activities.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Activity Type:</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="created" {{ request('type') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('type') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('type') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="called" {{ request('type') == 'called' ? 'selected' : '' }}>Called</option>
                        <option value="emailed" {{ request('type') == 'emailed' ? 'selected' : '' }}>Emailed</option>
                        <option value="met" {{ request('type') == 'met' ? 'selected' : '' }}>Meeting</option>
                        <option value="note" {{ request('type') == 'note' ? 'selected' : '' }}>Note</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Date From:</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Date To:</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="filter-item">
                    <label>User:</label>
                    <select name="user_id" class="form-control">
                        <option value="">All Users</option>
                        @foreach(\App\Models\User::where('is_active', true)->orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                    <th>Date</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>User</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->activity_date->format('Y-m-d H:i') }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #1976d2; color: white;">
                            {{ ucfirst($activity->activity_type) }}
                        </span>
                    </td>
                    <td>{{ $activity->title }}</td>
                    <td>{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</td>
                    <td>{{ $activity->user->name ?? 'System' }}</td>
                    <td>{{ $activity->duration_minutes ? $activity->duration_minutes . ' min' : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('activities.show', $activity) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No activities found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $activities->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('activities.index') }}';
}
</script>
@endsection

