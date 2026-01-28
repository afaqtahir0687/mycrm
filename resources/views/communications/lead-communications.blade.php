@extends('layouts.app')

@section('title', 'Lead Communications')

@section('content')
<style>
    .communication-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .communication-modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        max-width: 800px;
        border-radius: 4px;
        max-height: 90vh;
        overflow-y: auto;
    }
    .communication-history {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        padding: 10px;
        margin-top: 10px;
        border-radius: 4px;
        background: #f8f9fa;
    }
    .communication-item {
        padding: 10px;
        margin-bottom: 10px;
        border-left: 3px solid #0078d4;
        background: white;
        border-radius: 2px;
    }
    .category-badge {
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
        margin-right: 5px;
    }
</style>

<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Lead Communications</h1>
        <div class="form-actions">
            <a href="{{ route('email-templates.index') }}" class="btn btn-secondary">Manage Templates</a>
            <a href="{{ route('help.show', ['form' => 'communications.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Quick Access</h2>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('email-templates.index') }}" class="btn btn-success" style="text-align: left;">View Templates & Messages</a>
                <a href="{{ route('email-templates.create') }}" class="btn btn-primary" style="text-align: left;">Create New Template</a>
                <a href="{{ route('communications.my-engagements') }}" class="btn btn-primary" style="text-align: left;">View Engagement Results</a>
            </div>
        </div>
        <div class="content-card">
            <h2 style="margin-bottom: 15px; color: #1976d2;">Communication Information</h2>
            <p style="line-height: 1.8; color: #666;">
                Record all communications with assigned leads (email, SMS, calls, visits, etc.). 
                Use official templates for standardized communications. All communications are logged for future reference.
            </p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('communications.index') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Search:</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Company" class="form-control">
                </div>
                <div class="filter-item">
                    <label>Assignment Action:</label>
                    <select name="assignment_action" class="form-control">
                        <option value="">All Actions</option>
                        <option value="call" {{ request('assignment_action') == 'call' ? 'selected' : '' }}>Call</option>
                        <option value="sms" {{ request('assignment_action') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ request('assignment_action') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="whatsapp" {{ request('assignment_action') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="online_meeting" {{ request('assignment_action') == 'online_meeting' ? 'selected' : '' }}>Online Meeting</option>
                        <option value="personal_visit" {{ request('assignment_action') == 'personal_visit' ? 'selected' : '' }}>Personal Visit</option>
                    </select>
                </div>
                @if(auth()->user()->role && auth()->user()->role->slug === 'admin')
                <div class="filter-item">
                    <label>Assigned To:</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">All Assignees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Company</th>
                    <th>Contact Info</th>
                    <th>Assignment Action</th>
                    <th>Deadline</th>
                    <th>Communication History</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $leadComms = $communications->get($lead->id) ?? collect();
                    $isOverdue = $lead->assignment_deadline && $lead->assignment_deadline < now();
                @endphp
                <tr>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->company_name ?? '-' }}</td>
                    <td>
                        <div style="font-size: 12px;">
                            @if($lead->email)<div>📧 {{ $lead->email }}</div>@endif
                            @if($lead->phone)<div>📞 {{ $lead->phone }}</div>@endif
                        </div>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #0078d4; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $lead->assignment_action)) }}
                        </span>
                    </td>
                    <td style="color: {{ $isOverdue ? '#d13438' : '#201f1e' }};">
                        {{ $lead->assignment_deadline ? $lead->assignment_deadline->format('Y-m-d H:i') : '-' }}
                        @if($isOverdue)
                            <span style="color: #d13438; font-size: 10px;">(Overdue)</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 11px;">
                            <strong>{{ $leadComms->count() }}</strong> communication(s)
                            @if($leadComms->count() > 0)
                                <button type="button" class="btn btn-sm btn-link" onclick="viewHistory({{ $lead->id }})" style="padding: 2px 5px; font-size: 11px;">View</button>
                            @endif
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openCommunicationModal({{ $lead->id }}, '{{ $lead->first_name }}', '{{ $lead->last_name }}', '{{ $lead->company_name ?? '' }}', '{{ $lead->email ?? '' }}', '{{ $lead->phone ?? '' }}', '{{ $lead->assignment_action }}')" style="padding: 5px 10px; font-size: 12px;">Record Communication</button>
                    </td>
                </tr>
                
                <!-- Hidden Communication History -->
                <tr id="history-{{ $lead->id }}" style="display: none;">
                    <td colspan="7">
                        <div class="communication-history">
                            <h4 style="margin-bottom: 10px;">Communication History for {{ $lead->first_name }} {{ $lead->last_name }}</h4>
                            @forelse($leadComms as $comm)
                            <div class="communication-item">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 5px;">
                                    <div>
                                        <span class="category-badge" style="background: #0078d4; color: white;">{{ ucfirst(str_replace('_', ' ', $comm->communication_category ?? $comm->type)) }}</span>
                                        @if($comm->template)
                                            <span class="category-badge" style="background: #107c10; color: white;">Template: {{ $comm->template->name }}</span>
                                        @endif
                                        <strong>{{ $comm->subject ?? 'No Subject' }}</strong>
                                    </div>
                                    <div style="font-size: 11px; color: #666;">
                                        {{ $comm->created_at->format('Y-m-d H:i') }} by {{ $comm->creator->name ?? 'Unknown' }}
                                    </div>
                                </div>
                                <div style="font-size: 12px; color: #555; margin-top: 5px;">
                                    {{ Str::limit($comm->content ?? $comm->engagement_outcome ?? '', 200) }}
                                </div>
                                @if($comm->visit_report)
                                    <div style="margin-top: 5px; padding: 5px; background: #f3f2f1; border-radius: 2px; font-size: 11px;">
                                        <strong>Visit Report:</strong> {{ Str::limit($comm->visit_report, 150) }}
                                    </div>
                                @endif
                                @if($comm->attachment_path)
                                    <div style="margin-top: 5px;">
                                        <a href="{{ Storage::url($comm->attachment_path) }}" target="_blank" class="btn btn-sm btn-link" style="padding: 2px 5px; font-size: 11px;">📎 View Attachment</a>
                                    </div>
                                @endif
                            </div>
                            @empty
                            <p style="color: #666; font-style: italic;">No communications recorded yet.</p>
                            @endforelse
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No assigned leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $leads->links() }}
    </div>
