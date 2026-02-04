@extends('layouts.app')

@section('title', 'Agreement Details')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Agreement Details</h1>
        <a href="{{ route('agreements.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Agreement #:</strong> {{ $agreement->agreement_number }}</div>
        <div><strong>Type:</strong> {{ $agreement->agreement_type }}</div>
        <div><strong>Status:</strong> {{ ucfirst($agreement->status) }}</div>
        <div><strong>Agreement Date:</strong> {{ $agreement->agreement_date?->format('Y-m-d') ?? 'N/A' }}</div>
        <div><strong>Start Date:</strong> {{ $agreement->start_date?->format('Y-m-d') ?? 'N/A' }}</div>
        <div><strong>End Date:</strong> {{ $agreement->end_date?->format('Y-m-d') ?? 'N/A' }}</div>
        <div><strong>Account:</strong> {{ $agreement->account->account_name ?? 'N/A' }}</div>
        <div><strong>Contact:</strong> {{ $agreement->contact->first_name ?? 'N/A' }} {{ $agreement->contact->last_name ?? '' }}</div>
        <div><strong>Quotation:</strong> {{ $agreement->quotation->quotation_number ?? 'N/A' }}</div>
        <div><strong>Deal:</strong> {{ $agreement->deal->deal_name ?? 'N/A' }}</div>
        <div><strong>Total Value:</strong> {{ $agreement->currency ?? 'USD' }} {{ number_format($agreement->total_value ?? 0, 2) }}</div>
        <div><strong>Created By:</strong> {{ $agreement->creator->name ?? 'System' }}</div>
    </div>

    <div style="margin-top: 20px;">
        <div style="margin-bottom: 12px;"><strong>Terms & Conditions</strong></div>
        <div style="background: #f9f9f9; padding: 12px; border-radius: 4px;">{{ $agreement->terms_conditions ?? 'N/A' }}</div>
    </div>

    <div style="margin-top: 20px;">
        <div style="margin-bottom: 12px;"><strong>SLA Terms</strong></div>
        <div style="background: #f9f9f9; padding: 12px; border-radius: 4px;">{{ $agreement->sla_terms ?? 'N/A' }}</div>
    </div>

    <div style="margin-top: 20px;">
        <div style="margin-bottom: 12px;"><strong>Deliverables</strong></div>
        <div style="background: #f9f9f9; padding: 12px; border-radius: 4px;">{{ $agreement->deliverables ?? 'N/A' }}</div>
    </div>

    <div style="margin-top: 20px;">
        <div style="margin-bottom: 12px;"><strong>Notes</strong></div>
        <div style="background: #f9f9f9; padding: 12px; border-radius: 4px;">{{ $agreement->notes ?? 'N/A' }}</div>
    </div>

    <div style="margin-top: 20px;">
        <strong>Agreement File:</strong>
        @if($agreement->agreement_file_path)
            <a href="{{ asset('storage/' . $agreement->agreement_file_path) }}" target="_blank">
                {{ basename($agreement->agreement_file_path) }}
            </a>
        @else
            N/A
        @endif
    </div>
</div>
@endsection


