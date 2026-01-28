@extends('layouts.app')

@section('title', 'View Contact')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Contact Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'contacts.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>First Name:</strong> {{ $contact->first_name }}</div>
        <div><strong>Last Name:</strong> {{ $contact->last_name }}</div>
        <div><strong>Title:</strong> {{ $contact->title }}</div>
        <div><strong>Email:</strong> {{ $contact->email }}</div>
        <div><strong>Phone:</strong> {{ $contact->phone }}</div>
        <div><strong>Mobile:</strong> {{ $contact->mobile }}</div>
        <div><strong>Department:</strong> {{ $contact->department }}</div>
        <div><strong>Account:</strong> {{ $contact->account->account_name ?? 'N/A' }}</div>
        <div style="grid-column: 1 / -1;"><strong>Address:</strong> {{ $contact->address }}</div>
        <div><strong>City:</strong> {{ $contact->city }}</div>
        <div><strong>State:</strong> {{ $contact->state }}</div>
        <div><strong>Country:</strong> {{ $contact->country }}</div>
        <div><strong>Postal Code:</strong> {{ $contact->postal_code }}</div>
        <div><strong>Birthdate:</strong> {{ $contact->birthdate ? $contact->birthdate->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($contact->status) }}</div>
        <div><strong>Assigned To:</strong> {{ $contact->assignedUser->name ?? 'Unassigned' }}</div>
        <div><strong>Created Date:</strong> {{ $contact->created_at->format('Y-m-d H:i:s') }}</div>
        @if($contact->notes)
        <div style="grid-column: 1 / -1;"><strong>Notes:</strong><br>{{ $contact->notes }}</div>
        @endif
    </div>
</div>
@endsection

