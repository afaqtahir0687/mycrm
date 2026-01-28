@extends('layouts.app')

@section('title', 'View Tasks')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Tasks Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'tasks.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Subject:</strong> {{ $task->subject ?? 'N/A' }}</div>
        @if($task->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $task->description }}</div>
        @endif
        <div><strong>Priority:</strong> {{ ucfirst($task->priority ?? 'N/A') }}</div>
        <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status ?? 'N/A')) }}</div>
        <div><strong>Due Date:</strong> {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Due Time:</strong> {{ $task->due_time ? $task->due_time->format('H:i:s') : 'N/A' }}</div>
        <div><strong>Assigned To:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</div>
        <div><strong>Created By:</strong> {{ $task->creator->name ?? 'System' }}</div>
        @if($task->supportTicket)
        <div style="grid-column: 1 / -1; margin-top: 20px; padding: 15px; background: #f3f2f1; border-radius: 4px;">
            <h3 style="margin-bottom: 10px; color: #0078d4;">Related Support Ticket</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div><strong>Ticket Number:</strong> <a href="{{ route('support-tickets.show', $task->supportTicket) }}" style="color: #0078d4;">{{ $task->supportTicket->ticket_number }}</a></div>
                <div><strong>Subject:</strong> {{ $task->supportTicket->subject }}</div>
                <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->supportTicket->status)) }}</div>
                <div><strong>Priority:</strong> {{ ucfirst($task->supportTicket->priority) }}</div>
                <div><strong>Account:</strong> {{ $task->supportTicket->account->account_name ?? 'N/A' }}</div>
                <div><strong>Contact:</strong> {{ $task->supportTicket->contact ? $task->supportTicket->contact->first_name . ' ' . $task->supportTicket->contact->last_name : 'N/A' }}</div>
            </div>
            <div style="margin-top: 10px;">
                <a href="{{ route('support-tickets.show', $task->supportTicket) }}" class="btn btn-primary" style="padding: 8px 16px;">View Support Ticket</a>
            </div>
        </div>
        @endif
        
        @if($task->related_type)
        <div><strong>Related Type:</strong> {{ $task->related_type ?? 'N/A' }}</div>
        <div><strong>Related:</strong> {{ $task->related ? (($task->related_type == 'App\Models\Account' ? $task->related->account_name : ($task->related_type == 'App\Models\Contact' ? $task->related->first_name . ' ' . $task->related->last_name : ($task->related_type == 'App\Models\Lead' ? $task->related->first_name . ' ' . $task->related->last_name : ($task->related_type == 'App\Models\Deal' ? $task->related->deal_name : 'N/A'))))) : 'N/A' }}</div>
        @endif
        <div><strong>Is Reminder:</strong> {{ $task->is_reminder ? 'Yes' : 'No' }}</div>
        <div><strong>Reminder At:</strong> {{ $task->reminder_at ? $task->reminder_at->format('Y-m-d H:i:s') : 'N/A' }}</div>
        <div><strong>Completed At:</strong> {{ $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : 'N/A' }}</div>

        <div><strong>Created Date:</strong> {{ $task->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection