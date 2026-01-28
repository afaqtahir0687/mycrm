@extends('layouts.app')

@section('title', 'View Payment')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Payment Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'payments.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Payment Number:</strong> {{ $payment->payment_number }}</div>
        <div><strong>Payment Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}</div>
        <div><strong>Amount:</strong> {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</div>
        <div><strong>Status:</strong> 
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                @if($payment->status == 'received') #4caf50
                @elseif($payment->status == 'pending') #ff9800
                @elseif($payment->status == 'failed') #f44336
                @else #757575
                @endif; color: white;">
                {{ ucfirst($payment->status) }}
            </span>
        </div>
        <div><strong>Payment Method:</strong> {{ $payment->payment_method ?? '-' }}</div>
        <div><strong>Reference Number:</strong> {{ $payment->reference_number ?? '-' }}</div>
        
        <div><strong>Related Invoice:</strong> 
            @if($payment->invoice)
                <a href="{{ route('invoices.show', $payment->invoice) }}">{{ $payment->invoice->invoice_number }}</a>
            @else
                -
            @endif
        </div>
        
        <div><strong>Account:</strong> 
            @if($payment->account)
                <a href="{{ route('accounts.show', $payment->account) }}">{{ $payment->account->account_name }}</a>
            @else
                -
            @endif
        </div>
        
        <div><strong>Contact:</strong> 
            @if($payment->contact)
                <a href="{{ route('contacts.show', $payment->contact) }}">{{ $payment->contact->first_name }} {{ $payment->contact->last_name }}</a>
            @else
                -
            @endif
        </div>
        
        @if($payment->bank_name || $payment->cheque_number)
            <div><strong>Bank Details:</strong> {{ $payment->bank_name }} (Cheque: {{ $payment->cheque_number }} - {{ $payment->cheque_date ? $payment->cheque_date->format('Y-m-d') : '' }})</div>
        @endif
        
        <div><strong>Created By:</strong> {{ $payment->creator->name ?? 'System' }}</div>
        <div><strong>Created Date:</strong> {{ $payment->created_at->format('Y-m-d H:i:s') }}</div>
        
        @if($payment->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $payment->notes }}</div>
        @endif
    </div>
</div>
@endsection
