@extends('layouts.app')

@section('title', 'Create Automation Workflows')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Automation Workflows</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'automation-workflows.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('automation-workflows.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('automation-workflows.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description </label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Trigger Type </label>
                <input type="text" name="trigger_type" value="{{ old('trigger_type') }}" >
                @error('trigger_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Trigger Conditions (JSON format) </label>
                <textarea name="trigger_conditions" rows="6" placeholder='{"field": "status", "operator": "equals", "value": "active"}'>{{ old('trigger_conditions') }}</textarea>
                @error('trigger_conditions')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Actions (JSON format) </label>
                <textarea name="actions" rows="6" placeholder='[{"type": "email", "template": "welcome", "recipient": "contact"}]'>{{ old('actions') }}</textarea>
                @error('actions')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    Is Active
                </label>
                @error('is_active')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Automation Workflows</button>
            <a href="{{ route('automation-workflows.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection