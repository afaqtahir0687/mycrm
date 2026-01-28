@extends('layouts.app')

@section('title', 'Register New Client')

@section('content')
<style>
    .client-type-selector {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f3f2f1;
        border-radius: 4px;
    }
    .client-type-option {
        flex: 1;
        padding: 15px;
        border: 2px solid #8a8886;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }
    .client-type-option:hover {
        border-color: #0078d4;
        background: #e8f4f8;
    }
    .client-type-option input[type="radio"] {
        margin-right: 8px;
    }
    .client-type-option.active {
        border-color: #0078d4;
        background: #e8f4f8;
    }
    .form-section {
        display: none;
    }
    .form-section.active {
        display: block;
    }
</style>

<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Register New Client</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'client-registration.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('client-registration.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('client-registration.store') }}" id="clientForm" onsubmit="return validateForm()">
        @csrf
        <input type="hidden" name="client_type" id="client_type" value="{{ old('client_type', $preSelected['client_type'] ?? 'contact') }}">
        
        <div class="client-type-selector">
            <label class="client-type-option {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'contact' ? 'active' : '' }}" onclick="selectClientType('contact')">
                <input type="radio" name="client_type_radio" value="contact" {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'contact' ? 'checked' : '' }} onchange="selectClientType('contact')">
                <strong>Individual Contact</strong>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">Register a person/individual</div>
            </label>
            <label class="client-type-option {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'account' ? 'active' : '' }}" onclick="selectClientType('account')">
                <input type="radio" name="client_type_radio" value="account" {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'account' ? 'checked' : '' }} onchange="selectClientType('account')">
                <strong>Company Account</strong>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">Register a company/organization</div>
            </label>
        </div>
        
        <!-- Contact Form Section -->
        <div id="contact-section" class="form-section {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'contact' ? 'active' : '' }}">
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
                    <label>Account (Company)</label>
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
        </div>
        
        <!-- Account Form Section -->
        <div id="account-section" class="form-section {{ old('client_type', $preSelected['client_type'] ?? 'contact') == 'account' ? 'active' : '' }}">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Account Name *</label>
                    <input type="text" name="account_name" id="account_name" value="{{ old('account_name') }}" required>
                    @error('account_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Account Type</label>
                    <input type="text" name="account_type" value="{{ old('account_type') }}" placeholder="e.g., Customer, Partner">
                    @error('account_type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Industry</label>
                    <input type="text" name="industry" id="industry" value="{{ old('industry') }}">
                    @error('industry')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="account_email" value="{{ old('email') }}">
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="account_phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}">
                    @error('website')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Billing Address</label>
                    <textarea name="billing_address" id="billing_address" rows="2">{{ old('billing_address') }}</textarea>
                    @error('billing_address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Billing City</label>
                    <input type="text" name="billing_city" value="{{ old('billing_city') }}">
                    @error('billing_city')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Billing State</label>
                    <input type="text" name="billing_state" value="{{ old('billing_state') }}">
                    @error('billing_state')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Billing Country</label>
                    <input type="text" name="billing_country" value="{{ old('billing_country') }}">
                    @error('billing_country')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Billing Postal Code</label>
                    <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}">
                    @error('billing_postal_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Shipping Address</label>
                    <textarea name="shipping_address" rows="2">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Shipping City</label>
                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}">
                    @error('shipping_city')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Shipping State</label>
                    <input type="text" name="shipping_state" value="{{ old('shipping_state') }}">
                    @error('shipping_state')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Shipping Country</label>
                    <input type="text" name="shipping_country" value="{{ old('shipping_country') }}">
                    @error('shipping_country')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Shipping Postal Code</label>
                    <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                    @error('shipping_postal_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Employees</label>
                    <input type="number" name="employees" value="{{ old('employees') }}" min="0">
                    @error('employees')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Annual Revenue</label>
                    <input type="number" name="annual_revenue" value="{{ old('annual_revenue') }}" step="0.01" min="0">
                    @error('annual_revenue')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Owner</label>
                    <select name="owner_id" class="form-control">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('owner_id', $preSelected['owner_id'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('owner_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Description</label>
                    <textarea name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary" id="submitBtn">Register Client</button>
            <a href="{{ route('client-registration.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function selectClientType(type) {
    document.getElementById('client_type').value = type;
    
    // Update radio buttons
    document.querySelectorAll('input[name="client_type_radio"]').forEach(radio => {
        radio.checked = radio.value === type;
    });
    
    // Update visual selection
    document.querySelectorAll('.client-type-option').forEach(option => {
        option.classList.remove('active');
        if (option.querySelector('input[value="' + type + '"]')) {
            option.classList.add('active');
        }
    });
    
    // Show/hide form sections
    document.getElementById('contact-section').classList.toggle('active', type === 'contact');
    document.getElementById('account-section').classList.toggle('active', type === 'account');
    
    // Update submit button text
    document.getElementById('submitBtn').textContent = type === 'contact' ? 'Create Contact' : 'Create Account';
    
    // Update required fields
    if (type === 'contact') {
        const firstNameField = document.getElementById('first_name');
        const accountNameField = document.getElementById('account_name');
        if (firstNameField) firstNameField.required = true;
        if (accountNameField) accountNameField.required = false;
    } else {
        const firstNameField = document.getElementById('first_name');
        const accountNameField = document.getElementById('account_name');
        if (firstNameField) firstNameField.required = false;
        if (accountNameField) accountNameField.required = true;
    }
}

function populateFromLead(leadId) {
    if (!leadId) {
        return;
    }
    
    const leadSelect = document.getElementById('lead-select');
    const originalValue = leadSelect.value;
    leadSelect.disabled = true;
    
    const url = '{{ url('/client-registration/get-lead-data') }}/' + leadId;
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Populate contact fields
        if (data.first_name) document.getElementById('first_name').value = data.first_name;
        if (data.last_name) document.getElementById('last_name').value = data.last_name;
        if (data.email) {
            document.getElementById('email').value = data.email;
            document.getElementById('account_email').value = data.email;
        }
        if (data.phone) {
            document.getElementById('phone').value = data.phone;
            document.getElementById('account_phone').value = data.phone;
        }
        if (data.mobile) document.getElementById('mobile').value = data.mobile;
        if (data.address) {
            document.getElementById('address').value = data.address;
            document.getElementById('billing_address').value = data.address;
        }
        if (data.city) document.getElementById('city').value = data.city;
        if (data.state) document.getElementById('state').value = data.state;
        if (data.country) document.getElementById('country').value = data.country;
        if (data.postal_code) document.getElementById('postal_code').value = data.postal_code;
        if (data.notes) document.getElementById('notes').value = data.notes;
        
        // If company name exists, populate account fields and suggest switching to account
        if (data.company_name) {
            document.getElementById('account_name').value = data.company_name;
            document.getElementById('website').value = data.website || '';
            document.getElementById('industry').value = data.industry || '';
            
            // Try to find matching account
            const accountSelect = document.querySelector('select[name="account_id"]');
            if (accountSelect) {
                for (let option of accountSelect.options) {
                    if (option.text.toLowerCase().includes(data.company_name.toLowerCase()) || 
                        data.company_name.toLowerCase().includes(option.text.toLowerCase())) {
                        accountSelect.value = option.value;
                        break;
                    }
                }
            }
        }
        
        if (data.assigned_to) {
            const assignedSelect = document.querySelector('select[name="assigned_to"]');
            const ownerSelect = document.querySelector('select[name="owner_id"]');
            if (assignedSelect) assignedSelect.value = data.assigned_to;
            if (ownerSelect) ownerSelect.value = data.assigned_to;
        }
        
        showNotification('Lead data populated successfully!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading lead data.', 'error');
        leadSelect.value = originalValue;
    })
    .finally(() => {
        leadSelect.disabled = false;
    });
}

function showNotification(message, type) {
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
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function validateForm() {
    const clientType = document.getElementById('client_type').value;
    
    if (clientType === 'contact') {
        const firstName = document.getElementById('first_name').value.trim();
        if (!firstName) {
            alert('First Name is required for Contact registration.');
            document.getElementById('first_name').focus();
            return false;
        }
    } else {
        const accountName = document.getElementById('account_name').value.trim();
        if (!accountName) {
            alert('Account Name is required for Account registration.');
            document.getElementById('account_name').focus();
            return false;
        }
    }
    
    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const clientType = document.getElementById('client_type').value;
    selectClientType(clientType);
    
    const leadSelect = document.getElementById('lead-select');
    if (leadSelect && leadSelect.value) {
        populateFromLead(leadSelect.value);
    }
});
</script>
@endsection
