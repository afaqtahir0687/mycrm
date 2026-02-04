@extends('layouts.app')

@section('title', 'Agreements')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Agreements</h1>
        <div class="form-actions">
            <a href="{{ route('agreements.create') }}" class="btn btn-primary">Add New Agreement</a>
        </div>
    </div>

    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total:</strong> {{ $summary['total'] }}</div>
            <div><strong>Draft:</strong> {{ $summary['draft'] }}</div>
            <div><strong>Signed:</strong> {{ $summary['signed'] }}</div>
            <div><strong>Active:</strong> {{ $summary['active'] }}</div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Agreement #</th>
                    <th>Type</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Agreement Date</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agreements as $agreement)
                <tr>
                    <td>{{ $agreement->id }}</td>
                    <td>{{ $agreement->agreement_number }}</td>
                    <td>{{ $agreement->agreement_type }}</td>
                    <td>{{ $agreement->account->account_name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($agreement->status) }}</td>
                    <td>{{ $agreement->agreement_date?->format('Y-m-d') ?? 'N/A' }}</td>
                    <td>{{ $agreement->currency ?? 'USD' }} {{ number_format($agreement->total_value ?? 0, 2) }}</td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <a href="{{ route('agreements.show', $agreement) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            <a href="{{ route('agreements.edit', $agreement) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                            <form action="{{ route('agreements.destroy', $agreement) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No agreements found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $agreements->links() }}
    </div>
</div>
@endsection


