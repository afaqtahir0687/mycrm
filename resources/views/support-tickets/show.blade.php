@extends('layouts.app')

@section('title', 'View Support Tickets')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Support Tickets Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'support-tickets.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('support-tickets.edit', $supportTicket) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('support-tickets.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Ticket Number:</strong> {{ $supportTicket->ticket_number ?? 'N/A' }}</div>
        <div><strong>Subject:</strong> {{ $supportTicket->subject ?? 'N/A' }}</div>
        @if($supportTicket->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $supportTicket->description }}</div>
        @endif
        <div><strong>Account:</strong> {{ $supportTicket->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $supportTicket->contact->first_name ?? 'N/A' }} {{ $supportTicket->contact->last_name ?? '' }}</div>
        <div><strong>Priority:</strong> {{ ucfirst($supportTicket->priority ?? 'N/A') }}</div>
        <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $supportTicket->status ?? 'N/A')) }}</div>
        <div><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $supportTicket->type ?? 'N/A')) }}</div>
        <div><strong>Assigned To:</strong> {{ $supportTicket->assignedUser->name ?? 'Unassigned' }}</div>
        <div><strong>Created By:</strong> {{ $supportTicket->creator->name ?? 'System' }}</div>
        <div><strong>SLA Hours:</strong> {{ $supportTicket->sla_hours ?? 'N/A' }}</div>
        <div><strong>SLA Deadline:</strong> {{ $supportTicket->sla_deadline ? $supportTicket->sla_deadline->format('Y-m-d H:i:s') : 'N/A' }}</div>
        <div><strong>Resolved At:</strong> {{ $supportTicket->resolved_at ? $supportTicket->resolved_at->format('Y-m-d H:i:s') : 'N/A' }}</div>
        <div><strong>Closed At:</strong> {{ $supportTicket->closed_at ? $supportTicket->closed_at->format('Y-m-d H:i:s') : 'N/A' }}</div>

        <div><strong>Created Date:</strong> {{ $supportTicket->created_at->format('Y-m-d H:i:s') }}</div>
        
        @php
            $relatedTask = $supportTicket->tasks()->first();
        @endphp
        @if($relatedTask)
        <div style="grid-column: 1 / -1; margin-top: 20px; padding: 15px; background: #f3f2f1; border-radius: 4px;">
            <h3 style="margin-bottom: 10px; color: #0078d4;">Related Task</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div><strong>Task:</strong> <a href="{{ route('tasks.show', $relatedTask) }}" style="color: #0078d4;">{{ $relatedTask->subject }}</a></div>
                <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $relatedTask->status)) }}</div>
                <div><strong>Assigned To:</strong> {{ $relatedTask->assignedUser->name ?? 'Unassigned' }}</div>
                <div><strong>Due Date:</strong> {{ $relatedTask->due_date ? $relatedTask->due_date->format('Y-m-d') : 'N/A' }}</div>
            </div>
            <div style="margin-top: 10px;">
                <a href="{{ route('tasks.show', $relatedTask) }}" class="btn btn-primary" style="padding: 8px 16px;">View Task</a>
                @if($relatedTask->status != 'completed')
                <a href="{{ route('tasks.edit', $relatedTask) }}" class="btn btn-secondary" style="padding: 8px 16px;">Update Task</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection