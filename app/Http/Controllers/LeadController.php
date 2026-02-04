<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query()->with(['assignedUser', 'creator']);
        
        // Filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }
        
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Summary
        $summary = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];
        
        // Pagination - showing 50 per page
        $leads = $query->paginate(50);
        
        $users = User::where('is_active', true)->get();
        
        // Get distinct values for filters
        $filterOptions = [
            'lead_sources' => Lead::select('lead_source')->distinct()->whereNotNull('lead_source')->orderBy('lead_source')->pluck('lead_source'),
            'industries' => Lead::select('industry')->distinct()->whereNotNull('industry')->orderBy('industry')->pluck('industry'),
            'assigned_users' => User::where('is_active', true)->orderBy('name')->get(),
        ];
        
        return view('leads.index', compact('leads', 'summary', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'assigned_to' => $request->get('assigned_to'),
        ];
        
        return view('leads.create', compact('users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'lead_source' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'lead_score' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:new,contacted,qualified,converted,lost',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        $lead = Lead::create($validated);
        session(['client_registration_lead_id' => $lead->id]);

        return redirect()->route('client-registration.create', [
            'lead_id' => $lead->id,
            'assigned_to' => $lead->assigned_to,
        ])->with('success', 'Lead created. You can now register this lead as a client.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedUser', 'creator']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $users = User::where('is_active', true)->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'lead_source' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'lead_score' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:new,contacted,qualified,converted,lost',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        
        $lead->update($validated);
        
        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
    
    public function exportExcel()
    {
        // Placeholder for Excel export - will need maatwebsite/excel package
        return response()->json(['message' => 'Excel export functionality requires maatwebsite/excel package']);
    }
    
    public function exportPdf()
    {
        return response()->json(['message' => 'PDF export functionality requires barryvdh/laravel-dompdf package']);
    }
    
    public function importExcel(Request $request)
    {
        // Placeholder for Excel import - will need maatwebsite/excel package
        return redirect()->route('leads.index')->with('success', 'Excel import functionality requires maatwebsite/excel package');
    }
    
    public function assign(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assignment_action' => 'required|in:call,sms,email,whatsapp,online_meeting,personal_visit',
            'assignment_deadline' => 'required|date|after:now',
        ]);
        
        $lead->update([
            'assigned_to' => $validated['assigned_to'],
            'assignment_action' => $validated['assignment_action'],
            'assignment_deadline' => $validated['assignment_deadline'],
        ]);
        
        return redirect()->route('leads.index')->with('success', 'Lead assigned successfully.');
    }
}
