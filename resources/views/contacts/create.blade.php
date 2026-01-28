@extends('layouts.app')

@section('title', 'Create Contact')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Contact</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'contacts.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('contacts.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Select Lead to Populate Data (Optional)</label>
                <select id="lead-select" class="form-control" onchange="populateFromLead(this.value)">
                    <option value="">-- Select a Lead --</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead->id }}" {{ old('lead_id', $preSelected['lead_id'] ?? '') == $lead->id ? 'selected' : '' }}>
                            {{ $lead->first_name }} {{ $lead->last_name }} 
                            @if($lead->company_name) - {{ $lead->company_name }}@endif
                            @if($lead->email) ({{ $lead->email }})@endif
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; font-size: 12px; margin-top: 4px; display: block;">
                    Select a lead to automatically populate contact information from the lead's data.
                </small>
            </div>
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}">
                @error('last_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}">
                @error('mobile')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department" value="{{ old('department') }}">
                @error('department')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $preSelected['account_id'] ?? '') == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Address</label>
                <textarea name="address" id="address" rows="2">{{ old('address') }}</textarea>
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}">
                @error('city')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" id="state" value="{{ old('state') }}">
                @error('state')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" id="country" value="{{ old('country') }}">
                @error('country')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
                @error('postal_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Birthdate</label>
                <input type="date" name="birthdate" value="{{ old('birthdate') }}">
                @error('birthdate')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Assigned To</label>
                <select name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $preSelected['assigned_to'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" id="notes" rows="4">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Contact</button>
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function populateFromLead(leadId) {
    if (!leadId) {
        return;
    }
    
    // Show loading indicator
    const leadSelect = document.getElementById('lead-select');
    const originalValue = leadSelect.value;
    leadSelect.disabled = true;
    
    // Fetch lead data
    const url = '{{ url('/contacts/get-lead-data') }}/' + leadId;
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Populate form fields
        if (data.first_name) document.getElementById('first_name').value = data.first_name;
        if (data.last_name) document.getElementById('last_name').value = data.last_name;
        if (data.email) document.getElementById('email').value = data.email;
        if (data.phone) document.getElementById('phone').value = data.phone;
        if (data.mobile) document.getElementById('mobile').value = data.mobile;
        if (data.address) document.getElementById('address').value = data.address;
        if (data.city) document.getElementById('city').value = data.city;
        if (data.state) document.getElementById('state').value = data.state;
        if (data.country) document.getElementById('country').value = data.country;
        if (data.postal_code) document.getElementById('postal_code').value = data.postal_code;
        if (data.notes) document.getElementById('notes').value = data.notes;
        
        // If there's a company name, try to find and select matching account
        if (data.company_name) {
            const accountSelect = document.querySelector('select[name="account_id"]');
            if (accountSelect) {
                // Try to find account by name (case-insensitive)
                for (let option of accountSelect.options) {
                    if (option.text.toLowerCase().includes(data.company_name.toLowerCase()) || 
                        data.company_name.toLowerCase().includes(option.text.toLowerCase())) {
                        accountSelect.value = option.value;
                        break;
                    }
                }
            }
        }
        
        // Set assigned_to if available
        if (data.assigned_to) {
            const assignedSelect = document.querySelector('select[name="assigned_to"]');
            if (assignedSelect) {
                assignedSelect.value = data.assigned_to;
            }
        }
        
        // Show success message
        showNotification('Lead data populated successfully!', 'success');
    })
    .catch(error => {
        console.error('Error fetching lead data:', error);
        showNotification('Error loading lead data. Please try again.', 'error');
        leadSelect.value = originalValue;
    })
    .finally(() => {
        leadSelect.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#107c10' : '#d13438'};
        color: white;
        border-radius: 2px;
        box-shadow: 0 3.2px 7.2px rgba(0, 0, 0, 0.13);
        z-index: 10000;
        font-size: 14px;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Auto-populate on page load if lead_id is in query string
document.addEventListener('DOMContentLoaded', function() {
    const leadSelect = document.getElementById('lead-select');
    if (leadSelect && leadSelect.value) {
        populateFromLead(leadSelect.value);
    }
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>
@endsection