</div>

<!-- Communication Modal -->
<div id="communicationModal" class="communication-modal">
    <div class="communication-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Record Communication</h2>
            <span style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;" onclick="closeCommunicationModal()">&times;</span>
        </div>
        
        <form id="communicationForm" method="POST" action="{{ route('communications.record-lead') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="lead_id" id="modal_lead_id">
            
            <div style="display: grid; gap: 15px;">
                <div>
                    <label><strong>Lead:</strong> <span id="modal_lead_name"></span></label>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">
                        <span id="modal_lead_email"></span> | <span id="modal_lead_phone"></span>
                    </div>
                </div>
                
                <div>
                    <label>Communication Category *</label>
                    <select name="communication_category" id="comm_category" class="form-control" required onchange="updateTemplateOptions()">
                        <option value="">Select Category</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <option value="letter">Letter/Official Message</option>
                        <option value="voice_call">Voice Call</option>
                        <option value="video_call">Video Call</option>
                        <option value="visit">Personal Visit</option>
                    </select>
                    @error('communication_category')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label>Select Template (Optional)</label>
                    <select name="template_id" id="template_select" class="form-control" onchange="loadTemplate()">
                        <option value="">-- No Template --</option>
                    </select>
                    <small style="color: #666; font-size: 11px;">Official templates are marked with ⭐</small>
                </div>
                
                <div id="subject_group">
                    <label>Subject</label>
                    <input type="text" name="subject" id="comm_subject" class="form-control" placeholder="Communication subject">
                    @error('subject')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div id="email_group" style="display: none;">
                    <label>To Email</label>
                    <input type="email" name="to_email" id="comm_to_email" class="form-control" placeholder="recipient@example.com">
                </div>
                
                <div id="phone_group" style="display: none;">
                    <label>To Phone</label>
                    <input type="text" name="to_phone" id="comm_to_phone" class="form-control" placeholder="+1234567890">
                </div>
                
                <div id="duration_group" style="display: none;">
                    <label>Duration (minutes)</label>
                    <input type="number" name="duration_minutes" id="comm_duration" class="form-control" min="0" placeholder="e.g., 15">
                </div>
                
                <div>
                    <label>Content/Message *</label>
                    <textarea name="content" id="comm_content" class="form-control" rows="6" required placeholder="Enter your message or communication details..."></textarea>
                    @error('content')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div id="visit_report_group" style="display: none;">
                    <label>Visit Report</label>
                    <textarea name="visit_report" id="comm_visit_report" class="form-control" rows="4" placeholder="Enter visit details, findings, and outcomes..."></textarea>
                </div>
                
                <div>
                    <label>Attachment (Optional)</label>
                    <input type="file" name="attachment" id="comm_attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small style="color: #666; font-size: 11px;">Supported: PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>
                
                <div>
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="sent">Sent</option>
                        <option value="delivered">Delivered</option>
                        <option value="read">Read</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Record Communication</button>
                <button type="button" class="btn btn-secondary" onclick="closeCommunicationModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
