@extends('layouts.app')

@section('title', 'View Notification')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Notification Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'notifications.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Back to List</a>
            @if($notification->action_url)
            <a href="{{ $notification->action_url }}" class="btn btn-success" target="_blank">Go to Link</a>
            @endif
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div style="grid-column: 1 / -1;">
            <strong>Type:</strong>
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 14px; background: 
                @if($notification->type == 'success') #4caf50
                @elseif($notification->type == 'warning') #ff9800
                @elseif($notification->type == 'error') #f44336
                @elseif($notification->type == 'reminder') #2196f3
                @else #757575
                @endif; color: white;">
                {{ ucfirst($notification->type) }}
            </span>
        </div>
        <div style="grid-column: 1 / -1;">
            <strong>Title:</strong><br>
            <div style="font-size: 18px; font-weight: bold; margin-top: 5px;">{{ $notification->title }}</div>
        </div>
        <div style="grid-column: 1 / -1;">
            <strong>Message:</strong><br>
            <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 5px; white-space: pre-wrap;">{{ $notification->message }}</div>
        </div>
        <div><strong>Status:</strong> 
            @if($notification->read_at)
                <span style="color: #757575;">Read</span>
            @else
                <span style="color: #1976d2; font-weight: bold;">Unread</span>
            @endif
        </div>
        <div><strong>User:</strong> {{ $notification->user->name ?? 'N/A' }}</div>
        @if($notification->notifiable)
        <div style="grid-column: 1 / -1;">
            <strong>Related To:</strong> {{ class_basename($notification->notifiable_type) }} #{{ $notification->notifiable_id }}
        </div>
        @endif
        @if($notification->action_url)
        <div style="grid-column: 1 / -1;">
            <strong>Action URL:</strong> <a href="{{ $notification->action_url }}" target="_blank">{{ $notification->action_url }}</a>
        </div>
        @endif
        <div><strong>Created Date:</strong> {{ $notification->created_at->format('Y-m-d H:i:s') }}</div>
        @if($notification->read_at)
        <div><strong>Read Date:</strong> {{ $notification->read_at->format('Y-m-d H:i:s') }}</div>
        @endif
    </div>
</div>
@endsection

