@extends('layouts.app')

@section('title', 'View Expense')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Expense Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'expenses.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Expense Number:</strong> {{ $expense->expense_number }}</div>
        <div><strong>Expense Name:</strong> {{ $expense->expense_name }}</div>
        <div><strong>Category:</strong> {{ $expense->category }}</div>
        <div><strong>Expense Date:</strong> {{ $expense->expense_date->format('Y-m-d') }}</div>
        <div><strong>Amount:</strong> {{ $expense->currency }} {{ number_format($expense->amount, 2) }}</div>
        <div><strong>Status:</strong> 
            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: 
                @if($expense->status == 'paid') #4caf50
                @elseif($expense->status == 'approved') #2196f3
                @elseif($expense->status == 'pending') #ff9800
                @else #f44336
                @endif; color: white;">
                {{ ucfirst($expense->status) }}
            </span>
        </div>
        <div><strong>Payment Method:</strong> {{ $expense->payment_method ?? '-' }}</div>
        
        <div><strong>Vendor:</strong> 
            @if($expense->vendor)
                <a href="{{ route('accounts.show', $expense->vendor) }}">{{ $expense->vendor->account_name }}</a>
            @else
                -
            @endif
        </div>
        
        <div><strong>Related Payment:</strong> 
            @if($expense->payment)
                <a href="{{ route('payments.show', $expense->payment) }}">{{ $expense->payment->payment_number }}</a>
            @else
                -
            @endif
        </div>
        
        @if($expense->receipt_path)
        <div><strong>Receipt:</strong> <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank">View Receipt</a></div>
        @endif
        
        <div><strong>Created By:</strong> {{ $expense->creator->name ?? 'System' }}</div>
        <div><strong>Created Date:</strong> {{ $expense->created_at->format('Y-m-d H:i:s') }}</div>
        
        @if($expense->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $expense->notes }}</div>
        @endif
        @if($expense->description)
        <div style="grid-column: 1 / -1;"><strong>Description:</strong><br>{{ $expense->description }}</div>
        @endif
    </div>
</div>
@endsection
