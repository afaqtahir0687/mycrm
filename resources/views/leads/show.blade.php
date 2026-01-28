@extends('layouts.app')

@section('title', 'View Lead')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Lead Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'leads.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('contacts.create', ['lead_id' => $lead->id]) }}" class="btn btn-primary">Create Contact from Lead</a>
            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>First Name:</strong> {{ $lead->first_name }}</div>
        <div><strong>Last Name:</strong> {{ $lead->last_name }}</div>
        <div><strong>Company:</strong> {{ $lead->company_name }}</div>
        <div><strong>Email:</strong> {{ $lead->email }}</div>
        <div><strong>Phone:</strong> {{ $lead->phone }}</div>
        <div><strong>Mobile:</strong> {{ $lead->mobile }}</div>
        <div style="grid-column: 1 / -1;"><strong>Address:</strong> {{ $lead->address }}</div>
        <div><strong>City:</strong> {{ $lead->city }}</div>
        <div><strong>State:</strong> {{ $lead->state }}</div>
        <div><strong>Country:</strong> {{ $lead->country }}</div>
        <div><strong>Postal Code:</strong> {{ $lead->postal_code }}</div>
        <div><strong>Website:</strong> {{ $lead->website }}</div>
        <div><strong>Lead Source:</strong> {{ $lead->lead_source }}</div>
        <div><strong>Industry:</strong> {{ $lead->industry }}</div>
        <div><strong>Lead Score:</strong> {{ $lead->lead_score }}</div>
        <div><strong>Status:</strong> {{ ucfirst($lead->status) }}</div>
        <div><strong>Assigned To:</strong> {{ $lead->assignedUser->name ?? 'Unassigned' }}</div>
        <div><strong>Assignment Action:</strong> 
            @if($lead->assignment_action)
                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #0078d4; color: white;">
                    {{ ucfirst(str_replace('_', ' ', $lead->assignment_action)) }}
                </span>
            @else
                <span style="color: #8a8886;">Not assigned</span>
            @endif
        </div>
        <div><strong>Created By:</strong> {{ $lead->creator->name ?? 'System' }}</div>
        <div><strong>Created Date:</strong> {{ $lead->created_at->format('Y-m-d H:i:s') }}</div>
        @if($lead->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $lead->notes }}</div>
        @endif
    </div>
</div>
@endsection

