@extends('layouts.app')

@section('title', 'Edit Invoices')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Invoices</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'invoices.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('invoices.update', $invoices) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Invoice Number *</label>
                <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoices->invoice_number) }}" required>
                @error('invoice_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Id</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $invoice->account_id) == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
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
                        <option value="{{ $contact->id }}" {{ old('contact_id', $invoice->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
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
                        <option value="{{ $deal->id }}" {{ old('deal_id', $invoices->deal_id) == $deal->id ? 'selected' : '' }}>{{ $deal->name ?? $deal->account_name ?? $deal->first_name }}</option>
                    @endforeach
                </select>
                @error('deal_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Quotation Id</label>
                <select name="quotation_id" class="form-control">
                    <option value="">Select Quotation</option>
                    @foreach($quotations as $quotation)
                        <option value="{{ $quotation->id }}" {{ old('quotation_id', $invoices->quotation_id) == $quotation->id ? 'selected' : '' }}>{{ $quotation->name ?? $quotation->account_name ?? $quotation->first_name }}</option>
                    @endforeach
                </select>
                @error('quotation_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Invoice Date </label>
                <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoices->invoice_date?->format('Y-m-d')) }}" >
                @error('invoice_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Due Date </label>
                <input type="date" name="due_date" value="{{ old('due_date', $invoices->due_date?->format('Y-m-d')) }}" >
                @error('due_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Subtotal </label>
                <input type="text" name="subtotal" value="{{ old('subtotal', $invoices->subtotal) }}" >
                @error('subtotal')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tax Amount </label>
                <input type="number" name="tax_amount" value="{{ old('tax_amount', $invoices->tax_amount) }}" >
                @error('tax_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Discount Amount </label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount', $invoices->discount_amount) }}" >
                @error('discount_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Total Amount </label>
                <input type="number" name="total_amount" value="{{ old('total_amount', $invoices->total_amount) }}" >
                @error('total_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Amount Paid </label>
                <input type="number" name="amount_paid" value="{{ old('amount_paid', $invoices->amount_paid) }}" >
                @error('amount_paid')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Balance </label>
                <input type="text" name="balance" value="{{ old('balance', $invoices->balance) }}" >
                @error('balance')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency </label>
                <select name="currency" >
                    <option value="USD" {{ old('currency', $invoices->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency', $invoices->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency', $invoices->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="JPY" {{ old('currency', $invoices->currency) == 'JPY' ? 'selected' : '' }}>JPY</option>
                </select>
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status </label>
                <select name="status" >
                    <option value="active" {{ old('status', $invoices->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $invoices->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="new" {{ old('status', $invoices->status) == 'new' ? 'selected' : '' }}>New</option>
                    <option value="open" {{ old('status', $invoices->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $invoices->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status', $invoices->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $invoices->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status', $invoices->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="won" {{ old('status', $invoices->status) == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status', $invoices->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Terms Conditions </label>
                <textarea name="terms_conditions" rows="4">{{ old('terms_conditions', $invoices->terms_conditions) }}</textarea>
                @error('terms_conditions')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes </label>
                <textarea name="notes" rows="4">{{ old('notes', $invoices->notes) }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Invoices</button>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection