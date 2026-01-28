@extends('layouts.app')

@section('title', 'Edit Communication Template')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Communication Template</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'email-templates.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('email-templates.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('email-templates.update', $emailTemplate) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Template Name *</label>
                <input type="text" name="name" value="{{ old('name', $emailTemplate->name) }}" required placeholder="e.g., Welcome Email Template">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="email" {{ old('category', $emailTemplate->category ?? 'email') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="sms" {{ old('category', $emailTemplate->category ?? '') == 'sms' ? 'selected' : '' }}>SMS</option>
                    <option value="letter" {{ old('category', $emailTemplate->category ?? '') == 'letter' ? 'selected' : '' }}>Letter/Official Message</option>
                    <option value="call_script" {{ old('category', $emailTemplate->category ?? '') == 'call_script' ? 'selected' : '' }}>Call Script</option>
                    <option value="visit_report" {{ old('category', $emailTemplate->category ?? '') == 'visit_report' ? 'selected' : '' }}>Visit Report</option>
                </select>
                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Template Type *</label>
                <select name="type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="general" {{ old('type', $emailTemplate->type) == 'general' ? 'selected' : '' }}>General</option>
                    <option value="welcome" {{ old('type', $emailTemplate->type) == 'welcome' ? 'selected' : '' }}>Welcome</option>
                    <option value="followup" {{ old('type', $emailTemplate->type) == 'followup' ? 'selected' : '' }}>Follow-up</option>
                    <option value="quotation" {{ old('type', $emailTemplate->type) == 'quotation' ? 'selected' : '' }}>Quotation</option>
                    <option value="invoice" {{ old('type', $emailTemplate->type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="reminder" {{ old('type', $emailTemplate->type) == 'reminder' ? 'selected' : '' }}>Reminder</option>
                    <option value="official_letter" {{ old('type', $emailTemplate->type) == 'official_letter' ? 'selected' : '' }}>Official Letter</option>
                </select>
                @error('type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Is Official Template?</label>
                <div style="margin-top: 5px;">
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="checkbox" name="is_official" value="1" {{ old('is_official', $emailTemplate->is_official ?? false) ? 'checked' : '' }}>
                        <span>Mark as Official Letter/Message Template ⭐</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group" id="subject_group">
                <label>Subject</label>
                <input type="text" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" placeholder="e.g., Welcome to {company_name}">
                <small style="color: #666; font-size: 11px; margin-top: 4px; display: block;">
                    Use variables like {lead_name}, {company_name}, {email}, {phone} to personalize messages.
                </small>
                @error('subject')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Template File (Optional)</label>
                @if($emailTemplate->file_path)
                    <div style="margin-bottom: 5px; padding: 5px; background: #f3f2f1; border-radius: 2px;">
                        <span>Current file:</span>
                        <a href="{{ Storage::url($emailTemplate->file_path) }}" target="_blank" style="margin-left: 5px;">View Current File</a>
                    </div>
                @endif
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                <small style="color: #666; font-size: 11px; margin-top: 4px; display: block;">
                    Upload a new document template to replace existing one (PDF, DOC, DOCX). Max size: 5MB.
                </small>
                @error('file')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Template Body/Content *</label>
                <textarea name="body" rows="10" class="form-control" required placeholder="Enter template content here. Use variables like {lead_name}, {company_name}, {email}, etc.">{{ old('body', $emailTemplate->body) }}</textarea>
                <small style="color: #666; font-size: 11px; margin-top: 4px; display: block;">
                    <strong>Available Variables:</strong> {lead_name}, {first_name}, {last_name}, {company_name}, {email}, {phone}, {address}, {city}, {country}
                    <br>Variables will be automatically detected and replaced with actual lead data when using the template.
                </small>
                @error('body')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <div style="margin-top: 5px;">
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}>
                        <span>Active (Template will be available for use)</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Template</button>
            <a href="{{ route('email-templates.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('select[name="category"]');
    const subjectGroup = document.getElementById('subject_group');
    
    categorySelect.addEventListener('change', function() {
        if (this.value === 'sms' || this.value === 'call_script') {
            subjectGroup.style.display = 'none';
        } else {
            subjectGroup.style.display = 'block';
        }
    });
    
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
