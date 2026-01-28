<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query()->with(['payment', 'vendor', 'approver', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('expense_name', 'like', '%' . $request->search . '%')
                  ->orWhere('expense_number', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $sortBy = $request->get('sort_by', 'expense_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Expense::count(),
            'pending' => Expense::where('status', 'pending')->count(),
            'paid' => Expense::where('status', 'paid')->count(),
            'total_amount' => Expense::sum('amount'),
        ];
        
        $expenses = $query->paginate(50);
        $payments = Payment::orderBy('payment_number')->get();
        $vendors = Account::orderBy('account_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'payments' => $payments,
            'vendors' => $vendors,
            'users' => $users,
        ];
        
        return view('expenses.index', compact('expenses', 'summary', 'payments', 'vendors', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $payments = Payment::orderBy('payment_number')->get();
        $vendors = Account::orderBy('account_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $preSelected = [
            'payment_id' => $request->get('payment_id'),
            'vendor_id' => $request->get('vendor_id'),
        ];
        
        return view('expenses.create', compact('payments', 'vendors', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_number' => 'required|string|max:255|unique:expenses',
            'expense_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'payment_id' => 'nullable|exists:payments,id',
            'vendor_id' => 'nullable|exists:accounts,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,approved,rejected',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'receipt_' . time() . '_' . $validated['expense_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('expenses/receipts', $filename, 'public');
            $validated['receipt_path'] = $path;
        }
        
        unset($validated['receipt']);
        
        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['payment', 'vendor', 'approver', 'creator']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $payments = Payment::orderBy('payment_number')->get();
        $vendors = Account::orderBy('account_name')->get();
        $users = User::where('is_active', true)->get();
        return view('expenses.edit', compact('expense', 'payments', 'vendors', 'users'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_number' => 'required|string|max:255|unique:expenses,expense_number,' . $expense->id,
            'expense_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'payment_id' => 'nullable|exists:payments,id',
            'vendor_id' => 'nullable|exists:accounts,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,approved,rejected',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);
        
        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            
            $file = $request->file('receipt');
            $filename = 'receipt_' . time() . '_' . $validated['expense_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('expenses/receipts', $filename, 'public');
            $validated['receipt_path'] = $path;
        }
        
        unset($validated['receipt']);
        
        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ExpensesExport, 'expenses.xlsx');
    }

    public function exportPdf()
    {
        $expenses = \App\Models\Expense::with(['vendor', 'payment'])->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.expenses', compact('expenses'));
        return $pdf->download('expenses.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ExpensesImport, $request->file('file'));
        
        return redirect()->route('expenses.index')->with('success', 'Expenses imported successfully.');
    }
}
