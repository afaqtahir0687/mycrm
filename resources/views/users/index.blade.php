@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Users Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'users.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add New Entry</a>
            <a href="{{ route('export.excel', ['resource' => 'users']) }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('export.pdf', ['resource' => 'users']) }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Users:</strong> {{ $summary['total'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
            <div><strong>Inactive:</strong> {{ $summary['inactive'] }}</div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('users.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email" class="form-control">
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
                    <label>Role:</label>
                    <select name="role_id" class="form-control">
                        <option value="">All Roles</option>
                        @foreach($filterOptions['roles'] as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
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
                    <th>Email</th>
                    <th>Role</th>
                    <th>Position</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name ?? 'No Role' }}</td>
                    <td>{{ $user->position }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $user->is_active ? '#4caf50' : '#757575' }}; color: white;">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            @if($user->id != auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $users->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('users.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('import.excel', ['resource' => 'users']) }}', {
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
