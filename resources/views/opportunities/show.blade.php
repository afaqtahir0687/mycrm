@extends('layouts.app')

@section('title', 'View Opportunities')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Opportunities Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'opportunities.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('opportunities.edit', $opportunity) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('opportunities.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Opportunity Name:</strong> {{ $opportunity->opportunity_name ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $opportunity->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $opportunity->contact->first_name ?? 'N/A' }} {{ $opportunity->contact->last_name ?? '' }}</div>
        <div><strong>Amount:</strong> ${{ number_format($opportunity->amount ?? 0, 2) }}</div>
        <div><strong>Currency:</strong> {{ $opportunity->currency ?? 'N/A' }}</div>
        <div><strong>Close Date:</strong> {{ $opportunity->close_date ? $opportunity->close_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Stage:</strong> {{ ucfirst(str_replace('_', ' ', $opportunity->stage ?? 'N/A')) }}</div>
        <div><strong>Probability:</strong> {{ $opportunity->probability ?? 'N/A' }}%</div>
        <div><strong>Type:</strong> {{ $opportunity->type ?? 'N/A' }}</div>
        @if($opportunity->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $opportunity->description }}</div>
        @endif
        <div><strong>Owner:</strong> {{ $opportunity->owner->name ?? 'Unassigned' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($opportunity->status ?? 'N/A') }}</div>

        <div><strong>Created Date:</strong> {{ $opportunity->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection