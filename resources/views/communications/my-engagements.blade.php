@extends('layouts.app')

@section('title', 'My Engagements')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">My Engagements</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'engagements.my']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
            <div><strong>Total Assigned:</strong> {{ $leads->total() }}</div>
            <div><strong>Pending:</strong> {{ $leads->where('assignment_deadline', '>=', now())->count() }}</div>
            <div><strong>Overdue:</strong> {{ $leads->where('assignment_deadline', '<', now())->count() }}</div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Company</th>
                    <th>Action Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Engagement Result</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $engagement = $engagementResults[$lead->id] ?? null;
                    $isOverdue = $lead->assignment_deadline && $lead->assignment_deadline < now() && !$engagement;
                @endphp
                <tr>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->company_name }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #0078d4; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $lead->assignment_action)) }}
                        </span>
                    </td>
                    <td style="color: {{ $isOverdue ? '#d13438' : '#201f1e' }};">
                        {{ $lead->assignment_deadline ? $lead->assignment_deadline->format('Y-m-d H:i') : '-' }}
                    </td>
                    <td>
                        @if($engagement)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #107c10; color: white;">Completed</span>
                        @elseif($isOverdue)
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #d13438; color: white;">Overdue</span>
                        @else
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; background: #ffaa44; color: white;">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($engagement)
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $engagement->engagement_outcome }}">
                                {{ Str::limit($engagement->engagement_outcome, 50) }}
                            </div>
                        @else
                            <span style="color: #8a8886;">Not recorded</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            @if(!$engagement)
                                <button type="button" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;" onclick="openEngagementModal({{ $lead->id }}, '{{ $lead->first_name }} {{ $lead->last_name }}', '{{ $lead->assignment_action }}')">Record Result</button>
                            @else
                                <a href="{{ route('communications.show', $engagement) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">View Result</a>
                            @endif
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

<!-- Engagement Result Modal -->
<div id="engagementModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 24px; border-radius: 4px; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: var(--ms-blue, #0078d4);">Record Engagement Result</h2>
            <button type="button" onclick="closeEngagementModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
        </div>
        <form id="engagementForm" method="POST" action="{{ route('communications.store') }}">
            @csrf
            <input type="hidden" name="assigned_lead_id" id="engagement_lead_id">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Lead:</label>
                <div id="engagementLeadName" style="padding: 8px; background: #f3f2f1; border-radius: 2px; color: #201f1e;"></div>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="engagement_type" style="display: block; margin-bottom: 5px; font-weight: 600;">Action Type *</label>
                <select id="engagement_type" name="type" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;">
                    <option value="call">Call</option>
                    <option value="sms">SMS</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="online_meeting">Online Meeting</option>
                    <option value="personal_visit">Personal Visit</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="engagement_date" style="display: block; margin-bottom: 5px; font-weight: 600;">Engagement Date *</label>
                <input type="datetime-local" id="engagement_date" name="engagement_date" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;" value="{{ date('Y-m-d\TH:i') }}">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="engagement_outcome" style="display: block; margin-bottom: 5px; font-weight: 600;">Outcome/Result *</label>
                <textarea id="engagement_outcome" name="engagement_outcome" rows="5" required style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;" placeholder="Describe the outcome of the engagement..."></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="engagement_duration" style="display: block; margin-bottom: 5px; font-weight: 600;">Duration (minutes)</label>
                <input type="number" id="engagement_duration" name="duration_minutes" min="0" style="width: 100%; padding: 8px; border: 1px solid #8a8886; border-radius: 2px; font-size: 14px;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeEngagementModal()" class="btn btn-secondary" style="padding: 8px 16px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Submit Result</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEngagementModal(leadId, leadName, actionType) {
    document.getElementById('engagementLeadName').textContent = leadName;
    document.getElementById('engagement_lead_id').value = leadId;
    document.getElementById('engagement_type').value = actionType;
    document.getElementById('engagementModal').style.display = 'block';
}

function closeEngagementModal() {
    document.getElementById('engagementModal').style.display = 'none';
    document.getElementById('engagementForm').reset();
}

window.onclick = function(event) {
    const modal = document.getElementById('engagementModal');
    if (event.target == modal) {
        closeEngagementModal();
    }
}
</script>
@endsection
