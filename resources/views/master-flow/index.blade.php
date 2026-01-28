@extends('layouts.app')

@section('title', 'Master Flow')

@section('content')
<style>
    .master-flow-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        height: calc(100vh - 200px);
    }
    
    .flow-pane {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 20px;
        overflow-y: auto;
    }
    
    .flow-pane h2 {
        color: #1976d2;
        border-bottom: 2px solid #1976d2;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .flow-step {
        margin-bottom: 25px;
        padding: 15px;
        background: #f9f9f9;
        border-left: 4px solid #1976d2;
        border-radius: 4px;
    }
    
    .flow-step h3 {
        color: #1976d2;
        margin-bottom: 10px;
        font-size: 16px;
    }
    
    .flow-step .step-number {
        display: inline-block;
        width: 30px;
        height: 30px;
        background: #1976d2;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .flow-step .menu-path {
        background: #e3f2fd;
        padding: 8px 12px;
        border-radius: 4px;
        margin-top: 10px;
        font-family: monospace;
        font-size: 13px;
        color: #1565c0;
    }
    
    .flowchart-container {
        position: relative;
        min-height: 100%;
    }
    
    .flowchart-node {
        position: relative;
        margin: 20px auto;
        padding: 15px;
        background: white;
        border: 2px solid #1976d2;
        border-radius: 8px;
        width: 85%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .flowchart-node::before {
        content: '';
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-bottom: 15px solid #1976d2;
    }
    
    .flowchart-node:first-child::before {
        display: none;
    }
    
    .flowchart-node h4 {
        color: #1976d2;
        margin: 0 0 8px 0;
        font-size: 14px;
        font-weight: 600;
    }
    
    .flowchart-node .menu-info {
        background: #e3f2fd;
        padding: 6px 10px;
        border-radius: 4px;
        margin-top: 8px;
        font-size: 12px;
        color: #1565c0;
    }
    
    .flowchart-arrow {
        text-align: center;
        color: #1976d2;
        font-size: 24px;
        margin: 5px 0;
    }
    
    @media (max-width: 1200px) {
        .master-flow-container {
            grid-template-columns: 1fr;
            height: auto;
        }
    }
</style>

<div class="content-card" style="padding: 0;">
    <div class="form-header" style="padding: 20px; border-bottom: 2px solid #e0e0e0;">
        <h1 class="form-title">Master Flow - CRM Process Flow</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'master-flow.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="master-flow-container">
        <!-- Left Pane: Step-by-Step Instructions -->
        <div class="flow-pane">
            <h2>Master Flow - Step by Step Instructions</h2>
            
            <div class="flow-step">
                <h3><span class="step-number">1</span>System Login & Access</h3>
                <p>Begin by logging into the CRM system using your credentials. Upon successful authentication, you will be directed to the Dashboard which provides an overview of key metrics and statistics.</p>
                <div class="menu-path">Menu: Dashboard → Dashboard Overview</div>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">2</span>Lead Capture & Management</h3>
                <p>Capture new leads from various sources (website, referrals, events, etc.). Enter lead information including name, company, contact details, source, and industry. Assign leads to sales representatives for follow-up. Use AI scoring to prioritize high-value leads.</p>
                <div class="menu-path">Menu: Leads → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Actions:</strong> View, Edit, Delete, Export, Import, Filter, Sort</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">3</span>Lead Qualification & Contact</h3>
                <p>Review and qualify leads based on AI scores, lead source, and interaction history. Contact qualified leads through various communication channels. Log all communications (calls, emails, meetings) in the Communications module. Update lead status as the relationship progresses.</p>
                <div class="menu-path">Menu: Leads → View/Edit → Communications → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Status Flow:</strong> New → Contacted → Qualified</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">4</span>Account & Contact Creation</h3>
                <p>Once a lead is qualified and converted, create an Account record for the company/organization. Then create Contact records for individuals associated with that account. Link contacts to their respective accounts to maintain relationship hierarchy.</p>
                <div class="menu-path">Menu: Contact & Accounts → Accounts → Add New Entry<br>
                Then: Contact & Accounts → Contacts → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Relationship:</strong> Account (Company) → Contacts (Individuals)</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">5</span>Opportunity Identification</h3>
                <p>Identify potential sales opportunities from qualified leads or existing accounts. Create opportunity records with estimated value, expected close date, and probability. Link opportunities to accounts and primary contacts. Assign opportunities to sales representatives.</p>
                <div class="menu-path">Menu: Sales Pipeline → Opportunities → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Stages:</strong> Prospecting → Qualification → Proposal → Negotiation</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">6</span>Deal Management</h3>
                <p>Convert qualified opportunities into active deals with specific deal amounts and close dates. Track deals through various stages (Prospecting, Qualification, Proposal, Negotiation, Closed Won/Lost). Monitor deal pipeline value and win rates. Update deal stages and probabilities as negotiations progress.</p>
                <div class="menu-path">Menu: Sales Pipeline → Deals → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Track:</strong> Amount, Stage, Probability, Expected Close Date, Owner</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">7</span>Quotation Generation</h3>
                <p>Generate professional quotations for deals that have reached the proposal stage. Link quotations to deals, accounts, and contacts. Set quotation validity dates and terms. Track quotation status (Draft, Sent, Accepted, Rejected, Expired).</p>
                <div class="menu-path">Menu: Sales Pipeline → Quotations → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Calculations:</strong> Subtotal + Tax - Discount = Total Amount</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">8</span>Invoice Creation & Payment Tracking</h3>
                <p>When a quotation is accepted or a deal is closed, create an invoice. Link invoices to accounts, contacts, deals, and quotations. Track invoice status (Draft, Sent, Paid, Partial, Overdue, Cancelled). Record payments to update outstanding balances. Monitor collections and overdue invoices.</p>
                <div class="menu-path">Menu: Sales Pipeline → Invoices → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Tracking:</strong> Total Amount - Amount Paid = Balance</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">9</span>Customer Support Management</h3>
                <p>Handle customer support requests by creating support tickets. Link tickets to accounts and contacts. Assign tickets to support agents based on priority and expertise. Track ticket status (New, Open, In Progress, Resolved, Closed). Monitor SLA compliance and resolution times. Log all support communications.</p>
                <div class="menu-path">Menu: Support Tickets → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Priority Levels:</strong> Low, Medium, High, Urgent</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">10</span>Task & Activity Management</h3>
                <p>Create tasks for follow-up activities, deadlines, and reminders. Assign tasks to team members. Link tasks to related records (leads, contacts, deals, tickets). Track task status (Not Started, In Progress, Completed, Cancelled). Set task priorities and due dates. Log all activities in the Activity Feed for complete audit trail.</p>
                <div class="menu-path">Menu: Tasks → Add New Entry<br>
                Tools & Utilities → Activity Feed</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Activity Types:</strong> Calls, Meetings, Emails, Notes, Created, Updated</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">11</span>Communication Tracking</h3>
                <p>Log all customer interactions including emails, phone calls, SMS, WhatsApp messages, and meetings. Link communications to related records (leads, contacts, accounts, deals). Track communication direction (inbound/outbound). Monitor communication history for complete relationship context. Use email templates for consistent messaging.</p>
                <div class="menu-path">Menu: Communications → Add New Entry<br>
                Tools & Utilities → Email Templates</div>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">12</span>Workflow Automation</h3>
                <p>Set up automation workflows to streamline repetitive tasks. Configure triggers (e.g., lead created, task due, invoice overdue) and actions (e.g., send email, create task, update status). Activate workflows to automatically execute actions based on defined conditions.</p>
                <div class="menu-path">Menu: Workflows → Add New Entry</div>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Benefits:</strong> Consistency, Efficiency, Time Savings</p>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">13</span>Analytics & Reporting</h3>
                <p>Monitor business performance through the Analytics Dashboard. View sales pipeline metrics, lead conversion rates, revenue trends, and activity summaries. Generate reports on deals, invoices, leads, and support tickets. Use AI-powered sales forecasts for planning. Export data for external analysis.</p>
                <div class="menu-path">Menu: Tools & Utilities → Analytics Dashboard<br>
                Tools & Utilities → AI Sales Forecast</div>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">14</span>Calendar & Scheduling</h3>
                <p>Manage schedules and appointments using the Calendar. Create events for meetings, calls, and deadlines. Link calendar events to related CRM records. Set reminders and invite attendees. View calendar in day, week, or month format. Sync with related tasks and communications.</p>
                <div class="menu-path">Menu: Tools & Utilities → Calendar</div>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">15</span>User & Access Management</h3>
                <p>Manage system users, assign roles (Admin, Sales Manager, Sales Rep, Support Agent), and control access permissions. Create user accounts, assign them to records (leads, deals, tickets), and monitor user activity through audit logs. Activate or deactivate users as needed.</p>
                <div class="menu-path">Menu: User Management → Add New Entry</div>
            </div>
            
            <div class="flow-step">
                <h3><span class="step-number">16</span>Global Search & Quick Access</h3>
                <p>Use the Global Search functionality to quickly find any record across all CRM modules. Search by name, email, company, or keyword. Access records directly from search results. Use quick search in the header for fast navigation.</p>
                <div class="menu-path">Menu: Tools & Utilities → Global Search<br>
                Header: Quick search bar</div>
            </div>
            
            <div class="flow-step" style="border-left-color: #388e3c; background: #f1f8e9;">
                <h3><span class="step-number" style="background: #388e3c;">✓</span>Continuous Cycle</h3>
                <p>The CRM process is continuous. As new leads are captured, the cycle repeats. Ongoing customer relationships are maintained through regular communications, support tickets, and follow-up tasks. Sales opportunities are continuously identified, and the pipeline is managed for sustained growth.</p>
                <p style="margin-top: 10px; font-size: 13px;"><strong>Key:</strong> Regular monitoring, timely follow-ups, and data-driven decisions drive success.</p>
            </div>
        </div>
        
        <!-- Right Pane: Visual Flowchart -->
        <div class="flow-pane">
            <h2>Visual Flowchart</h2>
            
            <div class="flowchart-container">
                <!-- Step 1 -->
                <div class="flowchart-node">
                    <h4>1. Login & Dashboard</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Authenticate and view system overview</p>
                    <div class="menu-info">Dashboard → Dashboard Overview</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 2 -->
                <div class="flowchart-node">
                    <h4>2. Lead Capture</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Enter new leads from various sources</p>
                    <div class="menu-info">Leads → Add New Entry</div>
                    <div class="menu-info" style="margin-top: 5px; background: #fff3cd; color: #856404;">AI Scoring Applied</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 3 -->
                <div class="flowchart-node">
                    <h4>3. Lead Qualification</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Contact, communicate, and qualify leads</p>
                    <div class="menu-info">Leads → View/Edit</div>
                    <div class="menu-info" style="margin-top: 5px;">Communications → Log Interaction</div>
                    <div class="menu-info" style="margin-top: 5px; background: #e8f5e9; color: #2e7d32;">Status: New → Contacted → Qualified</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 4 -->
                <div class="flowchart-node">
                    <h4>4. Create Account & Contacts</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Establish customer relationships</p>
                    <div class="menu-info">Contact & Accounts → Accounts → Add</div>
                    <div class="menu-info" style="margin-top: 5px;">Contact & Accounts → Contacts → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #e3f2fd; color: #1565c0;">Link Contacts to Accounts</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 5 -->
                <div class="flowchart-node">
                    <h4>5. Identify Opportunities</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Create sales opportunities</p>
                    <div class="menu-info">Sales Pipeline → Opportunities → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #fff3cd; color: #856404;">Link to Account & Contact</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 6 -->
                <div class="flowchart-node">
                    <h4>6. Manage Deals</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Track deals through sales stages</p>
                    <div class="menu-info">Sales Pipeline → Deals → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #e8f5e9; color: #2e7d32;">Stages: Qualification → Proposal → Negotiation</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 7 -->
                <div class="flowchart-node">
                    <h4>7. Generate Quotations</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Send price proposals to customers</p>
                    <div class="menu-info">Sales Pipeline → Quotations → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #fff3cd; color: #856404;">Link to Deal & Account</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 8 -->
                <div class="flowchart-node">
                    <h4>8. Create Invoices</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Bill customers and track payments</p>
                    <div class="menu-info">Sales Pipeline → Invoices → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #e8f5e9; color: #2e7d32;">Status: Draft → Sent → Paid</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 9 -->
                <div class="flowchart-node">
                    <h4>9. Support Tickets</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Handle customer support requests</p>
                    <div class="menu-info">Support Tickets → Add</div>
                    <div class="menu-info" style="margin-top: 5px; background: #ffebee; color: #c62828;">Monitor SLA Compliance</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 10 -->
                <div class="flowchart-node">
                    <h4>10. Tasks & Activities</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Manage follow-ups and track activities</p>
                    <div class="menu-info">Tasks → Add</div>
                    <div class="menu-info" style="margin-top: 5px;">Tools & Utilities → Activity Feed</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 11 -->
                <div class="flowchart-node">
                    <h4>11. Communications</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Log all customer interactions</p>
                    <div class="menu-info">Communications → Add</div>
                    <div class="menu-info" style="margin-top: 5px;">Tools & Utilities → Email Templates</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 12 -->
                <div class="flowchart-node">
                    <h4>12. Automation</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Set up automated workflows</p>
                    <div class="menu-info">Workflows → Add → Configure Triggers & Actions</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 13 -->
                <div class="flowchart-node">
                    <h4>13. Analytics & Reports</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Monitor performance and generate insights</p>
                    <div class="menu-info">Tools & Utilities → Analytics Dashboard</div>
                    <div class="menu-info" style="margin-top: 5px;">Tools & Utilities → AI Sales Forecast</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 14 -->
                <div class="flowchart-node">
                    <h4>14. Calendar</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Manage schedules and appointments</p>
                    <div class="menu-info">Tools & Utilities → Calendar → Create Event</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 15 -->
                <div class="flowchart-node">
                    <h4>15. User Management</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Manage users and access control</p>
                    <div class="menu-info">User Management → Add</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Step 16 -->
                <div class="flowchart-node">
                    <h4>16. Global Search</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Quick access to any record</p>
                    <div class="menu-info">Tools & Utilities → Global Search</div>
                    <div class="menu-info" style="margin-top: 5px;">Header: Quick Search Bar</div>
                </div>
                <div class="flowchart-arrow">↓</div>
                
                <!-- Continuous Cycle -->
                <div class="flowchart-node" style="border-color: #388e3c; background: #f1f8e9;">
                    <h4 style="color: #388e3c;">✓ Continuous Cycle</h4>
                    <p style="margin: 5px 0; font-size: 13px;">Process repeats for ongoing growth</p>
                    <div class="menu-info" style="background: #c8e6c9; color: #2e7d32;">Monitor → Follow-up → Improve</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

