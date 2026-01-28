<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query()->with(['invoice', 'account', 'contact', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('payment_number', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->filled('type')) {
            // For filtering received vs made payments
            if ($request->type == 'received') {
                $query->whereNotNull('account_id');
            } elseif ($request->type == 'made') {
                // This would be for expenses - handled separately
            }
        }
        
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Payment::count(),
            'received' => Payment::whereNotNull('account_id')->count(),
            'total_amount' => Payment::sum('amount'),
        ];
        
        $payments = $query->paginate(50);
        $invoices = Invoice::orderBy('invoice_number')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'invoices' => $invoices,
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('payments.index', compact('payments', 'summary', 'invoices', 'accounts', 'contacts', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $invoices = Invoice::orderBy('invoice_number')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $preSelected = [
            'invoice_id' => $request->get('invoice_id'),
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
        ];
        
        return view('payments.create', compact('invoices', 'accounts', 'contacts', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_number' => 'required|string|max:255|unique:payments',
            'invoice_id' => 'nullable|exists:invoices,id',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:received,pending,failed,refunded',
            'notes' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        Payment::create($validated);
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'account', 'contact', 'creator']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $invoices = Invoice::orderBy('invoice_number')->get();
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->get();
        return view('payments.edit', compact('payment', 'invoices', 'accounts', 'contacts', 'users'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_number' => 'required|string|max:255|unique:payments,payment_number,' . $payment->id,
            'invoice_id' => 'nullable|exists:invoices,id',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:received,pending,failed,refunded',
            'notes' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
        ]);
        
        $payment->update($validated);
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PaymentsExport, 'payments.xlsx');
    }

    public function exportPdf()
    {
        $payments = \App\Models\Payment::with(['invoice', 'account', 'contact'])->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.payments', compact('payments'));
        return $pdf->download('payments.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\PaymentsImport, $request->file('file'));
        
        return redirect()->route('payments.index')->with('success', 'Payments imported successfully.');
    }
}
