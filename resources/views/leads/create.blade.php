@extends('layouts.app')

@section('title', 'Create Lead')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Create New Lead</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'leads.create']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('help.show', ['form' => 'leads.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575;">?</a>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('leads.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}">
                @error('last_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}">
                @error('company_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" name="mobile" value="{{ old('mobile') }}">
                @error('mobile')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Address</label>
                <textarea name="address" rows="2">{{ old('address') }}</textarea>
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" value="{{ old('city') }}">
                @error('city')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" value="{{ old('state') }}">
                @error('state')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" value="{{ old('country') }}">
                @error('country')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}">
                @error('postal_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" value="{{ old('website') }}">
                @error('website')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Lead Source</label>
                <input type="text" name="lead_source" value="{{ old('lead_source') }}" placeholder="e.g., Website, Referral, Cold Call">
                @error('lead_source')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry" value="{{ old('industry') }}">
                @error('industry')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Lead Score</label>
                <input type="number" name="lead_score" value="{{ old('lead_score', 0) }}" min="0" max="100">
                @error('lead_score')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="converted" {{ old('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Assigned To</label>
                <select name="assigned_to">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create Lead</button>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

