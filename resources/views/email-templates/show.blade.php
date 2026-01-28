@extends('layouts.app')

@section('title', 'View Email Template')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Email Template Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'email-templates.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('email-templates.edit', $emailTemplate) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('email-templates.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Name:</strong> {{ $emailTemplate->name ?? 'N/A' }}</div>
        <div><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $emailTemplate->type ?? 'N/A')) }}</div>
        <div style="grid-column: 1 / -1;"><strong>Subject:</strong> {{ $emailTemplate->subject ?? 'N/A' }}</div>
        <div style="grid-column: 1 / -1;"><strong>Body:</strong><br><div style="background: #f5f5f5; padding: 15px; border-radius: 4px; white-space: pre-wrap;">{{ $emailTemplate->body ?? 'N/A' }}</div></div>
        <div><strong>Is Active:</strong> {{ $emailTemplate->is_active ? 'Yes' : 'No' }}</div>
        <div><strong>Created By:</strong> {{ $emailTemplate->creator->name ?? 'System' }}</div>
        @if($emailTemplate->variables)
        <div style="grid-column: 1 / -1;"><strong>Variables:</strong><br>{{ implode(', ', $emailTemplate->variables ?? []) }}</div>
        @endif
        <div><strong>Created Date:</strong> {{ $emailTemplate->created_at->format('Y-m-d H:i:s') }}</div>
        <div><strong>Last Updated:</strong> {{ $emailTemplate->updated_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection