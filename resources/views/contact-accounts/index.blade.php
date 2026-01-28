@extends('layouts.app')

@section('title', 'Contact & Accounts Overview')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Contact & Accounts Overview</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'contact-accounts.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('contacts.index') }}" class="btn btn-primary">View Contacts</a>
            <a href="{{ route('accounts.index') }}" class="btn btn-primary">View Accounts</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Contacts Summary</h3>
                <div><strong>Total Contacts:</strong> {{ $summary['total_contacts'] }}</div>
                <div><strong>Active Contacts:</strong> {{ $summary['active_contacts'] }}</div>
                <div><strong>Inactive Contacts:</strong> {{ $summary['inactive_contacts'] }}</div>
            </div>
            <div>
                <h3 style="margin-bottom: 10px; color: #333;">Accounts Summary</h3>
                <div><strong>Total Accounts:</strong> {{ $summary['total_accounts'] }}</div>
                <div><strong>Active Accounts:</strong> {{ $summary['active_accounts'] }}</div>
                <div><strong>Inactive Accounts:</strong> {{ $summary['inactive_accounts'] }}</div>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 30px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('contacts.index') }}" class="btn btn-primary" style="text-align: left;">Manage Contacts</a>
                <a href="{{ route('contacts.create') }}" class="btn btn-success" style="text-align: left;">Create New Contact</a>
                <a href="{{ route('accounts.index') }}" class="btn btn-primary" style="text-align: left;">Manage Accounts</a>
                <a href="{{ route('accounts.create') }}" class="btn btn-success" style="text-align: left;">Create New Account</a>
            </div>
        </div>
        
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Module Information</h2>
            <p style="line-height: 1.8; color: #666;">
                The Contact & Accounts module is central to your CRM system. Accounts represent companies or organizations,
                while Contacts are individual people associated with those accounts. This module allows you to manage
                customer relationships effectively by organizing both company-level and individual contact information.
            </p>
        </div>
    </div>
</div>
@endsection

