@extends('layouts.app')

@section('title', 'View Quotations')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Quotations Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'quotations.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('quotations.print', $quotation) }}" target="_blank" class="btn btn-primary">Print Quotation</a>
            <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Quotation Number:</strong> {{ $quotation->quotation_number ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $quotation->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $quotation->contact->first_name ?? 'N/A' }} {{ $quotation->contact->last_name ?? '' }}</div>
        <div><strong>Deal:</strong> {{ $quotation->deal->deal_name ?? 'N/A' }}</div>
        <div><strong>Quotation Date:</strong> {{ $quotation->quotation_date ? $quotation->quotation_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Valid Until:</strong> {{ $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Subtotal:</strong> ${{ number_format($quotation->subtotal ?? 0, 2) }}</div>
        <div><strong>Tax Amount:</strong> ${{ number_format($quotation->tax_amount ?? 0, 2) }}</div>
        <div><strong>Discount Amount:</strong> ${{ number_format($quotation->discount_amount ?? 0, 2) }}</div>
        <div><strong>Total Amount:</strong> ${{ number_format($quotation->total_amount ?? 0, 2) }}</div>
        <div><strong>Currency:</strong> {{ $quotation->currency ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($quotation->status ?? 'N/A') }}</div>
        <div><strong>Created By:</strong> {{ $quotation->creator->name ?? 'System' }}</div>
        @if($quotation->terms_conditions)
        <div style="grid-column: 1 / -1;"><strong>Terms Conditions:</strong><br>{{ $quotation->terms_conditions }}</div>
        @endif
        @if($quotation->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $quotation->notes }}</div>
        @endif
        @if($quotation->agreement_template_path)
        <div style="grid-column: 1 / -1;">
            <strong>Agreement Template:</strong><br>
            <a href="{{ asset('storage/' . $quotation->agreement_template_path) }}" target="_blank" class="btn btn-primary" style="margin-top: 8px; display: inline-block;">
                View Agreement Template
            </a>
            <span style="color: var(--ms-gray-80, #8a8886); font-size: 12px; margin-left: 10px;">{{ basename($quotation->agreement_template_path) }}</span>
        </div>
        @endif

        <div><strong>Created Date:</strong> {{ $quotation->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection