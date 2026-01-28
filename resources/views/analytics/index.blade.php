@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Analytics Dashboard</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'analytics.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('analytics.export') }}" class="btn btn-success">Export Analytics</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Sales Analytics -->
        <div class="content-card" style="background: #f9f9f9;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Sales Analytics</h2>
            <div style="line-height: 2;">
                <div><strong>Total Deals:</strong> {{ $analytics['sales']['total_deals'] }}</div>
                <div><strong>Open Deals:</strong> {{ $analytics['sales']['open_deals'] }}</div>
                <div><strong>Won Deals:</strong> {{ $analytics['sales']['won_deals'] }}</div>
                <div><strong>Win Rate:</strong> {{ number_format($analytics['sales']['win_rate'], 2) }}%</div>
                <div><strong>Total Pipeline Value:</strong> ${{ number_format($analytics['sales']['total_value'], 2) }}</div>
                <div><strong>Won Value:</strong> ${{ number_format($analytics['sales']['won_value'], 2) }}</div>
                <div><strong>Avg Deal Size:</strong> ${{ number_format($analytics['sales']['avg_deal_size'] ?? 0, 2) }}</div>
            </div>
        </div>
        
        <!-- Leads Analytics -->
        <div class="content-card" style="background: #f9f9f9;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Leads Analytics</h2>
            <div style="line-height: 2;">
                <div><strong>Total Leads:</strong> {{ $analytics['leads']['total'] }}</div>
                <div><strong>Conversion Rate:</strong> {{ number_format($analytics['leads']['conversion_rate'], 2) }}%</div>
                <div><strong>Avg Lead Score:</strong> {{ number_format($analytics['leads']['avg_lead_score'] ?? 0, 1) }}</div>
                <div><strong>Top Sources:</strong>
                    @if($analytics['leads']['top_sources']->count() > 0)
                    <ul style="margin-left: 20px; margin-top: 5px;">
                        @foreach($analytics['leads']['top_sources'] as $source)
                        <li>{{ $source->lead_source ?? 'N/A' }}: {{ $source->count }}</li>
                        @endforeach
                    </ul>
                    @else
                    <div style="margin-left: 20px; margin-top: 5px; color: #666;">No data available</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Revenue Analytics -->
        <div class="content-card" style="background: #f9f9f9;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Revenue Analytics</h2>
            <div style="line-height: 2;">
                <div><strong>Total Invoiced:</strong> ${{ number_format($analytics['revenue']['total_invoiced'], 2) }}</div>
                <div><strong>Total Paid:</strong> ${{ number_format($analytics['revenue']['total_paid'], 2) }}</div>
                <div><strong>Outstanding:</strong> ${{ number_format($analytics['revenue']['outstanding'], 2) }}</div>
                <div><strong>Overdue:</strong> ${{ number_format($analytics['revenue']['overdue'], 2) }}</div>
            </div>
        </div>
        
        <!-- Conversion Analytics -->
        <div class="content-card" style="background: #f9f9f9;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Conversion Analytics</h2>
            <div style="line-height: 2;">
                <div><strong>Lead to Contact:</strong> {{ number_format($analytics['conversion']['lead_to_contact'], 2) }}%</div>
                <div><strong>Deal Win Rate:</strong> {{ number_format($analytics['conversion']['deal_win_rate'], 2) }}%</div>
                <div><strong>Lead to Deal:</strong> {{ number_format($analytics['conversion']['lead_to_deal'], 2) }}%</div>
            </div>
        </div>
    </div>
</div>
@endsection

