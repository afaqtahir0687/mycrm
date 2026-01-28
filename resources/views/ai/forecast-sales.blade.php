@extends('layouts.app')

@section('title', 'AI Sales Forecast')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">AI Sales Forecast</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'ai.forecast-sales']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <h3>Sales Forecast Overview</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Next 30 Days</div>
                <div style="font-size: 24px; font-weight: bold; color: #1976d2;">${{ number_format($forecast['next_30_days'], 2) }}</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Next 60 Days</div>
                <div style="font-size: 24px; font-weight: bold; color: #1976d2;">${{ number_format($forecast['next_60_days'], 2) }}</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Next 90 Days</div>
                <div style="font-size: 24px; font-weight: bold; color: #1976d2;">${{ number_format($forecast['next_90_days'], 2) }}</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Weighted Forecast</div>
                <div style="font-size: 24px; font-weight: bold; color: #388e3c;">${{ number_format($forecast['weighted_forecast'], 2) }}</div>
            </div>
        </div>
    </div>
    
    <div class="summary-section" style="margin-top: 20px;">
        <h3>Conversion Probability</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Win Rate</div>
                <div style="font-size: 24px; font-weight: bold; color: #388e3c;">{{ number_format($forecast['conversion_probability']['win_rate'], 2) }}%</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Loss Rate</div>
                <div style="font-size: 24px; font-weight: bold; color: #d32f2f;">{{ number_format($forecast['conversion_probability']['loss_rate'], 2) }}%</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Total Deals</div>
                <div style="font-size: 24px; font-weight: bold; color: #1976d2;">{{ $forecast['conversion_probability']['total_deals'] }}</div>
            </div>
        </div>
    </div>
    
    @if($forecast['deals_by_stage']->count() > 0)
    <div class="summary-section" style="margin-top: 20px;">
        <h3>Deals by Stage</h3>
        <div class="table-container" style="margin-top: 15px;">
            <table>
                <thead>
                    <tr>
                        <th>Stage</th>
                        <th>Count</th>
                        <th>Total Value</th>
                        <th>Average Deal Size</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forecast['deals_by_stage'] as $stage)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $stage->stage)) }}</td>
                        <td>{{ $stage->count }}</td>
                        <td>${{ number_format($stage->total, 2) }}</td>
                        <td>${{ number_format($stage->total / ($stage->count ?: 1), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

