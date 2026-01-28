@extends('layouts.app')

@section('title', 'Dashboard - Process Flow')

@section('content')
<style>
    .process-flow-container {
        background: white;
        padding: 24px;
        border-radius: 2px;
        border: 1px solid var(--ms-gray-30, #edebe9);
        margin-bottom: 24px;
    }
    
    .process-flow {
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
    }
    
    .process-section {
        border: 2px solid var(--ms-blue, #0078d4);
        border-radius: 8px;
        padding: 20px;
        background: linear-gradient(135deg, rgba(0, 120, 212, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--ms-blue, #0078d4);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--ms-blue, #0078d4);
    }
    
    .process-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .process-step {
        background: white;
        border: 1px solid var(--ms-gray-30, #edebe9);
        border-radius: 4px;
        padding: 15px;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    
    .process-step:hover {
        border-color: var(--ms-blue, #0078d4);
        box-shadow: 0 2px 4px rgba(0, 120, 212, 0.1);
        transform: translateY(-2px);
    }
    
    .process-step h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--ms-gray-120, #201f1e);
        margin-bottom: 8px;
    }
    
    .process-step p {
        font-size: 12px;
        color: var(--ms-gray-80, #8a8886);
        margin: 0;
    }
    
    .step-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .flow-arrow {
        text-align: center;
        color: var(--ms-blue, #0078d4);
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
</style>

<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Business Process Flow</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'dashboard']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="process-flow-container">
        <div style="background: #fff3cd; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #ffaa44;">
            <strong>Note:</strong> Every form shall seed the data as per the decision declared under the "Further Action" button from every record from the previous process automatically.
        </div>
        
        <div class="process-flow">
            <!-- Marketing Section -->
            <div class="process-section">
                <div class="section-title">📊 Marketing</div>
                <div class="process-steps">
                    <a href="{{ route('data-scraping.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Data Scraping</h4>
                            <p>Maps, Excel, Social Media</p>
                        </div>
                    </a>
                    <a href="{{ route('leads.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Leads & Assignment</h4>
                            <p>Manage leads and assign actions</p>
                        </div>
                    </a>
                    <a href="{{ route('communications.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Engagement Results</h4>
                            <p>Record outcomes</p>
                        </div>
                    </a>
                    <a href="{{ route('ai.lead-qualification') }}" class="step-link">
                        <div class="process-step">
                            <h4>AI Qualification</h4>
                            <p>Lead Scoring</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="flow-arrow">↓</div>
            
            <!-- Sales Section -->
            <div class="process-section">
                <div class="section-title">💼 Sales</div>
                <div class="process-steps">
                    <a href="{{ route('client-registration.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Client Registration</h4>
                            <p>Register clients</p>
                        </div>
                    </a>
                    <a href="{{ route('quotations.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Quotation</h4>
                            <p>Create quotations</p>
                        </div>
                    </a>
                    <a href="{{ route('products.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Products</h4>
                            <p>Product catalog</p>
                        </div>
                    </a>
                    <a href="{{ route('services.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Services</h4>
                            <p>Service catalog</p>
                        </div>
                    </a>
                    <a href="{{ route('agreements.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>STC/Agreements/SLAs</h4>
                            <p>Standard terms</p>
                        </div>
                    </a>
                    <a href="{{ route('deals.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Deals/Agreements</h4>
                            <p>Finalize deals</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="flow-arrow">↓</div>
            
            <!-- Accounts Section -->
            <div class="process-section">
                <div class="section-title">💰 Accounts</div>
                <div class="process-steps">
                    <a href="{{ route('invoices.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Sales Invoices</h4>
                            <p>Invoice customers</p>
                        </div>
                    </a>
                    <a href="{{ route('payments.index', ['type' => 'received']) }}" class="step-link">
                        <div class="process-step">
                            <h4>Funds Received</h4>
                            <p>Payment receipts</p>
                        </div>
                    </a>
                    <a href="{{ route('payments.index', ['type' => 'made']) }}" class="step-link">
                        <div class="process-step">
                            <h4>Fund Payments</h4>
                            <p>Payments made</p>
                        </div>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Expenses</h4>
                            <p>Track expenses</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="flow-arrow">↓</div>
            
            <!-- Help Desk Section -->
            <div class="process-section">
                <div class="section-title">🎫 Help Desk</div>
                <div class="process-steps">
                    <a href="{{ route('support-tickets.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Ticket Registration</h4>
                            <p>Post contract tickets</p>
                        </div>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="step-link">
                        <div class="process-step">
                            <h4>Tasks</h4>
                            <p>Assign to technical staff</p>
                        </div>
                    </a>
                    <a href="{{ route('support-tickets.resolution') }}" class="step-link">
                        <div class="process-step">
                            <h4>Resolution</h4>
                            <p>Issue resolution</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="dashboard-tile">
            <h3>Total Leads</h3>
            <div class="value">{{ $stats['total_leads'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Total Accounts</h3>
            <div class="value">{{ $stats['total_accounts'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Total Contacts</h3>
            <div class="value">{{ $stats['total_contacts'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Total Deals</h3>
            <div class="value">{{ $stats['total_deals'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Support Tickets</h3>
            <div class="value">{{ $stats['total_tickets'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Tasks</h3>
            <div class="value">{{ $stats['total_tasks'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>New Leads</h3>
            <div class="value">{{ $stats['new_leads'] }}</div>
        </div>
        <div class="dashboard-tile">
            <h3>Active Deals</h3>
            <div class="value">{{ $stats['active_deals'] }}</div>
        </div>
    </div>
</div>
@endsection
