<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with(['role']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];
        
        $users = $query->paginate(50);
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'roles' => $roles,
        ];
        
        return view('users.index', compact('users', 'summary', 'roles', 'filterOptions'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Password will be automatically hashed by the model's 'hashed' cast
        $validated['is_active'] = $request->has('is_active');
        
        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['role']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        // Password will be automatically hashed by the model's 'hashed' cast if provided
        
        $validated['is_active'] = $request->has('is_active');
        
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    
    public function exportExcel()
    {
        return response()->json(['message' => 'Excel export functionality requires maatwebsite/excel package']);
    }
    
    public function exportPdf()
    {
        return response()->json(['message' => 'PDF export functionality requires barryvdh/laravel-dompdf package']);
    }
    
    public function importExcel(Request $request)
    {
        return response()->json(['message' => 'Excel import functionality requires maatwebsite/excel package']);
    }
}
