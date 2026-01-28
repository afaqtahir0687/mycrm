<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::query()->with(['owner']);
        
        if ($request->filled('search')) {
            $query->where('account_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }
        
        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }
        
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Account::count(),
            'active' => Account::where('status', 'active')->count(),
            'inactive' => Account::where('status', 'inactive')->count(),
        ];
        
        $accounts = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'industries' => Account::select('industry')->distinct()->whereNotNull('industry')->orderBy('industry')->pluck('industry'),
            'account_types' => Account::select('account_type')->distinct()->whereNotNull('account_type')->orderBy('account_type')->pluck('account_type'),
            'users' => $users,
        ];
        
        return view('accounts.index', compact('accounts', 'summary', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $users = \App\Models\User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'owner_id' => $request->get('owner_id'),
        ];
        
        return view('accounts.create', compact('users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'billing_address' => 'nullable|string',
            'billing_city' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_postal_code' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'owner_id' => 'nullable|exists:users,id',
        ]);
        
        Account::create($validated);
        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load(['owner']);
        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $users = \App\Models\User::where('is_active', true)->get();
        return view('accounts.edit', compact('account', 'users'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'billing_address' => 'nullable|string',
            'billing_city' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_postal_code' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'owner_id' => 'nullable|exists:users,id',
        ]);
        
        $account->update($validated);
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
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
