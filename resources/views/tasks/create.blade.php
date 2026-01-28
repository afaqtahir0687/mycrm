@extends('layouts.app')

@section('title', 'Create Tasks')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Tasks</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'tasks.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 32px;">
            <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required>
                @error('subject')
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
                <label>Priority </label>
                <select name="priority" >
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="not_started" {{ old('status', $preSelected['status'] ?? 'not_started') == 'not_started' ? 'selected' : '' }}>Not Started</option>
                    <option value="in_progress" {{ old('status', $preSelected['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $preSelected['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $preSelected['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Due Date </label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" >
                @error('due_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Due Time </label>
                <input type="text" name="due_time" value="{{ old('due_time') }}" >
                @error('due_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Assigned To </label>
                <select name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to', $preSelected['assigned_to'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Type </label>
                <select name="related_type" id="related_type" class="form-control">
                    <option value="">None</option>
                    <option value="App\Models\Account" {{ old('related_type', $preSelected['related_type'] ?? '') == 'App\Models\Account' ? 'selected' : '' }}>Account</option>
                    <option value="App\Models\Contact" {{ old('related_type', $preSelected['related_type'] ?? '') == 'App\Models\Contact' ? 'selected' : '' }}>Contact</option>
                    <option value="App\Models\Lead" {{ old('related_type', $preSelected['related_type'] ?? '') == 'App\Models\Lead' ? 'selected' : '' }}>Lead</option>
                    <option value="App\Models\Deal" {{ old('related_type', $preSelected['related_type'] ?? '') == 'App\Models\Deal' ? 'selected' : '' }}>Deal</option>
                </select>
                @error('related_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Item</label>
                <select name="related_id" id="related_id" class="form-control">
                    <option value="">Select Related Item</option>
                    <!-- Accounts -->
                    <optgroup label="Accounts" id="accounts-group" style="display: none;">
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" data-type="App\Models\Account" {{ old('related_id', $preSelected['related_id'] ?? '') == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                        @endforeach
                    </optgroup>
                    <!-- Contacts -->
                    <optgroup label="Contacts" id="contacts-group" style="display: none;">
                        @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}" data-type="App\Models\Contact" {{ old('related_id', $preSelected['related_id'] ?? '') == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                        @endforeach
                    </optgroup>
                    <!-- Leads -->
                    <optgroup label="Leads" id="leads-group" style="display: none;">
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}" data-type="App\Models\Lead" {{ old('related_id', $preSelected['related_id'] ?? '') == $lead->id ? 'selected' : '' }}>{{ $lead->first_name }} {{ $lead->last_name }} @if($lead->company_name)({{ $lead->company_name }})@endif</option>
                        @endforeach
                    </optgroup>
                    <!-- Deals -->
                    <optgroup label="Deals" id="deals-group" style="display: none;">
                        @foreach($deals as $deal)
                            <option value="{{ $deal->id }}" data-type="App\Models\Deal" {{ old('related_id', $preSelected['related_id'] ?? '') == $deal->id ? 'selected' : '' }}>{{ $deal->deal_name }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('related_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_reminder" value="1" {{ old('is_reminder') ? 'checked' : '' }}>
                    Is Reminder
                </label>
                @error('is_reminder')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Reminder At </label>
                <input type="text" name="reminder_at" value="{{ old('reminder_at') }}" >
                @error('reminder_at')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 2px solid var(--gray-100);">
            <button type="submit" class="btn btn-primary">Create Tasks</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script>
// Dynamic filtering: Show/hide related items based on selected type
document.addEventListener('DOMContentLoaded', function() {
    const relatedTypeSelect = document.getElementById('related_type');
    const relatedIdSelect = document.getElementById('related_id');
    
    function updateRelatedOptions() {
        const selectedType = relatedTypeSelect.value;
        const allOptions = relatedIdSelect.querySelectorAll('option');
        const allGroups = relatedIdSelect.querySelectorAll('optgroup');
        
        // Hide all groups first
        allGroups.forEach(function(group) {
            group.style.display = 'none';
        });
        
        // Show relevant group based on selected type
        if (selectedType === 'App\Models\Account') {
            document.getElementById('accounts-group').style.display = 'block';
        } else if (selectedType === 'App\Models\Contact') {
            document.getElementById('contacts-group').style.display = 'block';
        } else if (selectedType === 'App\Models\Lead') {
            document.getElementById('leads-group').style.display = 'block';
        } else if (selectedType === 'App\Models\Deal') {
            document.getElementById('deals-group').style.display = 'block';
        }
        
        // Filter options to show only relevant ones
        allOptions.forEach(function(option) {
            if (option.value === '') {
                option.style.display = 'block';
            } else {
                const optionType = option.getAttribute('data-type');
                if (selectedType && optionType !== selectedType) {
                    option.style.display = 'none';
                } else if (!selectedType) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            }
        });
        
        // Reset selection if current selection doesn't match type
        if (selectedType && relatedIdSelect.value) {
            const selectedOption = relatedIdSelect.options[relatedIdSelect.selectedIndex];
            if (selectedOption.getAttribute('data-type') !== selectedType) {
                relatedIdSelect.value = '';
            }
        } else if (!selectedType) {
            relatedIdSelect.value = '';
        }
    }
    
    if (relatedTypeSelect && relatedIdSelect) {
        // Initial update
        updateRelatedOptions();
        
        // Update on change
        relatedTypeSelect.addEventListener('change', updateRelatedOptions);
    }
});
</script>
@endsection