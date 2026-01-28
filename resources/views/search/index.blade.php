@extends('layouts.app')

@section('title', 'Global Search')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Global Search</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'search.global']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <p>Search across all CRM modules including Leads, Contacts, Accounts, Deals, Opportunities, and Tasks.</p>
    </div>
    
    <div class="filter-section" style="max-width: 600px; margin: 0 auto;">
        <form method="GET" action="{{ route('search.global') }}">
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Enter search term (minimum 2 characters)..." 
                       class="form-control" 
                       style="flex: 1; padding: 12px; font-size: 16px;"
                       required
                       minlength="2"
                       autofocus>
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Search</button>
            </div>
            <div style="margin-top: 15px; color: #666; font-size: 14px;">
                <strong>Search in:</strong> Leads, Contacts, Accounts, Deals, Opportunities, Tasks
            </div>
        </form>
    </div>
    
    @if(request()->has('q') && request('q'))
    <div style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 4px;">
        <p style="color: #666;">Enter at least 2 characters to search.</p>
    </div>
    @endif
</div>
@endsection

