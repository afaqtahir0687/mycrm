@extends('layouts.app')

@section('title', 'Edit Agreement')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Agreement</h1>
        <a href="{{ route('agreements.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <form method="POST" action="{{ route('agreements.update', $agreement) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Agreement Number *</label>
                <input type="text" name="agreement_number" value="{{ old('agreement_number', $agreement->agreement_number) }}" required>
                @error('agreement_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Agreement Type *</label>
                <select name="agreement_type" required>
                    <option value="STC" {{ old('agreement_type', $agreement->agreement_type) == 'STC' ? 'selected' : '' }}>STC</option>
                    <option value="SLA" {{ old('agreement_type', $agreement->agreement_type) == 'SLA' ? 'selected' : '' }}>SLA</option>
                    <option value="Agreement Draft" {{ old('agreement_type', $agreement->agreement_type) == 'Agreement Draft' ? 'selected' : '' }}>Agreement Draft</option>
                </select>
                @error('agreement_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Quotation</label>
                <select name="quotation_id" class="form-control">
                    <option value="">Select Quotation</option>
                    @foreach($quotations as $quotation)
                        <option value="{{ $quotation->id }}" {{ old('quotation_id', $agreement->quotation_id) == $quotation->id ? 'selected' : '' }}>
                            {{ $quotation->quotation_number }}
                        </option>
                    @endforeach
                </select>
                @error('quotation_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Deal</label>
                <select name="deal_id" class="form-control">
                    <option value="">Select Deal</option>
                    @foreach($deals as $deal)
                        <option value="{{ $deal->id }}" {{ old('deal_id', $agreement->deal_id) == $deal->id ? 'selected' : '' }}>
                            {{ $deal->deal_name }}
                        </option>
                    @endforeach
                </select>
                @error('deal_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Account</label>
                <select name="account_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $agreement->account_id) == $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Contact</label>
                <select name="contact_id" class="form-control">
                    <option value="">Select Contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id', $agreement->contact_id) == $contact->id ? 'selected' : '' }}>
                            {{ $contact->first_name }} {{ $contact->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('contact_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Agreement Date *</label>
                <input type="date" name="agreement_date" value="{{ old('agreement_date', $agreement->agreement_date?->format('Y-m-d')) }}" required>
                @error('agreement_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', $agreement->start_date?->format('Y-m-d')) }}">
                @error('start_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $agreement->end_date?->format('Y-m-d')) }}">
                @error('end_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="draft" {{ old('status', $agreement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status', $agreement->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="signed" {{ old('status', $agreement->status) == 'signed' ? 'selected' : '' }}>Signed</option>
                    <option value="active" {{ old('status', $agreement->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ old('status', $agreement->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="terminated" {{ old('status', $agreement->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Total Value</label>
                <input type="number" step="0.01" name="total_value" value="{{ old('total_value', $agreement->total_value) }}">
                @error('total_value')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Currency</label>
                <select name="currency">
                    <option value="USD" {{ old('currency', $agreement->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency', $agreement->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency', $agreement->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                </select>
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Agreement File (PDF, DOC, DOCX)</label>
                @if($agreement->agreement_file_path)
                    <div style="margin-bottom: 10px;">
                        <a href="{{ asset('storage/' . $agreement->agreement_file_path) }}" target="_blank">
                            {{ basename($agreement->agreement_file_path) }}
                        </a>
                    </div>
                @endif
                <input type="file" name="agreement_file" class="form-control" accept=".pdf,.doc,.docx">
                @error('agreement_file')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Terms & Conditions</label>
                <textarea name="terms_conditions" rows="3">{{ old('terms_conditions', $agreement->terms_conditions) }}</textarea>
                @error('terms_conditions')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>SLA Terms</label>
                <textarea name="sla_terms" rows="3">{{ old('sla_terms', $agreement->sla_terms) }}</textarea>
                @error('sla_terms')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Deliverables</label>
                <textarea name="deliverables" rows="3">{{ old('deliverables', $agreement->deliverables) }}</textarea>
                @error('deliverables')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" rows="3">{{ old('notes', $agreement->notes) }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Agreement</button>
            <a href="{{ route('agreements.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


