<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Account;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->with(['account', 'assignedUser']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Contact::count(),
            'active' => Contact::where('status', 'active')->count(),
            'inactive' => Contact::where('status', 'inactive')->count(),
        ];
        
        $contacts = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'users' => $users,
        ];
        
        return view('contacts.index', compact('contacts', 'summary', 'accounts', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $leads = Lead::orderBy('first_name')->orderBy('company_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'lead_id' => $request->get('lead_id'),
            'account_id' => $request->get('account_id'),
            'assigned_to' => $request->get('assigned_to'),
        ];
        
        return view('contacts.create', compact('accounts', 'leads', 'users', 'preSelected'));
    }
    
    public function getLeadData(Lead $lead)
    {
        return response()->json([
            'first_name' => $lead->first_name ?? '',
            'last_name' => $lead->last_name ?? '',
            'email' => $lead->email ?? '',
            'phone' => $lead->phone ?? '',
            'mobile' => $lead->mobile ?? $lead->phone ?? '',
            'address' => $lead->address ?? '',
            'city' => $lead->city ?? '',
            'state' => $lead->state ?? '',
            'country' => $lead->country ?? '',
            'postal_code' => $lead->postal_code ?? '',
            'company_name' => $lead->company_name ?? '',
            'website' => $lead->website ?? '',
            'industry' => $lead->industry ?? '',
            'notes' => $lead->notes ?? '',
            'assigned_to' => $lead->assigned_to ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'notes' => 'nullable|string',
            'account_id' => 'nullable|exists:accounts,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        Contact::create($validated);
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact)
    {
        $contact->load(['account', 'assignedUser']);
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $accounts = Account::all();
        $users = User::where('is_active', true)->get();
        return view('contacts.edit', compact('contact', 'accounts', 'users'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'notes' => 'nullable|string',
            'account_id' => 'nullable|exists:accounts,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        $contact->update($validated);
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
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
