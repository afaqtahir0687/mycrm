@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Account</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'accounts.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('accounts.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Account Name *</label>
                <input type="text" name="account_name" value="{{ old('account_name') }}" required>
                @error('account_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account Type</label>
                <input type="text" name="account_type" value="{{ old('account_type') }}" placeholder="e.g., Customer, Partner">
                @error('account_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry" value="{{ old('industry') }}">
                @error('industry')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" value="{{ old('website') }}">
                @error('website')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Billing Address</label>
                <textarea name="billing_address" rows="2">{{ old('billing_address') }}</textarea>
                @error('billing_address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Billing City</label>
                <input type="text" name="billing_city" value="{{ old('billing_city') }}">
                @error('billing_city')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Billing State</label>
                <input type="text" name="billing_state" value="{{ old('billing_state') }}">
                @error('billing_state')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Billing Country</label>
                <input type="text" name="billing_country" value="{{ old('billing_country') }}">
                @error('billing_country')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Billing Postal Code</label>
                <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}">
                @error('billing_postal_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Employees</label>
                <input type="number" name="employees" value="{{ old('employees') }}" min="0">
                @error('employees')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Annual Revenue</label>
                <input type="number" name="annual_revenue" value="{{ old('annual_revenue') }}" step="0.01" min="0">
                @error('annual_revenue')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Owner</label>
                <select name="owner_id" class="form-control">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('owner_id', $preSelected['owner_id'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('owner_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

