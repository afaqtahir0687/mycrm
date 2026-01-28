@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Payment</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'payments.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('payments.update', $payment) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Payment Number *</label>
                <input type="text" name="payment_number" value="{{ old('payment_number', $payment->payment_number) }}" required>
                @error('payment_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Payment Date *</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                @error('payment_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Invoice</label>
                <select name="invoice_id">
                    <option value="">Select Invoice</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" {{ old('invoice_id', $payment->invoice_id) == $invoice->id ? 'selected' : '' }}>{{ $invoice->invoice_number }}</option>
                    @endforeach
                </select>
                @error('invoice_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account (Received From)</label>
                <select name="account_id">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $payment->account_id) == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Contact</label>
                <select name="contact_id">
                    <option value="">Select Contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id', $payment->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->first_name }} {{ $contact->last_name }}</option>
                    @endforeach
                </select>
                @error('contact_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Amount *</label>
                <input type="number" name="amount" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required>
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $payment->currency) }}">
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <input type="text" name="payment_method" value="{{ old('payment_method', $payment->payment_method) }}">
                @error('payment_method')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Reference Number</label>
                <input type="text" name="reference_number" value="{{ old('reference_number', $payment->reference_number) }}">
                @error('reference_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="received" {{ old('status', $payment->status) == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $payment->bank_name) }}">
                @error('bank_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Cheque Number</label>
                <input type="text" name="cheque_number" value="{{ old('cheque_number', $payment->cheque_number) }}">
                @error('cheque_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Cheque Date</label>
                <input type="date" name="cheque_date" value="{{ old('cheque_date', $payment->cheque_date ? $payment->cheque_date->format('Y-m-d') : '') }}">
                @error('cheque_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Payment</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
