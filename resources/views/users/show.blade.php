@extends('layouts.app')

@section('title', 'View Users')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">View Users Details</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'users.show']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</div>
        <div><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</div>
        <div><strong>Role:</strong> {{ $user->role->name ?? 'N/A' }}</div>
        <div><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</div>
        <div><strong>Position:</strong> {{ $user->position ?? 'N/A' }}</div>
        <div><strong>Is Active:</strong> {{ $user->is_active ? 'Yes' : 'No' }}</div>

        <div><strong>Created Date:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</div>
        <div><strong>Last Updated:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
    </div>
</div>
@endsection