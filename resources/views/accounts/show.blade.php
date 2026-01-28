@extends('layouts.app')

@section('title', 'View Account')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Account Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'accounts.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Account Name:</strong> {{ $account->account_name }}</div>
        <div><strong>Account Type:</strong> {{ $account->account_type }}</div>
        <div><strong>Industry:</strong> {{ $account->industry }}</div>
        <div><strong>Email:</strong> {{ $account->email }}</div>
        <div><strong>Phone:</strong> {{ $account->phone }}</div>
        <div><strong>Website:</strong> {{ $account->website }}</div>
        <div style="grid-column: 1 / -1;"><strong>Billing Address:</strong> {{ $account->billing_address }}</div>
        <div><strong>Billing City:</strong> {{ $account->billing_city }}</div>
        <div><strong>Billing State:</strong> {{ $account->billing_state }}</div>
        <div><strong>Billing Country:</strong> {{ $account->billing_country }}</div>
        <div><strong>Billing Postal Code:</strong> {{ $account->billing_postal_code }}</div>
        <div><strong>Employees:</strong> {{ $account->employees }}</div>
        <div><strong>Annual Revenue:</strong> ${{ number_format($account->annual_revenue ?? 0, 2) }}</div>
        <div><strong>Status:</strong> {{ ucfirst($account->status) }}</div>
        <div><strong>Owner:</strong> {{ $account->owner->name ?? 'Unassigned' }}</div>
        <div><strong>Created Date:</strong> {{ $account->created_at->format('Y-m-d H:i:s') }}</div>
        @if($account->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $account->description }}</div>
        @endif
    </div>
</div>
@endsection

