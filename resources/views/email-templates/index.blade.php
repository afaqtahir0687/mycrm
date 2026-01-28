@extends('layouts.app')

@section('title', 'Email Templates')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Email Templates</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'email-templates.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('email-templates.create') }}" class="btn btn-primary">Create Template</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Templates:</strong> {{ $summary['total'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('email-templates.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Subject" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Category:</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <option value="email" {{ request('category') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('category') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="letter" {{ request('category') == 'letter' ? 'selected' : '' }}>Letter</option>
                        <option value="call_script" {{ request('category') == 'call_script' ? 'selected' : '' }}>Call Script</option>
                        <option value="visit_report" {{ request('category') == 'visit_report' ? 'selected' : '' }}>Visit Report</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Type:</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="welcome" {{ request('type') == 'welcome' ? 'selected' : '' }}>Welcome</option>
                        <option value="followup" {{ request('type') == 'followup' ? 'selected' : '' }}>Follow-up</option>
                        <option value="quotation" {{ request('type') == 'quotation' ? 'selected' : '' }}>Quotation</option>
                        <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                        <option value="official_letter" {{ request('type') == 'official_letter' ? 'selected' : '' }}>Official Letter</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Official:</label>
                    <select name="is_official" class="form-control">
                        <option value="">All</option>
                        <option value="1" {{ request('is_official') == '1' ? 'selected' : '' }}>Official Only</option>
                        <option value="0" {{ request('is_official') == '0' ? 'selected' : '' }}>Non-Official</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="is_active" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
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
                    <th>Name</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Official</th>
                    <th>Status</th>
                    <th>Variables</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                <tr>
                    <td>{{ $template->id }}</td>
                    <td>{{ $template->name }}@if($template->is_official) ⭐@endif</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #0078d4; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $template->category ?? 'email')) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($template->subject ?? 'N/A', 50) }}</td>
                    <td>{{ ucfirst($template->type) }}</td>
                    <td>
                        @if($template->is_official)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #ff6b00; color: white;">⭐ Official</span>
                        @else
                            <span style="color: #666;">-</span>
                        @endif
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $template->is_active ? '#4caf50' : '#757575' }}; color: white;">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ is_array($template->variables) ? implode(', ', $template->variables) : 'None' }}</td>
                    <td>{{ $template->creator->name ?? 'System' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('email-templates.show', $template) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('email-templates.edit', $template) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('email-templates.destroy', $template) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No templates found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $templates->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('email-templates.index') }}';
}
</script>
@endsection

