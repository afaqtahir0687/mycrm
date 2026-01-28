@extends('layouts.app')

@section('title', 'Sales Pipeline Overview')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Sales Pipeline Overview</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'sales-pipeline.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('deals.index') }}" class="btn btn-primary">View Deals</a>
            <a href="{{ route('opportunities.index') }}" class="btn btn-primary">View Opportunities</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Deals</h3>
                <div><strong>Total:</strong> {{ $summary['total_deals'] }}</div>
                <div><strong>Open:</strong> {{ $summary['open_deals'] }}</div>
                <div><strong>Won:</strong> {{ $summary['won_deals'] }}</div>
                <div><strong>Total Value:</strong> ${{ number_format($summary['total_deal_value'], 2) }}</div>
            </div>
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Opportunities</h3>
                <div><strong>Total:</strong> {{ $summary['total_opportunities'] }}</div>
                <div><strong>Open:</strong> {{ $summary['open_opportunities'] }}</div>
                <div><strong>Won:</strong> {{ $summary['won_opportunities'] }}</div>
                <div><strong>Total Value:</strong> ${{ number_format($summary['total_opportunity_value'], 2) }}</div>
            </div>
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Quotations</h3>
                <div><strong>Total:</strong> {{ $summary['total_quotations'] }}</div>
                <div><strong>Sent:</strong> {{ $summary['sent_quotations'] }}</div>
                <div><strong>Accepted:</strong> {{ $summary['accepted_quotations'] }}</div>
                <div><strong>Total Value:</strong> ${{ number_format($summary['total_quotation_value'], 2) }}</div>
            </div>
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Invoices</h3>
                <div><strong>Total:</strong> {{ $summary['total_invoices'] }}</div>
                <div><strong>Paid:</strong> {{ $summary['paid_invoices'] }}</div>
                <div><strong>Overdue:</strong> {{ $summary['overdue_invoices'] }}</div>
                <div><strong>Outstanding:</strong> ${{ number_format($summary['outstanding_invoices'], 2) }}</div>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 30px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('deals.index') }}" class="btn btn-primary" style="text-align: left;">Manage Deals</a>
                <a href="{{ route('deals.create') }}" class="btn btn-success" style="text-align: left;">Create New Deal</a>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="text-align: left;">Manage Opportunities</a>
                <a href="{{ route('opportunities.create') }}" class="btn btn-success" style="text-align: left;">Create New Opportunity</a>
                <a href="{{ route('quotations.index') }}" class="btn btn-primary" style="text-align: left;">Manage Quotations</a>
                <a href="{{ route('quotations.create') }}" class="btn btn-success" style="text-align: left;">Create New Quotation</a>
                <a href="{{ route('invoices.index') }}" class="btn btn-primary" style="text-align: left;">Manage Invoices</a>
                <a href="{{ route('invoices.create') }}" class="btn btn-success" style="text-align: left;">Create New Invoice</a>
            </div>
        </div>
        
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Sales Pipeline Information</h2>
            <p style="line-height: 1.8; color: #666;">
                The Sales Pipeline module tracks your entire sales process from initial opportunities through closed deals.
                Deals represent active sales engagements with specific values and stages. Opportunities are potential sales
                that can be converted to deals. Quotations are formal price proposals sent to customers, and Invoices are
                generated when deals are won and payments are due.
            </p>
        </div>
    </div>
</div>
@endsection

