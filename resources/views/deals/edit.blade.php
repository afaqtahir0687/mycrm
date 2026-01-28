@extends('layouts.app')

@section('title', 'Edit Deals')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Deals</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'deals.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('deals.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('deals.update', $deals) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Deal Name *</label>
                <input type="text" name="deal_name" value="{{ old('deal_name', $deals->deal_name) }}" required>
                @error('deal_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Id</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $deal->account_id) == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
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
                        <option value="{{ $contact->id }}" {{ old('contact_id', $deal->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
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
                        <option value="{{ $lead->id }}" {{ old('lead_id', $deal->lead_id) == $lead->id ? 'selected' : '' }}>{{ $lead->first_name }} {{ $lead->last_name }}</option>
                    @endforeach
                </select>
                @error('lead_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Amount </label>
                <input type="number" name="amount" value="{{ old('amount', $deals->amount) }}" >
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency </label>
                <select name="currency" >
                    <option value="USD" {{ old('currency', $deals->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency', $deals->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency', $deals->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="JPY" {{ old('currency', $deals->currency) == 'JPY' ? 'selected' : '' }}>JPY</option>
                </select>
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Expected Close Date </label>
                <input type="date" name="expected_close_date" value="{{ old('expected_close_date', $deals->expected_close_date?->format('Y-m-d')) }}" >
                @error('expected_close_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Stage </label>
                <select name="stage" >
                    <option value="prospecting" {{ old('stage', $deals->stage) == 'prospecting' ? 'selected' : '' }}>Prospecting</option>
                    <option value="qualification" {{ old('stage', $deals->stage) == 'qualification' ? 'selected' : '' }}>Qualification</option>
                    <option value="proposal" {{ old('stage', $deals->stage) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                    <option value="negotiation" {{ old('stage', $deals->stage) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="closed_won" {{ old('stage', $deals->stage) == 'closed_won' ? 'selected' : '' }}>Closed won</option>
                    <option value="closed_lost" {{ old('stage', $deals->stage) == 'closed_lost' ? 'selected' : '' }}>Closed lost</option>
                </select>
                @error('stage')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Probability </label>
                <input type="number" name="probability" value="{{ old('probability', $deals->probability) }}" >
                @error('probability')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description </label>
                <textarea name="description" rows="4">{{ old('description', $deals->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Owner Id</label>
                <select name="owner_id" class="form-control">
                    <option value="">Select Owner</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ old('owner_id', $deals->owner_id) == $owner->id ? 'selected' : '' }}>{{ $owner->name ?? $owner->account_name ?? $owner->first_name }}</option>
                    @endforeach
                </select>
                @error('owner_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status </label>
                <select name="status" >
                    <option value="active" {{ old('status', $deals->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $deals->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="new" {{ old('status', $deals->status) == 'new' ? 'selected' : '' }}>New</option>
                    <option value="open" {{ old('status', $deals->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $deals->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status', $deals->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $deals->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status', $deals->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="won" {{ old('status', $deals->status) == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status', $deals->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Deals</button>
            <a href="{{ route('deals.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection