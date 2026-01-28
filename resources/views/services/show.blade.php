@extends('layouts.app')

@section('title', 'View Service')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Service Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'services.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('services.edit', $service) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Service Code:</strong> {{ $service->service_code }}</div>
        <div><strong>Service Name:</strong> {{ $service->service_name }}</div>
        <div><strong>Category:</strong> {{ $service->category }}</div>
        <div><strong>Pricing Type:</strong> {{ ucfirst($service->pricing_type) }}</div>
        <div><strong>Price/Rate:</strong> 
            @if($service->pricing_type == 'hourly')
                {{ $service->currency }} {{ number_format($service->hourly_rate, 2) }}/hr
            @elseif($service->pricing_type == 'fixed')
                {{ $service->currency }} {{ number_format($service->fixed_price, 2) }}
            @else
                Custom Quote
            @endif
        </div>
        <div><strong>Estimated Hours:</strong> {{ $service->estimated_hours }}</div>
        <div><strong>Status:</strong> 
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                @if($service->status == 'active') #4caf50
                @else #f44336
                @endif; color: white;">
                {{ ucfirst($service->status) }}
            </span>
        </div>
        <div><strong>Created By:</strong> {{ $service->creator->name ?? 'System' }}</div>
        <div><strong>Created Date:</strong> {{ $service->created_at->format('Y-m-d H:i:s') }}</div>
        
        @if($service->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $service->description }}</div>
        @endif
        
        @if($service->service_details)
        <div style="grid-column: 1 / -1;"><strong>Service Details / Scope:</strong><br>{{ $service->service_details }}</div>
        @endif
    </div>
</div>
@endsection
