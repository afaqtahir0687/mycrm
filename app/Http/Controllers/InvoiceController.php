<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->with(['account', 'contact', 'deal', 'quotation', 'creator']);
        
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('account', function($q) use ($request) {
                      $q->where('account_name', 'like', '%' . $request->search . '%');
                  });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
            'total_amount' => Invoice::sum('total_amount'),
            'outstanding' => Invoice::sum('balance'),
        ];
        
        $invoices = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $quotations = Quotation::orderBy('quotation_number')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('invoices.index', compact('invoices', 'summary', 'accounts', 'contacts', 'deals', 'quotations', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $quotations = Quotation::orderBy('quotation_number')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'deal_id' => $request->get('deal_id'),
            'quotation_id' => $request->get('quotation_id'),
        ];
        
        return view('invoices.create', compact('accounts', 'contacts', 'deals', 'quotations', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:draft,sent,paid,partial,overdue,cancelled',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $validated['created_by'] = auth()->id();
        if (!isset($validated['total_amount'])) {
            $validated['total_amount'] = ($validated['subtotal'] ?? 0) + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        }
        if (!isset($validated['balance'])) {
            $validated['balance'] = ($validated['total_amount'] ?? 0) - ($validated['amount_paid'] ?? 0);
        }
        
        Invoice::create($validated);
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['account', 'contact', 'deal', 'quotation', 'creator']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $quotations = Quotation::all();
        $users = User::where('is_active', true)->get();
        return view('invoices.edit', compact('invoice', 'accounts', 'contacts', 'deals', 'quotations', 'users'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:draft,sent,paid,partial,overdue,cancelled',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        if (!isset($validated['total_amount'])) {
            $validated['total_amount'] = ($validated['subtotal'] ?? 0) + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        }
        if (!isset($validated['balance'])) {
            $validated['balance'] = ($validated['total_amount'] ?? 0) - ($validated['amount_paid'] ?? 0);
        }
        
        $invoice->update($validated);
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
    
    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\InvoicesExport, 'invoices.xlsx');
    }
    
    public function exportPdf()
    {
        $invoices = Invoice::all();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.invoices', compact('invoices'));
        return $pdf->download('invoices.pdf');
    }
    
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\InvoicesImport, $request->file('file'));
        
        return redirect()->route('invoices.index')->with('success', 'Invoices imported successfully.');
    }
}
