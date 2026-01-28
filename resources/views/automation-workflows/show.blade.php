@extends('layouts.app')

@section('title', 'View Automation Workflows')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Automation Workflows Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'automation-workflows.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('automation-workflows.edit', $automationWorkflow) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('automation-workflows.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Name:</strong> {{ $automationWorkflow->name ?? 'N/A' }}</div>
        @if($automationWorkflow->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $automationWorkflow->description }}</div>
        @endif
        <div><strong>Trigger Type:</strong> {{ ucfirst(str_replace('_', ' ', $automationWorkflow->trigger_type ?? 'N/A')) }}</div>
        <div style="grid-column: 1 / -1;">
            <strong>Trigger Conditions:</strong><br>
            @if($automationWorkflow->trigger_conditions && is_array($automationWorkflow->trigger_conditions))
                <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 5px;">
                    <pre style="margin: 0; white-space: pre-wrap; font-family: inherit;">{{ json_encode($automationWorkflow->trigger_conditions, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @else
                <div style="margin-top: 5px;">{{ is_string($automationWorkflow->trigger_conditions) ? $automationWorkflow->trigger_conditions : 'N/A' }}</div>
            @endif
        </div>
        <div style="grid-column: 1 / -1;">
            <strong>Actions:</strong><br>
            @if($automationWorkflow->actions && is_array($automationWorkflow->actions))
                <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 5px;">
                    <pre style="margin: 0; white-space: pre-wrap; font-family: inherit;">{{ json_encode($automationWorkflow->actions, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @else
                <div style="margin-top: 5px;">{{ is_string($automationWorkflow->actions) ? $automationWorkflow->actions : 'N/A' }}</div>
            @endif
        </div>
        <div><strong>Is Active:</strong> {{ $automationWorkflow->is_active ? 'Yes' : 'No' }}</div>
        <div><strong>Created By:</strong> {{ $automationWorkflow->creator->name ?? 'System' }}</div>

        <div><strong>Created Date:</strong> {{ $automationWorkflow->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection