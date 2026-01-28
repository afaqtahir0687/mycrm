@extends('layouts.app')

@section('title', 'View Deals')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Deals Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'deals.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('deals.edit', $deal) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('deals.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Deal Name:</strong> {{ $deal->deal_name ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $deal->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $deal->contact->first_name ?? 'N/A' }} {{ $deal->contact->last_name ?? '' }}</div>
        <div><strong>Lead:</strong> {{ $deal->lead->first_name ?? 'N/A' }} {{ $deal->lead->last_name ?? '' }} ({{ $deal->lead->company_name ?? 'N/A' }})</div>
        <div><strong>Amount:</strong> ${{ number_format($deal->amount ?? 0, 2) }}</div>
        <div><strong>Currency:</strong> {{ $deal->currency ?? 'N/A' }}</div>
        <div><strong>Expected Close Date:</strong> {{ $deal->expected_close_date ? $deal->expected_close_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Stage:</strong> {{ ucfirst(str_replace('_', ' ', $deal->stage ?? 'N/A')) }}</div>
        <div><strong>Probability:</strong> {{ $deal->probability ?? 'N/A' }}%</div>
        @if($deal->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $deal->description }}</div>
        @endif
        <div><strong>Owner:</strong> {{ $deal->owner->name ?? 'Unassigned' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($deal->status ?? 'N/A') }}</div>

        <div><strong>Created Date:</strong> {{ $deal->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection