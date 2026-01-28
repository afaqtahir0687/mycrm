@extends('layouts.app')

@section('title', 'View Product')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Product Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'products.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Product Code:</strong> {{ $product->product_code }}</div>
        <div><strong>Product Name:</strong> {{ $product->product_name }}</div>
        <div><strong>Category:</strong> {{ $product->category }}</div>
        <div><strong>Unit Price:</strong> {{ $product->currency }} {{ number_format($product->unit_price, 2) }}</div>
        <div><strong>Stock Quantity:</strong> {{ $product->stock_quantity }} {{ $product->unit }}</div>
        <div><strong>Status:</strong> 
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                @if($product->status == 'active') #4caf50
                @else #f44336
                @endif; color: white;">
                {{ ucfirst($product->status) }}
            </span>
        </div>
        <div><strong>Created By:</strong> {{ $product->creator->name ?? 'System' }}</div>
        <div><strong>Created Date:</strong> {{ $product->created_at->format('Y-m-d H:i:s') }}</div>
        
        @if($product->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $product->description }}</div>
        @endif
        
        @if($product->specifications)
        <div style="grid-column: 1 / -1;"><strong>Specifications:</strong><br>{{ $product->specifications }}</div>
        @endif
    </div>
</div>
@endsection
