<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::query()->with(['account', 'contact', 'lead', 'owner']);
        
        if ($request->filled('search')) {
            $query->where('deal_name', 'like', '%' . $request->search . '%');
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
            'total' => Deal::count(),
            'open' => Deal::where('status', 'open')->count(),
            'won' => Deal::where('status', 'won')->count(),
            'lost' => Deal::where('status', 'lost')->count(),
            'total_value' => Deal::where('status', 'open')->sum('amount'),
        ];
        
        $deals = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $leads = Lead::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('deals.index', compact('deals', 'summary', 'accounts', 'contacts', 'leads', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $leads = Lead::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'lead_id' => $request->get('lead_id'),
            'owner_id' => $request->get('owner_id'),
        ];
        
        return view('deals.create', compact('accounts', 'contacts', 'leads', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deal_name' => 'required|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'expected_close_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:open,won,lost',
        ]);
        
        Deal::create($validated);
        return redirect()->route('deals.index')->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal)
    {
        $deal->load(['account', 'contact', 'lead', 'owner']);
        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $leads = Lead::all();
        $users = User::where('is_active', true)->get();
        return view('deals.edit', compact('deal', 'accounts', 'contacts', 'leads', 'users'));
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'deal_name' => 'required|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'expected_close_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost',
            'probability' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'required|in:open,won,lost',
        ]);
        
        $deal->update($validated);
        return redirect()->route('deals.index')->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')->with('success', 'Deal deleted successfully.');
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
