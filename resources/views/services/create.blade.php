@extends('layouts.app')

@section('title', 'Create Service')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Service</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'services.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('services.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Service Code *</label>
                <input type="text" name="service_code" value="{{ old('service_code') }}" required>
                @error('service_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Service Name *</label>
                <input type="text" name="service_name" value="{{ old('service_name') }}" required>
                @error('service_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="{{ old('category') }}">
                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Pricing Type *</label>
                <select name="pricing_type" required onchange="togglePricingFields()">
                    <option value="hourly" {{ old('pricing_type') == 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                    <option value="fixed" {{ old('pricing_type') == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                    <option value="custom" {{ old('pricing_type') == 'custom' ? 'selected' : '' }}>Custom Quote</option>
                </select>
                @error('pricing_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" id="hourly_rate_group">
                <label>Hourly Rate</label>
                <input type="number" name="hourly_rate" value="{{ old('hourly_rate') }}" step="0.01" min="0">
                @error('hourly_rate')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" id="fixed_price_group" style="display: none;">
                <label>Fixed Price</label>
                <input type="number" name="fixed_price" value="{{ old('fixed_price') }}" step="0.01" min="0">
                @error('fixed_price')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Estimated Hours</label>
                <input type="number" name="estimated_hours" value="{{ old('estimated_hours') }}" min="0">
                @error('estimated_hours')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency</label>
                <input type="text" name="currency" value="{{ old('currency', 'USD') }}" placeholder="USD">
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Service Details / Scope</label>
                <textarea name="service_details" rows="3">{{ old('service_details') }}</textarea>
                @error('service_details')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Service</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function togglePricingFields() {
    const type = document.querySelector('select[name="pricing_type"]').value;
    const hourlyGroup = document.getElementById('hourly_rate_group');
    const fixedGroup = document.getElementById('fixed_price_group');
    
    if (type === 'hourly') {
        hourlyGroup.style.display = 'block';
        fixedGroup.style.display = 'none';
        document.querySelector('input[name="fixed_price"]').value = '';
    } else if (type === 'fixed') {
        hourlyGroup.style.display = 'none';
        fixedGroup.style.display = 'block';
        document.querySelector('input[name="hourly_rate"]').value = '';
    } else {
        hourlyGroup.style.display = 'none';
        fixedGroup.style.display = 'none';
        document.querySelector('input[name="hourly_rate"]').value = '';
        document.querySelector('input[name="fixed_price"]').value = '';
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', togglePricingFields);
</script>
@endsection
