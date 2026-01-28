@extends('layouts.app')

@section('title', 'View Invoices')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Invoices Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'invoices.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Invoice Number:</strong> {{ $invoice->invoice_number ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $invoice->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $invoice->contact->first_name ?? 'N/A' }} {{ $invoice->contact->last_name ?? '' }}</div>
        <div><strong>Deal:</strong> {{ $invoice->deal->deal_name ?? 'N/A' }}</div>
        <div><strong>Quotation:</strong> {{ $invoice->quotation->quotation_number ?? 'N/A' }}</div>
        <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Subtotal:</strong> ${{ number_format($invoice->subtotal ?? 0, 2) }}</div>
        <div><strong>Tax Amount:</strong> ${{ number_format($invoice->tax_amount ?? 0, 2) }}</div>
        <div><strong>Discount Amount:</strong> ${{ number_format($invoice->discount_amount ?? 0, 2) }}</div>
        <div><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount ?? 0, 2) }}</div>
        <div><strong>Amount Paid:</strong> ${{ number_format($invoice->amount_paid ?? 0, 2) }}</div>
        <div><strong>Balance:</strong> ${{ number_format($invoice->balance ?? 0, 2) }}</div>
        <div><strong>Currency:</strong> {{ $invoice->currency ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($invoice->status ?? 'N/A') }}</div>
        <div><strong>Created By:</strong> {{ $invoice->creator->name ?? 'System' }}</div>
        @if($invoice->terms_conditions)
        <div style="grid-column: 1 / -1;"><strong>Terms Conditions:</strong><br>{{ $invoice->terms_conditions }}</div>
        @endif
        @if($invoice->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $invoice->notes }}</div>
        @endif

        <div><strong>Created Date:</strong> {{ $invoice->created_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection