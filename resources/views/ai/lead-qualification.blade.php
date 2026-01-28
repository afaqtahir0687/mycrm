@extends('layouts.app')

@section('title', 'AI Lead Qualification')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">AI Lead Qualification</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'ai.lead-qualification']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary">View All Leads</a>
    </div>
    
    <div class="summary-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <div style="font-size: 12px; color: var(--ms-gray-80, #8a8886); margin-bottom: 4px;">Total Leads</div>
                <div style="font-size: 24px; font-weight: 600; color: var(--ms-gray-120, #201f1e);">{{ $summary['total'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--ms-gray-80, #8a8886); margin-bottom: 4px;">Scored Leads</div>
                <div style="font-size: 24px; font-weight: 600; color: var(--ms-blue, #0078d4);">{{ $summary['scored'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--ms-gray-80, #8a8886); margin-bottom: 4px;">High Score (70+)</div>
                <div style="font-size: 24px; font-weight: 600; color: var(--ms-success, #107c10);">{{ $summary['high_score'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--ms-gray-80, #8a8886); margin-bottom: 4px;">Medium Score (40-69)</div>
                <div style="font-size: 24px; font-weight: 600; color: var(--ms-warning, #ffaa44);">{{ $summary['medium_score'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--ms-gray-80, #8a8886); margin-bottom: 4px;">Low Score (&lt;40)</div>
                <div style="font-size: 24px; font-weight: 600; color: var(--ms-error, #d13438);">{{ $summary['low_score'] }}</div>
            </div>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('ai.lead-qualification') }}" id="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Minimum AI Score:</label>
                    <select name="min_score" class="form-control" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All Scores</option>
                        <option value="70" {{ request('min_score') == '70' ? 'selected' : '' }}>High (70+)</option>
                        <option value="40" {{ request('min_score') == '40' ? 'selected' : '' }}>Medium+ (40+)</option>
                        <option value="0" {{ request('min_score') == '0' ? 'selected' : '' }}>Low+ (0+)</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status" class="form-control" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select name="sort_by" class="form-control" onchange="document.getElementById('filter-form').submit();">
                        <option value="ai_score" {{ request('sort_by') == 'ai_score' ? 'selected' : '' }}>AI Score</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>Name</option>
                        <option value="company_name" {{ request('sort_by') == 'company_name' ? 'selected' : '' }}>Company</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Order:</label>
                    <select name="sort_order" class="form-control" onchange="document.getElementById('filter-form').submit();">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>AI Score</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->company_name ?? 'N/A' }}</td>
                    <td>{{ $lead->email ?? 'N/A' }}</td>
                    <td>{{ $lead->phone ?? 'N/A' }}</td>
                    <td>
                        @if($lead->ai_score !== null)
                            <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; font-weight: 600; background: 
                                @if($lead->ai_score >= 70) var(--ms-success, #107c10)
                                @elseif($lead->ai_score >= 40) var(--ms-warning, #ffaa44)
                                @else var(--ms-error, #d13438)
                                @endif; color: white;">
                                {{ $lead->ai_score }}/100
                            </span>
                        @else
                            <button onclick="scoreLead({{ $lead->id }})" class="btn btn-secondary" style="padding: 4px 12px; font-size: 12px;">Score Lead</button>
                        @endif
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background: 
                            @if($lead->status == 'new') #2196f3
                            @elseif($lead->status == 'contacted') #ff9800
                            @elseif($lead->status == 'qualified') #4caf50
                            @elseif($lead->status == 'converted') #8bc34a
                            @else #f44336
                            @endif; color: white;">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            @if($lead->ai_score !== null)
                                <button onclick="viewAIDetails({{ $lead->id }})" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">AI Details</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $leads->links() }}
    </div>
</div>

<script>
function scoreLead(leadId) {
    if (!confirm('Score this lead using AI?')) {
        return;
    }
    
    fetch('{{ url('/ai/score-lead') }}/' + leadId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Lead scored! AI Score: ' + data.score + '/100\n\nFactors:\n' + data.factors.join('\n'));
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error scoring lead. Please try again.');
    });
}

function viewAIDetails(leadId) {
    window.location.href = '{{ url('/leads') }}/' + leadId;
}
</script>
@endsection