const templates = @json($templates);

function openCommunicationModal(leadId, firstName, lastName, companyName, email, phone, assignmentAction) {
    document.getElementById('modal_lead_id').value = leadId;
    document.getElementById('modal_lead_name').textContent = firstName + ' ' + lastName + (companyName ? ' - ' + companyName : '');
    document.getElementById('modal_lead_email').textContent = email || 'No email';
    document.getElementById('modal_lead_phone').textContent = phone || 'No phone';
    document.getElementById('comm_to_email').value = email || '';
    document.getElementById('comm_to_phone').value = phone || '';
    
    // Pre-select category based on assignment action
    const categoryMap = {
        'call': 'voice_call',
        'sms': 'sms',
        'email': 'email',
        'whatsapp': 'sms',
        'online_meeting': 'video_call',
        'personal_visit': 'visit'
    };
    
    if (categoryMap[assignmentAction]) {
        document.getElementById('comm_category').value = categoryMap[assignmentAction];
        updateTemplateOptions();
    }
    
    document.getElementById('communicationModal').style.display = 'block';
}

function closeCommunicationModal() {
    document.getElementById('communicationModal').style.display = 'none';
    document.getElementById('communicationForm').reset();
}

function updateTemplateOptions() {
    const category = document.getElementById('comm_category').value;
    const templateSelect = document.getElementById('template_select');
    
    // Clear existing options except the first one
    templateSelect.innerHTML = '<option value="">-- No Template --</option>';
    
    // Show/hide relevant fields based on category
    const emailGroup = document.getElementById('email_group');
    const phoneGroup = document.getElementById('phone_group');
    const durationGroup = document.getElementById('duration_group');
    const visitReportGroup = document.getElementById('visit_report_group');
    const subjectGroup = document.getElementById('subject_group');
    
    emailGroup.style.display = (category === 'email' || category === 'letter') ? 'block' : 'none';
    phoneGroup.style.display = (category === 'sms' || category === 'voice_call' || category === 'video_call') ? 'block' : 'none';
    durationGroup.style.display = (category === 'voice_call' || category === 'video_call' || category === 'visit') ? 'block' : 'none';
    visitReportGroup.style.display = category === 'visit' ? 'block' : 'none';
    subjectGroup.style.display = (category === 'email' || category === 'letter') ? 'block' : 'none';
    
    // Map category to template category
    const categoryMap = {
        'email': 'email',
        'sms': 'sms',
        'letter': 'letter',
        'voice_call': 'call_script',
        'video_call': 'call_script',
        'visit': 'visit_report'
    };
    
    const templateCategory = categoryMap[category];
    if (templateCategory && templates[templateCategory]) {
        templates[templateCategory].forEach(template => {
            const option = document.createElement('option');
            option.value = template.id;
            option.textContent = template.name + (template.is_official ? ' ⭐' : '');
            templateSelect.appendChild(option);
        });
    }
}

function loadTemplate() {
    const templateId = document.getElementById('template_select').value;
    if (!templateId) return;
    
    fetch('{{ url('/email-templates/get/templates') }}?category=' + document.getElementById('comm_category').value)
        .then(response => response.json())
        .then(data => {
            const template = data.find(t => t.id == templateId);
            if (template) {
                // Replace variables with lead data
                const leadName = document.getElementById('modal_lead_name').textContent;
                const leadEmail = document.getElementById('comm_to_email').value || '';
                const leadPhone = document.getElementById('comm_to_phone').value || '';
                
                let subject = template.subject || '';
                let body = template.body || '';
                
                // Replace common variables
                subject = subject.replace(/{lead_name}/g, leadName)
                    .replace(/{email}/g, leadEmail)
                    .replace(/{phone}/g, leadPhone);
                
                body = body.replace(/{lead_name}/g, leadName)
                    .replace(/{email}/g, leadEmail)
                    .replace(/{phone}/g, leadPhone);
                
                document.getElementById('comm_subject').value = subject;
                document.getElementById('comm_content').value = body;
            }
        })
        .catch(error => {
            console.error('Error loading template:', error);
        });
}

function viewHistory(leadId) {
    const historyRow = document.getElementById('history-' + leadId);
    historyRow.style.display = historyRow.style.display === 'none' ? 'table-row' : 'none';
}

function resetFilters() {
    document.getElementById('filter-form').reset();
    window.location.href = '{{ route('communications.index') }}';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('communicationModal');
    if (event.target == modal) {
        closeCommunicationModal();
    }
}
</script>
@endsection
