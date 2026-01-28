<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Quotation;
use App\Models\Deal;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $query = Agreement::query()->with(['quotation', 'deal', 'account', 'contact', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('agreement_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('account', function($q) use ($request) {
                      $q->where('account_name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('agreement_type')) {
            $query->where('agreement_type', $request->agreement_type);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Agreement::count(),
            'draft' => Agreement::where('status', 'draft')->count(),
            'signed' => Agreement::where('status', 'signed')->count(),
            'active' => Agreement::where('status', 'active')->count(),
        ];
        
        $agreements = $query->paginate(50);
        $quotations = Quotation::orderBy('quotation_number')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'quotations' => $quotations,
            'deals' => $deals,
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('agreements.index', compact('agreements', 'summary', 'quotations', 'deals', 'accounts', 'contacts', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $quotations = Quotation::orderBy('quotation_number')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $preSelected = [
            'quotation_id' => $request->get('quotation_id'),
            'deal_id' => $request->get('deal_id'),
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
        ];
        
        return view('agreements.create', compact('quotations', 'deals', 'accounts', 'contacts', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agreement_number' => 'required|string|max:255|unique:agreements',
            'agreement_type' => 'required|in:STC,SLA,Agreement Draft',
            'quotation_id' => 'nullable|exists:quotations,id',
            'deal_id' => 'nullable|exists:deals,id',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'agreement_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:draft,sent,signed,active,expired,terminated',
            'terms_conditions' => 'nullable|string',
            'sla_terms' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'total_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'agreement_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        if ($request->hasFile('agreement_file')) {
            $file = $request->file('agreement_file');
            $filename = 'agreement_' . time() . '_' . $validated['agreement_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('agreements', $filename, 'public');
            $validated['agreement_file_path'] = $path;
        }
        
        unset($validated['agreement_file']);
        
        Agreement::create($validated);
        return redirect()->route('agreements.index')->with('success', 'Agreement created successfully.');
    }

    public function show(Agreement $agreement)
    {
        $agreement->load(['quotation', 'deal', 'account', 'contact', 'creator']);
        return view('agreements.show', compact('agreement'));
    }

    public function edit(Agreement $agreement)
    {
        $quotations = Quotation::orderBy('quotation_number')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->get();
        return view('agreements.edit', compact('agreement', 'quotations', 'deals', 'accounts', 'contacts', 'users'));
    }

    public function update(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'agreement_number' => 'required|string|max:255|unique:agreements,agreement_number,' . $agreement->id,
            'agreement_type' => 'required|in:STC,SLA,Agreement Draft',
            'quotation_id' => 'nullable|exists:quotations,id',
            'deal_id' => 'nullable|exists:deals,id',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'agreement_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:draft,sent,signed,active,expired,terminated',
            'terms_conditions' => 'nullable|string',
            'sla_terms' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'total_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'agreement_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);
        
        if ($request->hasFile('agreement_file')) {
            if ($agreement->agreement_file_path && Storage::disk('public')->exists($agreement->agreement_file_path)) {
                Storage::disk('public')->delete($agreement->agreement_file_path);
            }
            
            $file = $request->file('agreement_file');
            $filename = 'agreement_' . time() . '_' . $validated['agreement_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('agreements', $filename, 'public');
            $validated['agreement_file_path'] = $path;
        }
        
        unset($validated['agreement_file']);
        
        $agreement->update($validated);
        return redirect()->route('agreements.index')->with('success', 'Agreement updated successfully.');
    }

    public function destroy(Agreement $agreement)
    {
        if ($agreement->agreement_file_path && Storage::disk('public')->exists($agreement->agreement_file_path)) {
            Storage::disk('public')->delete($agreement->agreement_file_path);
        }
        if ($agreement->signed_file_path && Storage::disk('public')->exists($agreement->signed_file_path)) {
            Storage::disk('public')->delete($agreement->signed_file_path);
        }
        $agreement->delete();
        return redirect()->route('agreements.index')->with('success', 'Agreement deleted successfully.');
    }
}
