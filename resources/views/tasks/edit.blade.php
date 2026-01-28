@extends('layouts.app')

@section('title', 'Edit Tasks')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Tasks</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'tasks.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('tasks.update', $tasks) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="subject" value="{{ old('subject', $tasks->subject) }}" required>
                @error('subject')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description </label>
                <textarea name="description" rows="4">{{ old('description', $tasks->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Priority </label>
                <select name="priority" >
                    <option value="low" {{ old('priority', $tasks->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="normal" {{ old('priority', $tasks->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="medium" {{ old('priority', $tasks->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $tasks->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $tasks->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status </label>
                <select name="status" >
                    <option value="active" {{ old('status', $tasks->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $tasks->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="new" {{ old('status', $tasks->status) == 'new' ? 'selected' : '' }}>New</option>
                    <option value="open" {{ old('status', $tasks->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $tasks->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status', $tasks->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $tasks->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status', $tasks->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="won" {{ old('status', $tasks->status) == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status', $tasks->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Due Date </label>
                <input type="date" name="due_date" value="{{ old('due_date', $tasks->due_date?->format('Y-m-d')) }}" >
                @error('due_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Due Time </label>
                <input type="text" name="due_time" value="{{ old('due_time', $tasks->due_time) }}" >
                @error('due_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Assigned To </label>
                <select name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Type </label>
                <input type="text" name="related_type" value="{{ old('related_type', $tasks->related_type) }}" >
                @error('related_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Id</label>
                <select name="related_id" class="form-control">
                    <option value="">Select Related</option>
                    @foreach($relateds as $related)
                        <option value="{{ $related->id }}" {{ old('related_id', $tasks->related_id) == $related->id ? 'selected' : '' }}>{{ $related->name ?? $related->account_name ?? $related->first_name }}</option>
                    @endforeach
                </select>
                @error('related_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_reminder" value="1" {{ old('is_reminder', $tasks->is_reminder) ? 'checked' : '' }}>
                    Is Reminder
                </label>
                @error('is_reminder')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Reminder At </label>
                <input type="text" name="reminder_at" value="{{ old('reminder_at', $tasks->reminder_at) }}" >
                @error('reminder_at')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Tasks</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection