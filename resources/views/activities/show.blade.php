@extends('layouts.app')

@section('title', 'View Activity')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Activity Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'activities.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Activity Type:</strong> {{ ucfirst(str_replace('_', ' ', $activity->activity_type ?? 'N/A')) }}</div>
        <div><strong>Title:</strong> {{ $activity->title ?? 'N/A' }}</div>
        @if($activity->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $activity->description }}</div>
        @endif
        <div><strong>Subject Type:</strong> {{ $activity->subject_type ?? 'N/A' }}</div>
        <div><strong>Subject:</strong> 
            @if($activity->subject)
                {{ $activity->subject_type == 'App\Models\Account' ? $activity->subject->account_name : ($activity->subject_type == 'App\Models\Contact' ? $activity->subject->first_name . ' ' . $activity->subject->last_name : ($activity->subject_type == 'App\Models\Lead' ? $activity->subject->first_name . ' ' . $activity->subject->last_name : ($activity->subject_type == 'App\Models\Deal' ? $activity->subject->deal_name : 'N/A'))) }}
            @else
                N/A
            @endif
        </div>
        <div><strong>User:</strong> {{ $activity->user->name ?? 'System' }}</div>
        <div><strong>Activity Date:</strong> {{ $activity->activity_date ? $activity->activity_date->format('Y-m-d H:i:s') : 'N/A' }}</div>
        <div><strong>Duration Minutes:</strong> {{ $activity->duration_minutes ?? 'N/A' }}</div>
        <div><strong>Location:</strong> {{ $activity->location ?? 'N/A' }}</div>
        @if($activity->metadata)
        <div style="grid-column: 1 / -1;"><strong>Metadata:</strong><br>{{ json_encode($activity->metadata, JSON_PRETTY_PRINT) }}</div>
        @endif
        <div><strong>Created Date:</strong> {{ $activity->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection