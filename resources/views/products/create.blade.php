@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Product</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'products.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Product Code *</label>
                <input type="text" name="product_code" value="{{ old('product_code') }}" required>
                @error('product_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" required>
                @error('product_name')
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
                <label>Unit Price *</label>
                <input type="number" name="unit_price" value="{{ old('unit_price') }}" step="0.01" min="0" required>
                @error('unit_price')
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
                <label>Unit</label>
                <input type="text" name="unit" value="{{ old('unit') }}" placeholder="e.g., pcs, kg, box">
                @error('unit')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0">
                @error('stock_quantity')
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
                <label>Specifications</label>
                <textarea name="specifications" rows="3">{{ old('specifications') }}</textarea>
                @error('specifications')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
