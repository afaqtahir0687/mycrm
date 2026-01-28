@extends('layouts.app')

@section('title', 'Create Communications')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Communications</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'communications.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('communications.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('communications.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Type </label>
                <select name="type" >
                    <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Phone</option>
                    <option value="sms" {{ old('type') == 'sms' ? 'selected' : '' }}>Sms</option>
                    <option value="whatsapp" {{ old('type') == 'whatsapp' ? 'selected' : '' }}>Whatsapp</option>
                    <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="note" {{ old('type') == 'note' ? 'selected' : '' }}>Note</option>
                    <option value="question" {{ old('type') == 'question' ? 'selected' : '' }}>Question</option>
                    <option value="problem" {{ old('type') == 'problem' ? 'selected' : '' }}>Problem</option>
                    <option value="feature_request" {{ old('type') == 'feature_request' ? 'selected' : '' }}>Feature request</option>
                    <option value="complaint" {{ old('type') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                </select>
                @error('type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required>
                @error('subject')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Content </label>
                <textarea name="content" rows="4">{{ old('content') }}</textarea>
                @error('content')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Direction </label>
                <select name="direction" >
                    <option value="inbound" {{ old('direction') == 'inbound' ? 'selected' : '' }}>Inbound</option>
                    <option value="outbound" {{ old('direction') == 'outbound' ? 'selected' : '' }}>Outbound</option>
                </select>
                @error('direction')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>From Email </label>
                <input type="email" name="from_email" value="{{ old('from_email') }}" >
                @error('from_email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>To Email </label>
                <input type="email" name="to_email" value="{{ old('to_email') }}" >
                @error('to_email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>From Phone </label>
                <input type="text" name="from_phone" value="{{ old('from_phone') }}" >
                @error('from_phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>To Phone </label>
                <input type="text" name="to_phone" value="{{ old('to_phone') }}" >
                @error('to_phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Id</label>
                <select name="account_id" class="form-control" id="account_id">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $preSelected['account_id'] ?? '') == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Contact Id</label>
                <select name="contact_id" class="form-control" id="contact_id">
                    <option value="">Select Contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" data-account-id="{{ $contact->account_id }}" {{ old('contact_id', $preSelected['contact_id'] ?? '') == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                    @endforeach
                </select>
                @error('contact_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Lead Id</label>
                <select name="lead_id" class="form-control">
                    <option value="">Select Lead</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead->id }}" {{ old('lead_id', $preSelected['lead_id'] ?? '') == $lead->id ? 'selected' : '' }}>{{ $lead->first_name }} {{ $lead->last_name }} @if($lead->company_name)({{ $lead->company_name }})@endif</option>
                    @endforeach
                </select>
                @error('lead_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Duration Minutes </label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" >
                @error('duration_minutes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status </label>
                <select name="status" >
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="won" {{ old('status') == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Communications</button>
            <a href="{{ route('communications.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script>
// Dynamic filtering: Filter contacts based on selected account
document.addEventListener('DOMContentLoaded', function() {
    const accountSelect = document.getElementById('account_id');
    const contactSelect = document.getElementById('contact_id');
    
    if (accountSelect && contactSelect) {
        accountSelect.addEventListener('change', function() {
            const selectedAccountId = this.value;
            const contactOptions = contactSelect.querySelectorAll('option');
            
            contactOptions.forEach(function(option) {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const accountId = option.getAttribute('data-account-id');
                    if (selectedAccountId && accountId !== selectedAccountId) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                }
            });
            
            // Reset contact selection if filtered out
            if (selectedAccountId && contactSelect.value) {
                const selectedOption = contactSelect.options[contactSelect.selectedIndex];
                if (selectedOption.getAttribute('data-account-id') !== selectedAccountId) {
                    contactSelect.value = '';
                }
            }
        });
    }
});
</script>
@endsection