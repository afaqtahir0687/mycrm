<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Account;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class ClientRegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Combine contacts and accounts into a unified view
        $contactQuery = Contact::query()->with(['account', 'assignedUser']);
        $accountQuery = Account::query()->with(['owner']);
        
        if ($request->filled('search')) {
            $contactQuery->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
            
            $accountQuery->where(function($q) use ($request) {
                $q->where('account_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $contactQuery->where('status', $request->status);
            $accountQuery->where('status', $request->status);
        }
        
        if ($request->filled('client_type')) {
            if ($request->client_type == 'contact') {
                $accountQuery->whereRaw('1 = 0'); // Exclude accounts
            } elseif ($request->client_type == 'account') {
                $contactQuery->whereRaw('1 = 0'); // Exclude contacts
            }
        }
        
        // Get all results and combine
        $contacts = $contactQuery->get()->map(function($contact) {
            return (object)[
                'id' => 'contact_' . $contact->id,
                'type' => 'contact',
                'name' => $contact->first_name . ' ' . $contact->last_name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'status' => $contact->status,
                'assigned_to' => $contact->assignedUser->name ?? 'Unassigned',
                'created_at' => $contact->created_at,
                'model' => $contact,
            ];
        });
        
        $accounts = $accountQuery->get()->map(function($account) {
            return (object)[
                'id' => 'account_' . $account->id,
                'type' => 'account',
                'name' => $account->account_name,
                'email' => $account->email,
                'phone' => $account->phone,
                'status' => $account->status,
                'assigned_to' => $account->owner->name ?? 'Unassigned',
                'created_at' => $account->created_at,
                'model' => $account,
            ];
        });
        
        // Combine and sort
        $clients = $contacts->concat($accounts)->sortByDesc('created_at');
        
        // Paginate manually
        $page = $request->get('page', 1);
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        $items = $clients->slice($offset, $perPage)->values();
        $total = $clients->count();
        
        // Create paginator
        $clients = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $summary = [
            'total' => Contact::count() + Account::count(),
            'contacts' => Contact::count(),
            'accounts' => Account::count(),
            'active' => Contact::where('status', 'active')->count() + Account::where('status', 'active')->count(),
        ];
        
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        return view('client-registration.index', compact('clients', 'summary', 'users'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $leads = Lead::orderBy('first_name')->orderBy('company_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        $leadId = $request->get('lead_id') ?? session()->pull('client_registration_lead_id');
        if (!$leadId) {
            $leadId = Lead::where('created_by', auth()->id())
                ->orderByDesc('created_at')
                ->value('id');
        }
        $lead = $leadId ? Lead::find($leadId) : null;
        $leadData = $lead ? [
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
        ] : [];
        
        // Pre-populate from query parameters/session
        $preSelected = [
            'lead_id' => $leadId,
            'account_id' => $request->get('account_id'),
            'assigned_to' => $request->get('assigned_to') ?? ($leadData['assigned_to'] ?? null),
            'owner_id' => $request->get('owner_id') ?? ($leadData['assigned_to'] ?? null),
            'client_type' => $request->get('client_type', 'contact'), // Default to contact
        ];

        return view('client-registration.create', compact('accounts', 'leads', 'users', 'preSelected', 'leadData'));
    }

    public function store(Request $request)
    {
        $clientType = $request->input('client_type', 'contact');
        
        if ($clientType == 'account') {
            // Create Account
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
                'shipping_address' => 'nullable|string',
                'shipping_city' => 'nullable|string|max:255',
                'shipping_state' => 'nullable|string|max:255',
                'shipping_country' => 'nullable|string|max:255',
                'shipping_postal_code' => 'nullable|string|max:255',
                'employees' => 'nullable|integer|min:0',
                'annual_revenue' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive,suspended',
                'owner_id' => 'nullable|exists:users,id',
            ]);
            
            Account::create($validated);
            return redirect()->route('client-registration.index')->with('success', 'Account created successfully.');
        } else {
            // Create Contact
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
            return redirect()->route('client-registration.index')->with('success', 'Contact created successfully.');
        }
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
}
