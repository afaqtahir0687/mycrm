<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CRM System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .register-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 520px; }
        h1 { text-align: center; margin-bottom: 24px; color: #333; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; color: #666; font-size: 14px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn-primary { width: 100%; padding: 12px; background: #1976d2; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .btn-primary:hover { background: #1565c0; }
        .error { color: #d32f2f; font-size: 14px; margin-top: 5px; }
        .help { text-align: center; margin-top: 14px; font-size: 14px; color: #666; }
        .help a { color: #1976d2; text-decoration: none; }
        .help a:hover { text-decoration: underline; }
        @media (max-width: 600px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Create CRM Account</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" value="{{ old('position') }}">
                    @error('position')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="role_id">Role</label>
                <select id="role_id" name="role_id">
                    <option value="">Select Role (optional)</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>
            <button type="submit" class="btn-primary">Register</button>
        </form>
        <div class="help">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</body>
</html>


