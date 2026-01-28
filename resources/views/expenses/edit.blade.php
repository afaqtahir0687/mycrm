@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Expense</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'expenses.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Expense Number *</label>
                <input type="text" name="expense_number" value="{{ old('expense_number', $expense->expense_number) }}" required>
                @error('expense_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Expense Name *</label>
                <input type="text" name="expense_name" value="{{ old('expense_name', $expense->expense_name) }}" required>
                @error('expense_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="{{ old('category', $expense->category) }}">
                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Expense Date *</label>
                <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                @error('expense_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Vendor</label>
                <select name="vendor_id">
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $expense->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->account_name }}</option>
                    @endforeach
                </select>
                @error('vendor_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Related Payment</label>
                <select name="payment_id">
                    <option value="">Select Payment</option>
                    @foreach($payments as $payment)
                        <option value="{{ $payment->id }}" {{ old('payment_id', $expense->payment_id) == $payment->id ? 'selected' : '' }}>{{ $payment->payment_number }} ({{ $payment->amount }})</option>
                    @endforeach
                </select>
                @error('payment_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Amount *</label>
                <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" required>
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $expense->currency) }}">
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="pending" {{ old('status', $expense->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $expense->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ old('status', $expense->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ old('status', $expense->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <input type="text" name="payment_method" value="{{ old('payment_method', $expense->payment_method) }}">
                @error('payment_method')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Receipt Upload</label>
                <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png">
                @if($expense->receipt_path)
                    <div style="margin-top: 5px;">
                        <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank">Current Receipt</a>
                    </div>
                @endif
                @error('receipt')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Expense</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
