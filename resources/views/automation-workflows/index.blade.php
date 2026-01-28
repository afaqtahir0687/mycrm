@extends('layouts.app')

@section('title', 'Automation Workflows Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Automation Workflows Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'automation-workflows.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('automation-workflows.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'automation-workflows']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'automation-workflows']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
            <div><strong>Inactive:</strong> {{ $summary['inactive'] }}</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('automation-workflows.create') }}" class="btn btn-success" style="text-align: left;">Create New Workflow</a>
                <a href="{{ route('leads.index') }}" class="btn btn-primary" style="text-align: left;">View Leads</a>
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">View Deals</a>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="text-align: left;">View Opportunities</a>
                <a href="{{ route('tasks.index') }}" class="btn btn-primary" style="text-align: left;">View Tasks</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Automation Workflows Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Automation Workflows allow you to define rules that trigger actions based on changes in your CRM data.
                Use this screen to configure and monitor workflows that automate follow-ups, notifications, and data updates
                across Leads, Deals, Opportunities, and Tasks, helping you enforce consistent business processes.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('automation-workflows.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Description" class="form-control">
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
                    <label>Trigger Type:</label>
                    <select name="trigger_type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($filterOptions['trigger_types'] as $type)
                        <option value="{{ $type }}" {{ request('trigger_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
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
                    <th>Description</th>
                    <th>Trigger Type</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workflows as $workflow)
                <tr>
                    <td>{{ $workflow->id }}</td>
                    <td>{{ $workflow->name }}</td>
                    <td>{{ Str::limit($workflow->description, 50) }}</td>
                    <td>{{ $workflow->trigger_type }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $workflow->is_active ? '#4caf50' : '#757575' }}; color: white;">
                            {{ $workflow->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $workflow->creator->name ?? 'System' }}</td>
                    <td>{{ $workflow->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('automation-workflows.show', $workflow) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('automation-workflows.edit', $workflow) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('automation-workflows.destroy', $workflow) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No workflows found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $workflows->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('automation-workflows.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'automation-workflows']) }}', {
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
