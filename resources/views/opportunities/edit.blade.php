@extends('layouts.app')

@section('title', 'Edit Opportunities')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Opportunities</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'opportunities.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('opportunities.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('opportunities.update', $opportunity) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Opportunity Name *</label>
                <input type="text" name="opportunity_name" value="{{ old('opportunity_name', $opportunity->opportunity_name) }}" required>
                @error('opportunity_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Id</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $opportunity->account_id) == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Contact Id</label>
                <select name="contact_id" class="form-control">
                    <option value="">Select Contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id', $opportunity->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                    @endforeach
                </select>
                @error('contact_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Amount </label>
                <input type="number" name="amount" value="{{ old('amount', $opportunity->amount) }}" >
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency </label>
                <select name="currency" >
                    <option value="USD" {{ old('currency', $opportunity->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency', $opportunity->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency', $opportunity->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="JPY" {{ old('currency', $opportunity->currency) == 'JPY' ? 'selected' : '' }}>JPY</option>
                </select>
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Close Date </label>
                <input type="date" name="close_date" value="{{ old('close_date', $opportunity->close_date?->format('Y-m-d')) }}" >
                @error('close_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Stage </label>
                <select name="stage" >
                    <option value="prospecting" {{ old('stage', $opportunity->stage) == 'prospecting' ? 'selected' : '' }}>Prospecting</option>
                    <option value="qualification" {{ old('stage', $opportunity->stage) == 'qualification' ? 'selected' : '' }}>Qualification</option>
                    <option value="proposal" {{ old('stage', $opportunity->stage) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                    <option value="negotiation" {{ old('stage', $opportunity->stage) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="closed_won" {{ old('stage', $opportunity->stage) == 'closed_won' ? 'selected' : '' }}>Closed won</option>
                    <option value="closed_lost" {{ old('stage', $opportunity->stage) == 'closed_lost' ? 'selected' : '' }}>Closed lost</option>
                </select>
                @error('stage')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Probability </label>
                <input type="number" name="probability" value="{{ old('probability', $opportunity->probability) }}" >
                @error('probability')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Type </label>
                <select name="type" >
                    <option value="email" {{ old('type', $opportunity->type) == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="phone" {{ old('type', $opportunity->type) == 'phone' ? 'selected' : '' }}>Phone</option>
                    <option value="sms" {{ old('type', $opportunity->type) == 'sms' ? 'selected' : '' }}>Sms</option>
                    <option value="whatsapp" {{ old('type', $opportunity->type) == 'whatsapp' ? 'selected' : '' }}>Whatsapp</option>
                    <option value="meeting" {{ old('type', $opportunity->type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="note" {{ old('type', $opportunity->type) == 'note' ? 'selected' : '' }}>Note</option>
                    <option value="question" {{ old('type', $opportunity->type) == 'question' ? 'selected' : '' }}>Question</option>
                    <option value="problem" {{ old('type', $opportunity->type) == 'problem' ? 'selected' : '' }}>Problem</option>
                    <option value="feature_request" {{ old('type', $opportunity->type) == 'feature_request' ? 'selected' : '' }}>Feature request</option>
                    <option value="complaint" {{ old('type', $opportunity->type) == 'complaint' ? 'selected' : '' }}>Complaint</option>
                </select>
                @error('type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description </label>
                <textarea name="description" rows="4">{{ old('description', $opportunity->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Owner</label>
                <select name="owner_id" class="form-control">
                    <option value="">Select Owner</option>
                    @foreach($users as $owner)
                        <option value="{{ $owner->id }}" {{ old('owner_id', $opportunity->owner_id) == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                    @endforeach
                </select>
                @error('owner_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status </label>
                <select name="status" >
                    <option value="open" {{ old('status', $opportunity->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="won" {{ old('status', $opportunity->status) == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status', $opportunity->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Opportunities</button>
            <a href="{{ route('opportunities.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection