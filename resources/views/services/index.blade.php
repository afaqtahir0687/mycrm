@extends('layouts.app')

@section('title', 'Services Management')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Services Management</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'services.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('services.create') }}" class="btn btn-primary">Add New Service</a>
            <a href="{{ route('services.export_excel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('services.export_pdf') }}" class="btn btn-success">Export PDF</a>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-file').click()">Import Excel</button>
            <input type="file" id="import-file" accept=".xlsx,.xls" style="display:none" onchange="importExcel()">
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div>
                <strong>Total Services:</strong> {{ $summary['total'] }}
            </div>
            <div>
                <strong>Active:</strong> {{ $summary['active'] }}
            </div>
            <div>
                <strong>Inactive:</strong> {{ $summary['inactive'] }}
            </div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('services.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Code, Description" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Category:</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($filterOptions['categories'] as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="service_name" {{ request('sort_by') == 'service_name' ? 'selected' : '' }}>Name</option>
                        <option value="service_code" {{ request('sort_by') == 'service_code' ? 'selected' : '' }}>Code</option>
                        <option value="hourly_rate" {{ request('sort_by') == 'hourly_rate' ? 'selected' : '' }}>Hourly Rate</option>
                        <option value="fixed_price" {{ request('sort_by') == 'fixed_price' ? 'selected' : '' }}>Fixed Price</option>
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
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Pricing Type</th>
                    <th>Price/Rate</th>
                    <th>Est. Hours</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>{{ $service->service_code }}</td>
                    <td>{{ $service->service_name }}</td>
                    <td>{{ $service->category }}</td>
                    <td>{{ ucfirst($service->pricing_type) }}</td>
                    <td>
                        @if($service->pricing_type == 'hourly')
                            {{ $service->currency }} {{ number_format($service->hourly_rate, 2) }}/hr
                        @elseif($service->pricing_type == 'fixed')
                            {{ $service->currency }} {{ number_format($service->fixed_price, 2) }}
                        @else
                            Custom
                        @endif
                    </td>
                    <td>{{ $service->estimated_hours }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                            @if($service->status == 'active') #4caf50
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($service->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('services.show', $service) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('services.edit', $service) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('services.destroy', $service) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No services found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $services->links() }}
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('services.index') }}';
}

function importExcel() {
    const file = document.getElementById('import-file').files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('services.import_excel') }}', {
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
