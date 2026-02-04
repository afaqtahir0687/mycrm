<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use App\Services\FaisDigitalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::query()->with(['account', 'contact', 'deal', 'creator']);
        
        if ($request->filled('search')) {
            $query->where('quotation_number', 'like', '%' . $request->search . '%')
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
            'total' => Quotation::count(),
            'draft' => Quotation::where('status', 'draft')->count(),
            'sent' => Quotation::where('status', 'sent')->count(),
            'accepted' => Quotation::where('status', 'accepted')->count(),
            'total_value' => Quotation::sum('total_amount'),
        ];
        
        $quotations = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('quotations.index', compact('quotations', 'summary', 'accounts', 'contacts', 'deals', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $deals = Deal::orderBy('deal_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'deal_id' => $request->get('deal_id'),
        ];
        
        return view('quotations.create', compact('accounts', 'contacts', 'deals', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quotation_number' => 'required|string|max:255|unique:quotations',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'agreement_template' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        
        $validated['created_by'] = auth()->id();
        if (!isset($validated['total_amount'])) {
            $validated['total_amount'] = ($validated['subtotal'] ?? 0) + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        }
        
        // Handle agreement template upload
        if ($request->hasFile('agreement_template')) {
            $file = $request->file('agreement_template');
            $filename = 'agreement_' . time() . '_' . $validated['quotation_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('quotations/agreements', $filename, 'public');
            $validated['agreement_template_path'] = $path;
        }
        
        unset($validated['agreement_template']);
        
        Quotation::create($validated);
        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['account', 'contact', 'deal', 'creator']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $users = User::where('is_active', true)->get();
        return view('quotations.edit', compact('quotation', 'accounts', 'contacts', 'deals', 'users'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'quotation_number' => 'required|string|max:255|unique:quotations,quotation_number,' . $quotation->id,
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'agreement_template' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        
        if (!isset($validated['total_amount'])) {
            $validated['total_amount'] = ($validated['subtotal'] ?? 0) + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        }
        
        // Handle agreement template upload
        if ($request->hasFile('agreement_template')) {
            // Delete old file if exists
            if ($quotation->agreement_template_path && \Storage::disk('public')->exists($quotation->agreement_template_path)) {
                \Storage::disk('public')->delete($quotation->agreement_template_path);
            }
            
            $file = $request->file('agreement_template');
            $filename = 'agreement_' . time() . '_' . $validated['quotation_number'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('quotations/agreements', $filename, 'public');
            $validated['agreement_template_path'] = $path;
        }
        
        unset($validated['agreement_template']);
        
        $quotation->update($validated);
        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }
    
    public function print(Quotation $quotation)
    {
        $quotation->load(['account', 'contact', 'deal', 'creator']);
        return view('quotations.print', compact('quotation'));
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function syncFromFaisDigital(Request $request)
    {
        try {
            $client = new FaisDigitalClient();
            $items = $client->fetchQuotations();

            if (empty($items)) {
                return redirect()->route('quotations.index')->with('warning', 'No quotations received from Fais Digital.');
            }

            $created = 0;
            $updated = 0;
            $skipped = 0;
            $errors = [];

            foreach ($items as $item) {
                $quotationNumber = $item['quotation_number'] ?? $item['number'] ?? null;
                if (empty($quotationNumber)) {
                    $skipped++;
                    continue;
                }

                $accountId = $this->resolveAccountId($item);
                $contactId = $this->resolveContactId($item, $accountId);

                $payload = $this->mapQuotationPayload($item);
                $payload['account_id'] = $accountId;
                $payload['contact_id'] = $contactId;

                $existing = Quotation::where('quotation_number', $quotationNumber)->first();
                if ($existing) {
                    $existing->update($payload);
                    $updated++;
                } else {
                    $payload['created_by'] = auth()->id();
                    Quotation::create($payload);
                    $created++;
                }
            }

            $message = "Fais Digital sync completed. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}.";
            if (!empty($errors)) {
                $message .= ' Errors: ' . implode('; ', array_slice($errors, 0, 3));
            }

            return redirect()->route('quotations.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('quotations.index')->with('error', 'Fais Digital sync failed: ' . $e->getMessage());
        }
    }

    private function mapQuotationPayload(array $item): array
    {
        $status = strtolower($item['status'] ?? 'draft');
        $allowedStatuses = ['draft', 'sent', 'accepted', 'rejected', 'expired'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'draft';
        }

        $subtotal = $item['subtotal'] ?? null;
        $taxAmount = $item['tax_amount'] ?? null;
        $discountAmount = $item['discount_amount'] ?? null;
        $totalAmount = $item['total_amount'] ?? null;

        if ($totalAmount === null) {
            $totalAmount = ($subtotal ?? 0) + ($taxAmount ?? 0) - ($discountAmount ?? 0);
        }

        $quotationDate = $item['quotation_date'] ?? $item['date'] ?? now()->toDateString();
        $validUntil = $item['valid_until'] ?? $item['validity_date'] ?? null;

        return [
            'quotation_number' => $item['quotation_number'] ?? ($item['number'] ?? null),
            'deal_id' => $item['deal_id'] ?? null,
            'quotation_date' => $quotationDate,
            'valid_until' => $validUntil,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'currency' => strtoupper($item['currency'] ?? 'USD'),
            'status' => $status,
            'terms_conditions' => $item['terms_conditions'] ?? $item['terms'] ?? null,
            'notes' => $item['notes'] ?? null,
        ];
    }

    private function resolveAccountId(array $item): ?int
    {
        if (!empty($item['account_id'])) {
            return (int) $item['account_id'];
        }

        $accountName = $item['account_name'] ?? $item['account'] ?? null;
        if (empty($accountName)) {
            return null;
        }

        $account = Account::firstOrCreate(
            ['account_name' => $accountName],
            ['owner_id' => auth()->id()]
        );

        return $account->id;
    }

    private function resolveContactId(array $item, ?int $accountId): ?int
    {
        if (!empty($item['contact_id'])) {
            return (int) $item['contact_id'];
        }

        $contactEmail = $item['contact_email'] ?? $item['email'] ?? null;
        if (!empty($contactEmail)) {
            $contact = Contact::firstOrCreate(
                ['email' => $contactEmail],
                [
                    'first_name' => $item['contact_first_name'] ?? $item['first_name'] ?? 'Unknown',
                    'last_name' => $item['contact_last_name'] ?? $item['last_name'] ?? '',
                    'account_id' => $accountId,
                ]
            );

            return $contact->id;
        }

        $contactName = $item['contact_name'] ?? null;
        if (!empty($contactName)) {
            $parts = preg_split('/\s+/', trim($contactName), 2);
            $firstName = $parts[0] ?? 'Unknown';
            $lastName = $parts[1] ?? '';

            $contact = Contact::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'account_id' => $accountId,
            ]);

            return $contact->id;
        }

        return null;
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
