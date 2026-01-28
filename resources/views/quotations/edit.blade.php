@extends('layouts.app')

@section('title', 'Edit Quotations')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Quotations</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'quotations.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('quotations.update', $quotation) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Quotation Number *</label>
                <input type="text" name="quotation_number" value="{{ old('quotation_number', $quotation->quotation_number) }}" required>
                @error('quotation_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Id</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $quotation->account_id) == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
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
                        <option value="{{ $contact->id }}" {{ old('contact_id', $quotation->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                    @endforeach
                </select>
                @error('contact_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Deal Id</label>
                <select name="deal_id" class="form-control">
                    <option value="">Select Deal</option>
                    @foreach($deals as $deal)
                        <option value="{{ $deal->id }}" {{ old('deal_id', $quotation->deal_id) == $deal->id ? 'selected' : '' }}>{{ $deal->deal_name ?? $deal->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
                @error('deal_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Quotation Date </label>
                <input type="date" name="quotation_date" value="{{ old('quotation_date', $quotation->quotation_date?->format('Y-m-d')) }}" >
                @error('quotation_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Valid Until </label>
                <input type="date" name="valid_until" value="{{ old('valid_until', $quotation->valid_until ? (is_string($quotation->valid_until) ? $quotation->valid_until : $quotation->valid_until->format('Y-m-d')) : '') }}" >
                @error('valid_until')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Subtotal </label>
                <input type="number" step="0.01" name="subtotal" value="{{ old('subtotal', $quotation->subtotal) }}" >
                @error('subtotal')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tax Amount </label>
                <input type="number" step="0.01" name="tax_amount" value="{{ old('tax_amount', $quotation->tax_amount) }}" >
                @error('tax_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Discount Amount </label>
                <input type="number" step="0.01" name="discount_amount" value="{{ old('discount_amount', $quotation->discount_amount) }}" >
                @error('discount_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Total Amount </label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $quotation->total_amount) }}" >
                @error('total_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency </label>
                <select name="currency" >
                    <option value="USD" {{ old('currency', $quotation->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency', $quotation->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency', $quotation->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="JPY" {{ old('currency', $quotation->currency) == 'JPY' ? 'selected' : '' }}>JPY</option>
                </select>
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="draft" {{ old('status', $quotation->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $quotation->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="accepted" {{ old('status', $quotation->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ old('status', $quotation->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ old('status', $quotation->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Terms Conditions </label>
                <textarea name="terms_conditions" rows="4">{{ old('terms_conditions', $quotation->terms_conditions) }}</textarea>
                @error('terms_conditions')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes </label>
                <textarea name="notes" rows="4">{{ old('notes', $quotation->notes) }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Agreement Template (PDF, DOC, DOCX)</label>
                @if($quotation->agreement_template_path)
                    <div style="margin-bottom: 10px; padding: 10px; background: var(--ms-gray-20, #f3f2f1); border-radius: 2px;">
                        <strong>Current File:</strong> 
                        <a href="{{ asset('storage/' . $quotation->agreement_template_path) }}" target="_blank" style="color: var(--ms-blue, #0078d4); text-decoration: none;">
                            {{ basename($quotation->agreement_template_path) }}
                        </a>
                        <span style="color: var(--ms-gray-80, #8a8886); font-size: 12px;"> (Click to view)</span>
                    </div>
                @endif
                <input type="file" name="agreement_template" accept=".pdf,.doc,.docx" class="form-control">
                <small style="color: #666; font-size: 12px;">Upload new agreement template to replace existing (Max 10MB)</small>
                @error('agreement_template')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Quotations</button>
            <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection