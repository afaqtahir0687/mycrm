@extends('layouts.app')

@section('title', 'Edit Users')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Edit Users</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'users.edit']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" action="{{ route('users.update', $users) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="{{ old('name', $users->name) }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email', $users->email) }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="">
                <small>Leave blank to keep current password</small>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Role Id</label>
                <select name="role_id" class="form-control">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $users->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name ?? $role->account_name ?? $role->first_name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone </label>
                <input type="text" name="phone" value="{{ old('phone', $users->phone) }}" >
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Position </label>
                <input type="text" name="position" value="{{ old('position', $users->position) }}" >
                @error('position')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $users->is_active) ? 'checked' : '' }}>
                    Is Active
                </label>
                @error('is_active')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update Users</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection