@extends('layouts.app')

@section('title', 'View Communications')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Communications Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'communications.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('communications.edit', $communication) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('communications.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Type:</strong> {{ ucfirst($communication->type ?? 'N/A') }}</div>
        <div><strong>Subject:</strong> {{ $communication->subject ?? 'N/A' }}</div>
        @if($communication->content)
        <div style="grid-column: 1 / -1;"><strong>Content:</strong><br>{{ $communication->content }}</div>
        @endif
        <div><strong>Direction:</strong> {{ ucfirst($communication->direction ?? 'N/A') }}</div>
        <div><strong>From Email:</strong> {{ $communication->from_email ?? 'N/A' }}</div>
        <div><strong>To Email:</strong> {{ $communication->to_email ?? 'N/A' }}</div>
        <div><strong>From Phone:</strong> {{ $communication->from_phone ?? 'N/A' }}</div>
        <div><strong>To Phone:</strong> {{ $communication->to_phone ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $communication->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $communication->contact->first_name ?? 'N/A' }} {{ $communication->contact->last_name ?? '' }}</div>
        <div><strong>Lead:</strong> {{ $communication->lead->first_name ?? 'N/A' }} {{ $communication->lead->last_name ?? '' }} ({{ $communication->lead->company_name ?? 'N/A' }})</div>
        <div><strong>Duration Minutes:</strong> {{ $communication->duration_minutes ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($communication->status ?? 'N/A') }}</div>
        <div><strong>Created By:</strong> {{ $communication->creator->name ?? 'System' }}</div>

        <div><strong>Created Date:</strong> {{ $communication->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection