<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::query()->with(['account', 'contact', 'owner']);
        
        if ($request->filled('search')) {
            $query->where('opportunity_name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Opportunity::count(),
            'open' => Opportunity::where('status', 'open')->count(),
            'won' => Opportunity::where('status', 'won')->count(),
            'lost' => Opportunity::where('status', 'lost')->count(),
            'total_value' => Opportunity::where('status', 'open')->sum('amount'),
        ];
        
        $opportunities = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('opportunities.index', compact('opportunities', 'summary', 'accounts', 'contacts', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'owner_id' => $request->get('owner_id'),
        ];
        
        return view('opportunities.create', compact('accounts', 'contacts', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'opportunity_name' => 'required|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'close_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability' => 'nullable|integer|min:0|max:100',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:open,won,lost',
        ]);
        
        Opportunity::create($validated);
        return redirect()->route('opportunities.index')->with('success', 'Opportunity created successfully.');
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load(['account', 'contact', 'owner']);
        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $users = User::where('is_active', true)->get();
        return view('opportunities.edit', compact('opportunity', 'accounts', 'contacts', 'users'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'opportunity_name' => 'required|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'close_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability' => 'nullable|integer|min:0|max:100',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:open,won,lost',
        ]);
        
        $opportunity->update($validated);
        return redirect()->route('opportunities.index')->with('success', 'Opportunity updated successfully.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();
        return redirect()->route('opportunities.index')->with('success', 'Opportunity deleted successfully.');
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
