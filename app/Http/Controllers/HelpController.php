<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function show($form)
    {
        // Map create/edit/show routes to their index route for help content
        $form = str_replace(['.create', '.edit', '.show'], '.index', $form);
        
        $helpContent = $this->getHelpContent($form);
        return view('help.show', compact('helpContent', 'form'));
    }
    
    private function getHelpContent($form)
    {
        $contents = [
            'dashboard' => [
                'title' => 'Dashboard - Help Documentation',
                'aims' => 'The Dashboard provides a comprehensive overview of key CRM metrics and statistics at a glance.',
                'objectives' => [
                    'Display real-time statistics for all CRM modules',
                    'Provide quick access to key performance indicators',
                    'Monitor overall business health and activity levels'
                ],
                'linked_forms' => [
                    'All CRM modules are accessible from the dashboard',
                    'Click on any metric to navigate to the detailed module'
                ],
                'functionality' => [
                    'View total counts for Leads, Accounts, Contacts, Deals, Tickets, and Tasks',
                    'Monitor new leads and active deals',
                    'Access all modules via the horizontal navigation menu'
                ],
                'data_entry' => [
                    'The dashboard is read-only and displays aggregated data',
                    'Navigate to specific modules to perform data entry operations'
                ],
                'process_flow' => [
                    'Dashboard → Select Module → Perform Actions',
                    'All modules are accessible from the top navigation bar'
                ],
                'reports' => [
                    'Summary statistics for all modules',
                    'Real-time data updates',
                    'Quick access to detailed module views'
                ]
            ],
            'leads.index' => [
                'title' => 'Leads Management - Help Documentation',
                'aims' => 'The Leads Management form is designed to capture, track, and manage potential customers throughout the sales process.',
                'objectives' => [
                    'Capture and store lead information from various sources',
                    'Score and prioritize leads based on quality indicators',
                    'Track lead status through the sales funnel',
                    'Assign leads to sales representatives',
                    'Generate reports on lead conversion rates'
                ],
                'linked_forms' => [
                    'Contacts - Leads can be converted to contacts',
                    'Accounts - Leads can be associated with accounts',
                    'Deals - Qualified leads can become deals',
                    'Tasks - Follow-up tasks can be created for leads',
                    'Communications - Track all interactions with leads'
                ],
                'functionality' => [
                    'Add New Entry: Create a new lead record with complete information',
                    'Export Excel: Export lead data to Excel format for analysis',
                    'Export PDF: Generate PDF reports of lead information',
                    'Import Excel: Bulk import leads from Excel file',
                    'Filter & Sort: Filter leads by status, source, and sort by various fields',
                    'View: Display detailed information about a specific lead',
                    'Edit: Modify existing lead information',
                    'Delete: Remove lead records from the system'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry" button',
                    'Step 2: Fill in the required fields (First Name, Status)',
                    'Step 3: Optionally fill in additional information (email, phone, company, etc.)',
                    'Step 4: Assign the lead to a sales representative if needed',
                    'Step 5: Set lead score and source',
                    'Step 6: Add any relevant notes',
                    'Step 7: Click "Create Lead" to save'
                ],
                'process_flow' => [
                    'New Lead → Contacted → Qualified → Converted/Lost',
                    'At each stage, the lead status should be updated',
                    'Tasks and communications can be linked to track interactions',
                    'Once converted, a lead can be transformed into a contact and deal'
                ],
                'reports' => [
                    'Lead Source Report: Analyze leads by source',
                    'Conversion Rate Report: Track conversion from new to qualified/converted',
                    'Lead Score Distribution: View distribution of lead scores',
                    'Sales Rep Performance: Track lead assignment and conversion by rep'
                ]
            ],
            'contacts.index' => [
                'title' => 'Contacts Management - Help Documentation',
                'aims' => 'Manage individual contact information and relationships with customers and prospects.',
                'objectives' => [
                    'Store detailed contact information',
                    'Link contacts to accounts',
                    'Track communication history',
                    'Manage contact assignments'
                ],
                'linked_forms' => [
                    'Accounts - Contacts belong to accounts',
                    'Deals - Contacts can be associated with deals',
                    'Communications - All interactions with contacts',
                    'Tasks - Follow-up tasks for contacts'
                ],
                'functionality' => [
                    'Add New Entry: Create new contact records',
                    'Export/Import: Excel and PDF export/import functionality',
                    'Filter & Sort: Filter by status, account, and sort by various fields',
                    'View/Edit/Delete: Full CRUD operations on contacts'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter first name (required) and other details',
                    'Step 3: Link to an account if applicable',
                    'Step 4: Assign to a user if needed',
                    'Step 5: Set status and save'
                ],
                'process_flow' => [
                    'Create Contact → Link to Account → Track Communications → Create Deals',
                    'Contacts can be created from converted leads',
                    'All interactions are tracked in Communications module'
                ],
                'reports' => [
                    'Contact by Account Report',
                    'Contact Activity Report',
                    'Contact Assignment Report'
                ]
            ],
            'accounts.index' => [
                'title' => 'Accounts Management - Help Documentation',
                'aims' => 'Manage company accounts and organizational information.',
                'objectives' => [
                    'Store company information and details',
                    'Track account relationships',
                    'Manage account ownership',
                    'Monitor account status'
                ],
                'linked_forms' => [
                    'Contacts - Accounts have multiple contacts',
                    'Deals - Accounts are linked to deals',
                    'Opportunities - Accounts have opportunities',
                    'Invoices - Accounts receive invoices'
                ],
                'functionality' => [
                    'Add New Entry: Create new account records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, type, and sort',
                    'View/Edit/Delete: Complete account management'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter account name (required)',
                    'Step 3: Fill in company details (industry, type, etc.)',
                    'Step 4: Add billing and shipping addresses',
                    'Step 5: Set owner and status, then save'
                ],
                'process_flow' => [
                    'Create Account → Add Contacts → Create Deals → Generate Invoices',
                    'Accounts are central to the CRM workflow',
                    'All related records are linked through accounts'
                ],
                'reports' => [
                    'Account Summary Report',
                    'Account by Industry Report',
                    'Account Revenue Report'
                ]
            ],
            'deals.index' => [
                'title' => 'Deals Management - Help Documentation',
                'aims' => 'Track sales deals through the entire sales pipeline from prospecting to closure.',
                'objectives' => [
                    'Manage sales opportunities and deals',
                    'Track deal stages and probability',
                    'Monitor deal value and expected close dates',
                    'Assign deals to sales representatives'
                ],
                'linked_forms' => [
                    'Accounts - Deals belong to accounts',
                    'Contacts - Deals have primary contacts',
                    'Leads - Deals can originate from leads',
                    'Quotations - Deals can generate quotations',
                    'Invoices - Closed deals become invoices'
                ],
                'functionality' => [
                    'Add New Entry: Create new deal records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, stage, and sort by amount or date',
                    'View/Edit/Delete: Complete deal management',
                    'Track deal stages: Prospecting → Qualification → Proposal → Negotiation → Closed'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter deal name (required)',
                    'Step 3: Link to account and contact',
                    'Step 4: Set amount, stage, and probability',
                    'Step 5: Set expected close date and owner, then save'
                ],
                'process_flow' => [
                    'Prospecting → Qualification → Proposal → Negotiation → Closed Won/Lost',
                    'Update deal stage as it progresses',
                    'Set probability percentage based on likelihood of closing',
                    'Once won, create invoice or quotation'
                ],
                'reports' => [
                    'Deal Pipeline Report: View all deals by stage',
                    'Deal Value Report: Total value of open deals',
                    'Win/Loss Report: Analyze won vs lost deals',
                    'Sales Rep Performance: Track deals by owner'
                ]
            ],
            'opportunities.index' => [
                'title' => 'Opportunities Management - Help Documentation',
                'aims' => 'Track and manage sales opportunities throughout the sales cycle.',
                'objectives' => [
                    'Identify and track potential sales opportunities',
                    'Monitor opportunity stages and probability',
                    'Forecast revenue from opportunities',
                    'Manage opportunity assignments'
                ],
                'linked_forms' => [
                    'Accounts - Opportunities belong to accounts',
                    'Contacts - Opportunities have contacts',
                    'Deals - Opportunities can become deals',
                    'Quotations - Opportunities can generate quotations'
                ],
                'functionality' => [
                    'Add New Entry: Create new opportunity records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, stage, and sort',
                    'View/Edit/Delete: Complete opportunity management'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter opportunity name (required)',
                    'Step 3: Link to account and contact',
                    'Step 4: Set amount, stage, and probability',
                    'Step 5: Set close date and owner, then save'
                ],
                'process_flow' => [
                    'Create Opportunity → Qualify → Develop Proposal → Negotiate → Close',
                    'Update opportunity stage as it progresses',
                    'Convert to deal when qualified',
                    'Generate quotation or invoice upon closure'
                ],
                'reports' => [
                    'Opportunity Pipeline Report',
                    'Opportunity Forecast Report',
                    'Conversion Rate Report'
                ]
            ],
            'quotations.index' => [
                'title' => 'Quotations Management - Help Documentation',
                'aims' => 'Create, manage, and track quotations sent to customers.',
                'objectives' => [
                    'Generate professional quotations',
                    'Track quotation status and validity',
                    'Link quotations to deals and opportunities',
                    'Monitor quotation acceptance rates'
                ],
                'linked_forms' => [
                    'Accounts - Quotations are sent to accounts',
                    'Contacts - Quotations have contact persons',
                    'Deals - Quotations are linked to deals',
                    'Invoices - Accepted quotations become invoices'
                ],
                'functionality' => [
                    'Add New Entry: Create new quotation records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status and sort by date or amount',
                    'View/Edit/Delete: Complete quotation management',
                    'Calculate totals: Subtotal + Tax - Discount = Total'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter quotation number (required, must be unique)',
                    'Step 3: Link to account, contact, and deal',
                    'Step 4: Set quotation date and valid until date',
                    'Step 5: Enter amounts (subtotal, tax, discount)',
                    'Step 6: Set status and save'
                ],
                'process_flow' => [
                    'Draft → Sent → Accepted/Rejected/Expired',
                    'Create quotation from deal or opportunity',
                    'Send to customer for approval',
                    'If accepted, create invoice from quotation'
                ],
                'reports' => [
                    'Quotation Status Report',
                    'Quotation Value Report',
                    'Acceptance Rate Report'
                ]
            ],
            'invoices.index' => [
                'title' => 'Invoices Management - Help Documentation',
                'aims' => 'Generate, track, and manage customer invoices and payments.',
                'objectives' => [
                    'Create professional invoices',
                    'Track invoice status and payments',
                    'Monitor outstanding balances',
                    'Link invoices to deals and quotations'
                ],
                'linked_forms' => [
                    'Accounts - Invoices are sent to accounts',
                    'Contacts - Invoices have billing contacts',
                    'Deals - Invoices are linked to deals',
                    'Quotations - Invoices can be created from quotations'
                ],
                'functionality' => [
                    'Add New Entry: Create new invoice records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status and sort by date or amount',
                    'View/Edit/Delete: Complete invoice management',
                    'Track payments: Total Amount - Amount Paid = Balance'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter invoice number (required, must be unique)',
                    'Step 3: Link to account, contact, deal, and quotation',
                    'Step 4: Set invoice date and due date',
                    'Step 5: Enter amounts (subtotal, tax, discount, total)',
                    'Step 6: Enter amount paid and calculate balance',
                    'Step 7: Set status and save'
                ],
                'process_flow' => [
                    'Draft → Sent → Paid/Partial/Overdue',
                    'Create invoice from quotation or deal',
                    'Send to customer',
                    'Record payments as they are received',
                    'Update status based on payment amount'
                ],
                'reports' => [
                    'Invoice Status Report',
                    'Outstanding Invoices Report',
                    'Payment Collection Report',
                    'Revenue Report'
                ]
            ],
            'support-tickets.index' => [
                'title' => 'Support Tickets Management - Help Documentation',
                'aims' => 'Manage customer support requests and track resolution times.',
                'objectives' => [
                    'Create and track support tickets',
                    'Assign tickets to support agents',
                    'Monitor SLA compliance',
                    'Track ticket resolution'
                ],
                'linked_forms' => [
                    'Accounts - Tickets belong to accounts',
                    'Contacts - Tickets are created by contacts',
                    'Tasks - Follow-up tasks for tickets',
                    'Communications - All ticket communications'
                ],
                'functionality' => [
                    'Add New Entry: Create new support ticket records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, priority, and sort',
                    'View/Edit/Delete: Complete ticket management',
                    'SLA Monitoring: Track SLA hours and deadlines'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter ticket number (required, must be unique)',
                    'Step 3: Enter subject and description (required)',
                    'Step 4: Link to account and contact',
                    'Step 5: Set priority, status, and type',
                    'Step 6: Assign to support agent',
                    'Step 7: Set SLA hours if applicable, then save'
                ],
                'process_flow' => [
                    'New → Open → In Progress → Resolved → Closed',
                    'Assign ticket to appropriate agent',
                    'Update status as work progresses',
                    'Resolve and close when completed',
                    'Monitor SLA deadlines'
                ],
                'reports' => [
                    'Ticket Status Report',
                    'SLA Compliance Report',
                    'Agent Performance Report',
                    'Ticket Resolution Time Report'
                ]
            ],
            'tasks.index' => [
                'title' => 'Tasks Management - Help Documentation',
                'aims' => 'Manage tasks, reminders, and follow-up activities.',
                'objectives' => [
                    'Create and assign tasks',
                    'Track task completion',
                    'Set reminders for important activities',
                    'Link tasks to related records'
                ],
                'linked_forms' => [
                    'All modules - Tasks can be linked to any record',
                    'Users - Tasks are assigned to users',
                    'Communications - Tasks can trigger communications'
                ],
                'functionality' => [
                    'Add New Entry: Create new task records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, priority, and sort by due date',
                    'View/Edit/Delete: Complete task management',
                    'Reminders: Set reminder dates and times'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter subject (required)',
                    'Step 3: Add description if needed',
                    'Step 4: Set priority and status',
                    'Step 5: Set due date and time',
                    'Step 6: Assign to user',
                    'Step 7: Link to related record if applicable',
                    'Step 8: Set reminder if needed, then save'
                ],
                'process_flow' => [
                    'Not Started → In Progress → Completed',
                    'Create task for follow-up activities',
                    'Set reminders for important deadlines',
                    'Update status as work progresses',
                    'Mark as completed when done'
                ],
                'reports' => [
                    'Task Status Report',
                    'Overdue Tasks Report',
                    'Task Completion Report',
                    'User Task Load Report'
                ]
            ],
            'communications.index' => [
                'title' => 'Communications Management - Help Documentation',
                'aims' => 'Track all communications including emails, calls, SMS, WhatsApp, and meetings.',
                'objectives' => [
                    'Record all customer interactions',
                    'Track communication history',
                    'Monitor communication status',
                    'Link communications to accounts, contacts, and leads'
                ],
                'linked_forms' => [
                    'Accounts - Communications with accounts',
                    'Contacts - Communications with contacts',
                    'Leads - Communications with leads',
                    'Tasks - Communications can trigger tasks'
                ],
                'functionality' => [
                    'Add New Entry: Create new communication records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by type, direction, and sort',
                    'View/Edit/Delete: Complete communication management',
                    'Track duration for calls and meetings'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Select communication type (email, phone, SMS, WhatsApp, meeting, note)',
                    'Step 3: Set direction (inbound or outbound)',
                    'Step 4: Enter subject and content',
                    'Step 5: Add email addresses or phone numbers',
                    'Step 6: Link to account, contact, or lead',
                    'Step 7: Set duration for calls/meetings',
                    'Step 8: Set status and save'
                ],
                'process_flow' => [
                    'Create Communication → Link to Record → Track Status',
                    'All communications are logged automatically',
                    'Communications can trigger automated workflows',
                    'Review communication history for any record'
                ],
                'reports' => [
                    'Communication Type Report',
                    'Communication Volume Report',
                    'Response Time Report'
                ]
            ],
            'automation-workflows.index' => [
                'title' => 'Automation Workflows Management - Help Documentation',
                'aims' => 'Create and manage automated workflows to streamline CRM processes.',
                'objectives' => [
                    'Automate repetitive tasks',
                    'Trigger actions based on events',
                    'Improve process efficiency',
                    'Ensure consistent follow-up'
                ],
                'linked_forms' => [
                    'All modules - Workflows can trigger actions on any module',
                    'Tasks - Workflows can create tasks',
                    'Communications - Workflows can send emails/SMS'
                ],
                'functionality' => [
                    'Add New Entry: Create new workflow records',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status and sort',
                    'View/Edit/Delete: Complete workflow management',
                    'Configure triggers and actions'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter workflow name (required)',
                    'Step 3: Add description',
                    'Step 4: Set trigger type (e.g., lead_created, task_due)',
                    'Step 5: Configure trigger conditions (JSON format)',
                    'Step 6: Configure actions (JSON format)',
                    'Step 7: Set active status, then save'
                ],
                'process_flow' => [
                    'Create Workflow → Set Triggers → Configure Actions → Activate',
                    'Workflows run automatically when triggers are met',
                    'Actions are executed based on configured rules',
                    'Monitor workflow execution and results'
                ],
                'reports' => [
                    'Workflow Execution Report',
                    'Workflow Performance Report'
                ]
            ],
            'contact-accounts.index' => [
                'title' => 'Contact & Accounts - Help Documentation',
                'aims' => 'The Contact & Accounts module provides an overview and centralized access to manage customer relationships through accounts and individual contacts.',
                'objectives' => [
                    'Provide a unified view of contacts and accounts',
                    'Track overall relationship management metrics',
                    'Quick access to contact and account management functions',
                    'Monitor active and inactive relationships'
                ],
                'linked_forms' => [
                    'Contacts - Individual contact management',
                    'Accounts - Company and organization management',
                    'Deals - Deals can be associated with accounts and contacts',
                    'Opportunities - Opportunities linked to accounts',
                    'Invoices - Invoices sent to accounts'
                ],
                'functionality' => [
                    'View Summary: See total counts and active/inactive status',
                    'Quick Access: Direct links to Contacts and Accounts modules',
                    'Create Links: Quick access to create new contacts and accounts',
                    'Navigation: Access detailed contact and account management'
                ],
                'data_entry' => [
                    'This is an overview page showing summary statistics',
                    'Click "View Contacts" to access the Contacts module',
                    'Click "View Accounts" to access the Accounts module',
                    'Use quick access buttons to create new records',
                    'Navigate to detailed modules for full data entry capabilities'
                ],
                'process_flow' => [
                    'Overview → Select Module (Contacts/Accounts) → Manage Records',
                    'Accounts are created first, then contacts are linked to accounts',
                    'All related records (deals, opportunities, invoices) reference accounts'
                ],
                'reports' => [
                    'Summary statistics for contacts and accounts',
                    'Active vs Inactive breakdown',
                    'Access detailed reports from individual modules'
                ]
            ],
            'sales-pipeline.index' => [
                'title' => 'Sales Pipeline - Help Documentation',
                'aims' => 'The Sales Pipeline module provides a comprehensive overview of all sales-related activities including deals, opportunities, quotations, and invoices.',
                'objectives' => [
                    'Track the entire sales process from opportunity to invoice',
                    'Monitor sales pipeline health and performance',
                    'Quick access to all sales-related modules',
                    'View consolidated sales metrics and values'
                ],
                'linked_forms' => [
                    'Deals - Active sales engagements with specific values',
                    'Opportunities - Potential sales opportunities',
                    'Quotations - Price proposals sent to customers',
                    'Invoices - Billing and payment tracking',
                    'Accounts - All sales activities are linked to accounts',
                    'Contacts - Primary contacts for sales activities'
                ],
                'functionality' => [
                    'View Summary: See totals and status breakdowns for all sales modules',
                    'Pipeline Metrics: Track open deals, opportunities, and their values',
                    'Quotation Tracking: Monitor sent and accepted quotations',
                    'Invoice Management: View paid, overdue, and outstanding invoices',
                    'Quick Access: Direct links to all sales modules',
                    'Create Actions: Quick access to create new records'
                ],
                'data_entry' => [
                    'This is an overview page showing summary statistics',
                    'Click "View Deals" to access the Deals module',
                    'Click "View Opportunities" to access the Opportunities module',
                    'Use quick access buttons to create new records',
                    'Navigate to detailed modules for full data entry capabilities',
                    'Track the sales flow: Opportunities → Deals → Quotations → Invoices'
                ],
                'process_flow' => [
                    'Overview → Opportunities → Deals → Quotations → Invoices',
                    'Opportunities are identified and qualified',
                    'Qualified opportunities become deals with specific values',
                    'Deals generate quotations for customer approval',
                    'Accepted quotations lead to invoice generation',
                    'Track payments and outstanding balances'
                ],
                'reports' => [
                    'Sales Pipeline Summary Report',
                    'Deal Value and Win Rate Analysis',
                    'Quotation Acceptance Rate',
                    'Invoice Collection Report',
                    'Sales Performance Metrics'
                ]
            ],
            'users.index' => [
                'title' => 'Users Management - Help Documentation',
                'aims' => 'Manage system users, roles, and access permissions.',
                'objectives' => [
                    'Create and manage user accounts',
                    'Assign roles to users',
                    'Control user access and permissions',
                    'Monitor user activity'
                ],
                'linked_forms' => [
                    'Roles - Users are assigned roles',
                    'All modules - Users are assigned to records',
                    'Audit Logs - User actions are logged'
                ],
                'functionality' => [
                    'Add New Entry: Create new user accounts',
                    'Export/Import: Excel and PDF functionality',
                    'Filter & Sort: Filter by status, role, and sort',
                    'View/Edit/Delete: Complete user management',
                    'Password management and role assignment'
                ],
                'data_entry' => [
                    'Step 1: Click "Add New Entry"',
                    'Step 2: Enter name and email (required)',
                    'Step 3: Set password (required, minimum 8 characters)',
                    'Step 4: Select role from dropdown',
                    'Step 5: Enter phone and position if needed',
                    'Step 6: Set active status, then save'
                ],
                'process_flow' => [
                    'Create User → Assign Role → Set Permissions → Activate',
                    'Users can be assigned to leads, deals, tickets, etc.',
                    'User actions are tracked in audit logs',
                    'Deactivate users instead of deleting to preserve history'
                ],
                'reports' => [
                    'User Activity Report',
                    'User Assignment Report',
                    'Role Distribution Report'
                ]
            ],
            'analytics.index' => [
                'title' => 'Analytics Dashboard - Help Documentation',
                'aims' => 'The Analytics Dashboard provides comprehensive insights and metrics across all CRM modules to help make data-driven decisions.',
                'objectives' => [
                    'Analyze sales performance and pipeline health',
                    'Track lead conversion rates and sources',
                    'Monitor revenue and payment collections',
                    'Measure conversion metrics across the sales funnel',
                    'Identify trends and opportunities for improvement'
                ],
                'linked_forms' => [
                    'Leads - Lead analytics and conversion rates',
                    'Deals - Sales pipeline and win/loss analysis',
                    'Accounts - Account-based analytics',
                    'Invoices - Revenue and payment analytics',
                    'Opportunities - Opportunity pipeline metrics',
                    'Activities - Activity tracking and engagement metrics'
                ],
                'functionality' => [
                    'Sales Analytics: View total deals, open deals, won/lost deals, win rates, pipeline value, and average deal size',
                    'Leads Analytics: Track total leads, conversion rates, lead scores, source analysis, and top performing sources',
                    'Revenue Analytics: Monitor total invoiced, paid amounts, outstanding balances, overdue invoices, and monthly revenue trends',
                    'Conversion Analytics: Analyze lead-to-contact, deal win rates, and lead-to-deal conversion percentages',
                    'Export Analytics: Export all analytics data in JSON format for external analysis',
                    'Pipeline by Stage: View deal distribution across different sales stages with values',
                    'Monthly Trends: Track revenue trends over time with monthly breakdowns'
                ],
                'data_entry' => [
                    'The Analytics Dashboard is a read-only view displaying aggregated data from all CRM modules',
                    'Data is automatically calculated from existing records in the system',
                    'No manual data entry is required - all metrics update automatically',
                    'To update underlying data, navigate to the respective modules (Leads, Deals, Accounts, etc.)',
                    'Use filters and date ranges in individual modules to analyze specific time periods',
                    'Export analytics data to JSON format for external analysis tools'
                ],
                'process_flow' => [
                    'Data Collection → Module records (Leads, Deals, Accounts, etc.)',
                    'Automatic Aggregation → System calculates metrics in real-time',
                    'Dashboard Display → All metrics displayed in organized sections',
                    'Analysis → Review metrics to identify trends and opportunities',
                    'Export → Download analytics data for external reporting',
                    'Action → Use insights to improve sales processes and strategies'
                ],
                'reports' => [
                    'Sales Performance Report: Detailed sales metrics and pipeline analysis',
                    'Lead Conversion Report: Lead source performance and conversion tracking',
                    'Revenue Analysis Report: Revenue trends, collections, and outstanding amounts',
                    'Conversion Funnel Report: Complete conversion analysis from lead to deal',
                    'Pipeline Health Report: Deal stage distribution and pipeline value',
                    'Export Analytics: Download complete analytics dataset in JSON format'
                ]
            ],
            'activities.index' => [
                'title' => 'Activity Feed - Help Documentation',
                'aims' => 'The Activity Feed provides a comprehensive timeline of all activities and interactions across the CRM system, enabling complete visibility into customer engagement.',
                'objectives' => [
                    'Track all system activities and user interactions',
                    'Maintain a complete audit trail of changes and actions',
                    'Monitor customer engagement and communication history',
                    'Provide visibility into team activities and productivity',
                    'Enable historical analysis of relationship development'
                ],
                'linked_forms' => [
                    'All CRM Modules - Activities can be linked to any record (polymorphic relationship)',
                    'Leads - Track all lead-related activities',
                    'Contacts - Monitor contact engagement activities',
                    'Accounts - View account-related activities',
                    'Deals - Track deal progression activities',
                    'Tasks - Link activities to task completion',
                    'Communications - Activities can trigger or result from communications',
                    'Users - Track user activity and assignments'
                ],
                'functionality' => [
                    'View Activity Timeline: See all activities in chronological order with filtering options',
                    'Filter Activities: Filter by activity type (created, updated, deleted, called, emailed, met, note)',
                    'Filter by Subject: Filter activities by related record type and ID',
                    'Filter by User: View activities performed by specific users',
                    'Date Range Filtering: Filter activities by date range (from/to)',
                    'Log New Activity: Manually create activity records with type, title, description, duration, and location',
                    'View Activity Details: See complete information about each activity including metadata',
                    'Summary Statistics: View total, today, this week, and this month activity counts',
                    'Polymorphic Linking: Link activities to any CRM entity (leads, contacts, accounts, deals, etc.)'
                ],
                'data_entry' => [
                    'Step 1: Click "Log Activity" button from the Activity Feed page',
                    'Step 2: Select Activity Type from dropdown (created, updated, deleted, called, emailed, met, note)',
                    'Step 3: Enter Activity Title (required) - brief description of the activity',
                    'Step 4: Enter Description (optional) - detailed information about the activity',
                    'Step 5: Select Subject Type - choose the type of record this activity relates to (Lead, Contact, Account, Deal, etc.)',
                    'Step 6: Enter Subject ID - the ID of the specific record this activity relates to',
                    'Step 7: Set Activity Date and Time - when the activity occurred',
                    'Step 8: Enter Duration (optional) - for calls and meetings, specify duration in minutes',
                    'Step 9: Enter Location (optional) - physical location for meetings or calls',
                    'Step 10: Click "Create Activity" to save - activity is automatically linked to current user'
                ],
                'process_flow' => [
                    'Activity Occurs → User or system triggers an action',
                    'Log Activity → Activity is recorded with type, details, and related record',
                    'Link to Record → Activity is linked to relevant CRM entity (polymorphic)',
                    'Timeline Display → Activity appears in chronological timeline',
                    'Filter & Search → Users can filter and search activities',
                    'Analysis → Review activity patterns and engagement history',
                    'Reporting → Use activity data for productivity and engagement reports'
                ],
                'reports' => [
                    'Activity Summary Report: Total activities by type, user, and time period',
                    'User Activity Report: Track individual user productivity and activity levels',
                    'Customer Engagement Report: Analyze engagement frequency and patterns by customer',
                    'Activity Timeline Report: Chronological view of all activities for a specific record',
                    'Activity Type Distribution: Breakdown of activities by type (calls, emails, meetings, etc.)',
                    'Time-based Analysis: Activities by day, week, month with trend analysis'
                ]
            ],
            'email-templates.index' => [
                'title' => 'Email Templates - Help Documentation',
                'aims' => 'The Email Templates module enables the creation, management, and reuse of standardized email templates for consistent and efficient communication.',
                'objectives' => [
                    'Create standardized email templates for common communications',
                    'Ensure consistent messaging across all customer communications',
                    'Save time by reusing templates with variable substitution',
                    'Maintain professional communication standards',
                    'Track and manage template usage and effectiveness'
                ],
                'linked_forms' => [
                    'Communications - Templates can be used to send emails',
                    'Leads - Welcome emails, follow-up templates for leads',
                    'Contacts - Standard communication templates',
                    'Deals - Proposal and negotiation templates',
                    'Quotations - Quotation email templates',
                    'Invoices - Invoice notification templates',
                    'Support Tickets - Ticket response templates',
                    'Users - Templates are created by users'
                ],
                'functionality' => [
                    'Create Template: Build new email templates with subject and body',
                    'Template Types: Create templates for general, welcome, follow-up, quotation, invoice, and reminder emails',
                    'Variable Detection: System automatically detects variables like {name}, {company}, {amount} in templates',
                    'Variable Substitution: Replace variables with actual values when using templates',
                    'Template Status: Set templates as active or inactive to control availability',
                    'Filter Templates: Search by name, subject, or filter by type and status',
                    'Render Template: Preview how template looks with actual variable values',
                    'Edit Templates: Modify existing templates while preserving usage history',
                    'Delete Templates: Remove unused templates from the system',
                    'Export/Import: Export templates to Excel/PDF or import from files'
                ],
                'data_entry' => [
                    'Step 1: Click "Create Template" button',
                    'Step 2: Enter Template Name (required) - descriptive name for the template',
                    'Step 3: Enter Email Subject (required) - subject line, use {variable_name} for dynamic content',
                    'Step 4: Enter Email Body (required) - email content, use {variable_name} for dynamic values',
                    'Step 5: Select Template Type from dropdown (general, welcome, followup, quotation, invoice, reminder)',
                    'Step 6: Check "Active" checkbox to make template available for use',
                    'Step 7: System automatically detects variables in subject and body (e.g., {name}, {company}, {amount})',
                    'Step 8: Click "Create Template" to save',
                    'Step 9: To use template, render it with actual values using the Render Template function',
                    'Step 10: Edit or delete templates as needed from the template list'
                ],
                'process_flow' => [
                    'Create Template → Design email with subject and body including variables',
                    'Variable Detection → System identifies all variables in template (e.g., {name}, {company})',
                    'Activate Template → Set template as active for use',
                    'Select Template → Choose template when sending email',
                    'Render Template → Replace variables with actual values from record',
                    'Send Email → Use rendered template content to send communication',
                    'Track Usage → Monitor template effectiveness and usage patterns',
                    'Maintain → Update templates periodically to improve effectiveness'
                ],
                'reports' => [
                    'Template Usage Report: Track how often each template is used',
                    'Template Type Distribution: Breakdown of templates by type',
                    'Active vs Inactive Templates: View template status distribution',
                    'Template Effectiveness: Analyze response rates for templates',
                    'Variable Usage Report: See which variables are most commonly used'
                ]
            ],
            'notifications.index' => [
                'title' => 'Notifications - Help Documentation',
                'aims' => 'The Notifications system provides real-time alerts and updates about important events, deadlines, and activities within the CRM system.',
                'objectives' => [
                    'Keep users informed about important events and updates',
                    'Provide timely alerts for deadlines and tasks',
                    'Ensure critical information is not missed',
                    'Enable efficient notification management and tracking',
                    'Support real-time collaboration and communication'
                ],
                'linked_forms' => [
                    'All CRM Modules - Notifications can be linked to any record (polymorphic relationship)',
                    'Tasks - Notifications for task deadlines and reminders',
                    'Deals - Alerts for deal milestones and stage changes',
                    'Leads - Notifications for lead status changes and follow-ups',
                    'Support Tickets - Alerts for new tickets and updates',
                    'Calendar Events - Reminders for meetings and appointments',
                    'Activities - Notifications about important activities',
                    'Users - Notifications are sent to specific users'
                ],
                'functionality' => [
                    'View Notifications: See all notifications in a centralized list',
                    'Unread Count Badge: Header shows count of unread notifications with auto-refresh every 30 seconds',
                    'Filter Notifications: Filter by type (info, success, warning, error, reminder)',
                    'Filter by Status: View all notifications or only unread ones',
                    'Mark as Read: Mark individual notifications as read',
                    'Mark All as Read: Mark all notifications as read at once',
                    'Notification Types: Different types for different priority levels (info, success, warning, error, reminder)',
                    'Action URLs: Notifications can include direct links to related records',
                    'Delete Notifications: Remove notifications from the list',
                    'Real-time Updates: Notification count updates automatically without page refresh'
                ],
                'data_entry' => [
                    'Notifications are automatically created by the system based on events and triggers',
                    'Manual notification creation is typically handled through workflows or system events',
                    'To manage notifications:',
                    'Step 1: Click the notification bell icon in the header to view notifications',
                    'Step 2: Use filters to find specific notifications (type, unread status)',
                    'Step 3: Click on a notification or use "Mark as Read" button to mark it as read',
                    'Step 4: Click "Mark All as Read" to clear all unread notifications',
                    'Step 5: Click "View" button on notifications with action URLs to navigate to related record',
                    'Step 6: Use "Delete" button to remove notifications from the list',
                    'Step 7: Notification badge count updates automatically every 30 seconds'
                ],
                'process_flow' => [
                    'Event Occurs → System event or trigger fires (task due, deal stage change, etc.)',
                    'Notification Created → System creates notification with type, title, message, and link',
                    'User Assignment → Notification is assigned to relevant user(s)',
                    'Display Badge → Unread count appears in header notification bell',
                    'User Views → User clicks notification bell to view notifications',
                    'Read & Act → User reads notification and clicks action URL if available',
                    'Mark as Read → User marks notification as read or system auto-marks after action',
                    'Archive → Old notifications can be deleted to keep list manageable'
                ],
                'reports' => [
                    'Notification Summary Report: Total notifications by type and status',
                    'User Notification Report: Track notifications per user',
                    'Unread Notifications Report: Identify notifications requiring attention',
                    'Notification Type Distribution: Breakdown by type (info, warning, error, etc.)',
                    'Response Time Report: Time between notification creation and user action'
                ]
            ],
            'calendar.index' => [
                'title' => 'Calendar - Help Documentation',
                'aims' => 'The Calendar module provides a comprehensive scheduling system for managing meetings, calls, reminders, tasks, and deadlines with full calendar views and event management.',
                'objectives' => [
                    'Schedule and manage meetings, calls, and appointments',
                    'Set reminders for important deadlines and tasks',
                    'Track recurring events and activities',
                    'Link calendar events to CRM records',
                    'Manage attendee lists and event details',
                    'Provide visual calendar views (day, week, month)'
                ],
                'linked_forms' => [
                    'All CRM Modules - Calendar events can be linked to any record (polymorphic relationship)',
                    'Tasks - Calendar events can represent task deadlines',
                    'Deals - Meetings related to deal progress',
                    'Leads - Follow-up calls and meetings with leads',
                    'Contacts - Scheduled calls and meetings',
                    'Accounts - Account meetings and visits',
                    'Users - Events are assigned to users and can include attendees'
                ],
                'functionality' => [
                    'Calendar Views: Switch between Day, Week, and Month views using FullCalendar',
                    'Create Events: Add new calendar events with title, description, dates, and type',
                    'Event Types: Create meetings, calls, reminders, tasks, or deadline events',
                    'Date Selection: Click on calendar dates to create events on specific dates',
                    'Event Details: Click on events to view and edit details',
                    'All-Day Events: Mark events as all-day or set specific start/end times',
                    'Recurring Events: Set recurrence patterns (daily, weekly, monthly, yearly)',
                    'Reminders: Configure reminders with specified minutes before event',
                    'Location: Add physical or virtual location for events',
                    'Attendees: Add user IDs or email addresses for event attendees',
                    'Event Status: Track event status (scheduled, completed, cancelled)',
                    'Color Coding: Different colors for different event types for easy identification'
                ],
                'data_entry' => [
                    'Step 1: Click "Create Event" button or click on a calendar date',
                    'Step 2: Enter Event Title (required) - brief description of the event',
                    'Step 3: Enter Description (optional) - detailed information about the event',
                    'Step 4: Set Start Time (required) - when the event begins',
                    'Step 5: Set End Time (required) - when the event ends',
                    'Step 6: Select Event Type from dropdown (meeting, call, reminder, task, deadline)',
                    'Step 7: Link to Related Record (optional) - select related type and ID',
                    'Step 8: Add Location (optional) - physical or virtual meeting location',
                    'Step 9: Check "All Day" if event spans entire day',
                    'Step 10: Configure Reminder (optional) - set reminder minutes before event',
                    'Step 11: Add Attendees (optional) - enter user IDs or email addresses',
                    'Step 12: Set Recurrence Pattern (optional) - daily, weekly, monthly, yearly',
                    'Step 13: Set Status - scheduled (default), completed, or cancelled',
                    'Step 14: Click "Create Event" to save - event appears on calendar'
                ],
                'process_flow' => [
                    'Plan Event → Identify need for meeting, call, or reminder',
                    'Create Event → Add event details, time, and attendees',
                    'Link to Record → Connect event to relevant CRM record (deal, lead, contact, etc.)',
                    'Set Reminder → Configure notification before event time',
                    'Calendar Display → Event appears on calendar in appropriate view',
                    'Reminder Sent → User receives notification before event',
                    'Event Occurs → Meeting, call, or task deadline arrives',
                    'Update Status → Mark event as completed after occurrence',
                    'Follow-up → Create related activities or tasks from completed events'
                ],
                'reports' => [
                    'Calendar Event Summary Report: Total events by type and status',
                    'Upcoming Events Report: Future events by user and date range',
                    'Event Completion Report: Track completed vs scheduled events',
                    'Meeting Attendance Report: Analyze attendance patterns',
                    'Recurring Events Report: View all recurring event schedules',
                    'Event Type Distribution: Breakdown of events by type'
                ]
            ],
            'search.global' => [
                'title' => 'Global Search - Help Documentation',
                'aims' => 'The Global Search functionality provides comprehensive search capabilities across all CRM modules to quickly find relevant records and information.',
                'objectives' => [
                    'Enable quick search across all CRM entities',
                    'Reduce time spent locating specific records',
                    'Provide unified search results from multiple modules',
                    'Support efficient navigation to relevant records',
                    'Improve productivity through fast information retrieval'
                ],
                'linked_forms' => [
                    'Leads - Search leads by name, company, email, phone',
                    'Contacts - Search contacts by name, email, phone',
                    'Accounts - Search accounts by name, industry, email, phone',
                    'Deals - Search deals by name, description',
                    'Opportunities - Search opportunities by name, description',
                    'Tasks - Search tasks by subject, description',
                    'All Modules - Global search covers all CRM entities'
                ],
                'functionality' => [
                    'Global Search: Search across all CRM modules simultaneously',
                    'Quick Search: Fast search from header search bar with instant results',
                    'Search Results: Results organized by module type (Leads, Contacts, Accounts, Deals, etc.)',
                    'Multi-Field Search: Searches across name, email, phone, company, description fields',
                    'Result Navigation: Click on results to navigate directly to record detail page',
                    'Result Organization: Results grouped by entity type with counts',
                    'Search Limit: Quick search shows top 5 results per module, full search shows all matches',
                    'Real-time Search: Instant search results as you type (if implemented)'
                ],
                'data_entry' => [
                    'The Search module is query-only - no data entry required',
                    'To perform a search:',
                    'Step 1: Enter search term in the header search bar or navigate to Search page',
                    'Step 2: Enter at least 2 characters in the search field',
                    'Step 3: Click search button or press Enter',
                    'Step 4: Review search results organized by module type',
                    'Step 5: Click on any result to navigate to the full record details',
                    'Step 6: Use filters in individual modules for advanced searching',
                    'Step 7: Quick search in header shows limited results, full search page shows complete results'
                ],
                'process_flow' => [
                    'Enter Search Term → User types search query (minimum 2 characters)',
                    'Search Execution → System searches across all CRM modules',
                    'Result Aggregation → Results collected and grouped by module type',
                    'Result Display → Results shown organized by Leads, Contacts, Accounts, Deals, etc.',
                    'Navigate to Record → User clicks on result to view full details',
                    'Perform Action → User can view, edit, or take action on found record',
                    'Refine Search → If needed, use advanced filters in individual modules'
                ],
                'reports' => [
                    'Search Usage Report: Track most common search terms and patterns',
                    'Search Result Statistics: Analyze which modules are searched most frequently',
                    'Search Effectiveness: Measure search success rates and user navigation patterns'
                ]
            ],
            'ai.score-lead' => [
                'title' => 'AI Lead Scoring - Help Documentation',
                'aims' => 'The AI Lead Scoring feature automatically evaluates and scores leads based on multiple factors to help prioritize sales efforts and identify high-value opportunities.',
                'objectives' => [
                    'Automatically score leads using AI algorithms',
                    'Prioritize leads based on conversion probability',
                    'Identify high-value leads for immediate attention',
                    'Generate AI-powered insights and recommendations',
                    'Analyze sentiment from communication history',
                    'Improve sales efficiency through intelligent lead prioritization'
                ],
                'linked_forms' => [
                    'Leads - AI scoring applies to lead records',
                    'Communications - Sentiment analysis uses communication history',
                    'Accounts - Company information factors into scoring',
                    'Deals - Qualified leads can be converted to deals',
                    'Contacts - Scored leads can become contacts',
                    'Activities - Interaction history influences scoring'
                ],
                'functionality' => [
                    'Automatic Lead Scoring: System calculates lead score based on multiple factors (0-100 scale)',
                    'Scoring Factors: Evaluates email domain quality, company name, phone number, website, industry, lead source, interaction history, and status progression',
                    'AI Insights: Generates intelligent insights about lead quality and potential',
                    'AI Recommendations: Provides actionable recommendations for lead engagement',
                    'Sentiment Analysis: Analyzes communication history to determine positive/negative/neutral sentiment',
                    'Score Display: View AI score alongside traditional lead score',
                    'Score Breakdown: See detailed breakdown of scoring factors',
                    'Real-time Scoring: Score updates automatically when lead information changes',
                    'Lead Prioritization: Use AI score to prioritize leads for sales team'
                ],
                'data_entry' => [
                    'AI Lead Scoring is automated - no manual data entry required',
                    'To trigger AI scoring for a lead:',
                    'Step 1: Navigate to a Lead detail page',
                    'Step 2: Click "AI Score Lead" button or access via AI menu',
                    'Step 3: System automatically analyzes lead data and communications',
                    'Step 4: Review AI-generated score (0-100) and scoring factors',
                    'Step 5: Read AI insights about lead quality and potential',
                    'Step 6: Review AI recommendations for next steps',
                    'Step 7: Check sentiment analysis based on communication history',
                    'Step 8: Use insights and recommendations to prioritize and engage lead',
                    'Step 9: AI score is saved to lead record for future reference',
                    'Note: Ensure lead has complete information (company, email, phone, etc.) for accurate scoring'
                ],
                'process_flow' => [
                    'Lead Created/Updated → Lead information captured in system',
                    'Trigger AI Scoring → User clicks "AI Score Lead" or system auto-scores',
                    'Data Analysis → System evaluates lead using multiple factors',
                    'Communication Analysis → Sentiment analyzed from communication history',
                    'Score Calculation → AI algorithm calculates score (0-100)',
                    'Insight Generation → System generates insights and recommendations',
                    'Results Display → Score, insights, recommendations, and sentiment shown',
                    'Lead Prioritization → Sales team uses score to prioritize engagement',
                    'Action Taken → Follow recommendations for lead engagement',
                    'Score Updates → Re-score as lead information and interactions evolve'
                ],
                'reports' => [
                    'AI Score Distribution Report: View distribution of lead scores across range',
                    'High-Value Leads Report: Identify leads with scores above threshold (e.g., 70+)',
                    'Scoring Factor Analysis: Analyze which factors most impact lead scores',
                    'Sentiment Analysis Report: Distribution of positive/negative/neutral sentiment',
                    'Recommendation Effectiveness: Track if following recommendations improves conversion',
                    'Score vs Conversion Correlation: Analyze relationship between AI score and actual conversions'
                ]
        ],
        'data-scraping.index' => [
            'title' => 'Data Scraping Utility - Help Documentation',
            'aims' => 'The Data Scraping Utility allows users to extract business information from publicly available free sources such as maps, business directories, and social media platforms, while ensuring compliance with terms of service and data privacy regulations.',
            'objectives' => [
                'Extract business contact information from free public sources',
                'Collect structured data including company names, addresses, phone numbers, and emails',
                'Filter businesses by location (country, city), industry, and company size',
                'Visualize business locations on an interactive map',
                'Import scraped data directly into the Leads module for follow-up',
                'Ensure ethical and legal data collection practices'
            ],
            'linked_forms' => [
                'Leads - Scraped data can be imported as new leads',
                'Accounts - Imported businesses can become accounts',
                'Contacts - Contact information can be used to create contacts',
                'Map Integration - Visual representation of business locations'
            ],
            'functionality' => [
                'Two-Pane Interface: Scraped data on left, map visualization on right',
                'Search Filters: Filter by Country, City, Business Type/Industry, and Company Size',
                'Data Display: View company name, address, phone, email, website, industry, and size',
                'Map Visualization: Interactive map showing pin locations for each business',
                'Marker Interaction: Click markers to view details, click items to center map',
                'Bulk Selection: Select multiple businesses for batch import',
                'Import to Leads: Import selected businesses directly to the Leads module',
                'Data Validation: Ensures required fields (Country, City) are provided'
            ],
            'data_entry' => [
                'Step 1: Select Country from the dropdown (required)',
                'Step 2: Enter City name (required)',
                'Step 3: Optionally select Business Type/Industry to filter results',
                'Step 4: Optionally select Company Size (SMC, Large Scale, Public)',
                'Step 5: Click "Start Scraping" button to begin data extraction',
                'Step 6: Review scraped data in the left pane',
                'Step 7: View business locations on the map in the right pane',
                'Step 8: Click on data items or map markers to view details',
                'Step 9: Select businesses you want to import (checkboxes)',
                'Step 10: Click "Import Selected to Leads" to add them to the Leads module'
            ],
            'process_flow' => [
                'Define Search Criteria → Execute Scraping → Review Results → Select Businesses → Import to Leads',
                'Scraped data is displayed in two panes: data table and map visualization',
                'Users can interact with both panes - clicking items centers map, clicking markers shows details',
                'Selected businesses can be imported in bulk to create new lead records',
                'Imported leads are automatically tagged with "Data Scraping" as the source'
            ],
            'reports' => [
                'Scraping Results: View all scraped businesses with complete details',
                'Map Visualization: Geographic distribution of scraped businesses',
                'Import Report: Track which businesses were imported and when',
                'Leads Report: View leads created from scraped data in the Leads module'
            ]
        ],
        'master-flow.index' => [
                'title' => 'Master Flow - CRM Process Flow Documentation',
            'aims' => 'The Master Flow provides a comprehensive step-by-step guide to the complete CRM process from initial lead capture through to customer management, sales closure, and ongoing relationship maintenance.',
            'objectives' => [
                'Provide a clear understanding of the end-to-end CRM process',
                'Guide users through each step with specific menu navigation',
                'Visualize the process flow for better comprehension',
                'Serve as a reference guide for new users and training purposes',
                'Document the relationship between different CRM modules'
            ],
            'linked_forms' => [
                'All CRM modules are part of the master flow',
                'Dashboard - Starting point and overview',
                'Leads - Entry point for new prospects',
                'Contact & Accounts - Relationship management',
                'Sales Pipeline - Complete sales process (Deals, Opportunities, Quotations, Invoices)',
                'Support Tickets - Post-sale customer service',
                'Tasks & Communications - Activity tracking',
                'Analytics - Performance monitoring',
                'Tools & Utilities - Supporting functions (Calendar, Email Templates, etc.)'
            ],
            'functionality' => [
                'Two-Pane View: Step-by-step instructions on left, visual flowchart on right',
                'Navigation Guide: Each step shows the exact menu path to access',
                'Process Flow: Complete workflow from lead to customer relationship',
                'Menu References: Specific menu and submenu items for each step',
                'Status Tracking: Shows status transitions throughout the process',
                'Module Integration: Demonstrates how modules work together',
                'Visual Flowchart: Right pane displays the process flow visually',
                'Interactive Reference: Click through to understand each step in detail'
            ],
            'data_entry' => [
                'The Master Flow is a reference document, not a data entry form',
                'Use it as a guide to understand the complete CRM workflow',
                'Follow the step-by-step instructions to navigate the system',
                'Refer to the flowchart for a visual representation of the process',
                'Each step indicates which menu and submenu to access',
                'Follow the menu paths shown to perform each step in the actual system'
            ],
            'process_flow' => [
                'Login → Dashboard → Lead Capture → Lead Qualification → Account/Contact Creation → Opportunity Identification → Deal Management → Quotation Generation → Invoice Creation → Support Management → Task & Activity Management → Communication Tracking → Workflow Automation → Analytics & Reporting → Calendar Management → User Management → Global Search → Continuous Cycle',
                'The process flows from prospect identification to customer relationship management',
                'Multiple parallel processes: Sales Pipeline, Support, and Relationship Management',
                'Continuous cycle for ongoing business growth and relationship maintenance',
                'Each step builds upon previous steps to create a complete customer journey'
            ],
            'reports' => [
                'Master Flow Report: This document itself showing the complete process',
                'Analytics Dashboard: View overall process metrics and KPIs',
                'Activity Feed: Track all activities in the flow across all modules',
                'Sales Pipeline Report: Analyze the sales portion of the master flow',
                'Conversion Reports: Track conversion rates at each step',
                'Custom Reports: Generate reports on any step of the master flow process'
            ]
        ]
        ];
        
        return $contents[$form] ?? [
            'title' => 'Help Documentation - ' . ucwords(str_replace(['.', '-', '_'], ' ', $form)),
            'aims' => 'This form helps manage ' . str_replace(['.', 'index'], '', $form) . ' data in the CRM system.',
            'objectives' => [
                'Manage and track ' . str_replace(['.', 'index'], '', $form) . ' records',
                'Maintain data integrity and relationships',
                'Generate reports and analytics'
            ],
            'linked_forms' => [
                'Related forms and data are linked through relationships'
            ],
            'functionality' => [
                'Add New Entry: Create new records',
                'Export Excel/PDF: Export data for analysis',
                'Import Excel: Bulk import records',
                'Filter & Sort: Filter and sort data',
                'View/Edit/Delete: Complete CRUD operations'
            ],
            'data_entry' => [
                'Step 1: Click "Add New Entry"',
                'Step 2: Fill in required fields',
                'Step 3: Add optional information',
                'Step 4: Save the record'
            ],
            'process_flow' => [
                'Create → Update → Complete/Close',
                'Records flow through various statuses',
                'Related records are linked automatically'
            ],
            'reports' => [
                'Summary reports are available',
                'Export data for detailed analysis'
            ]
        ];
    }
}
