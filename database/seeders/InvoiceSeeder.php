<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $quotations = Quotation::all();
        $users = User::where('is_active', true)->get();
        
        $statuses = ['draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled'];
        $currencies = ['USD', 'EUR', 'GBP'];
        
        for ($i = 0; $i < 45; $i++) {
            $subtotal = rand(2000, 150000);
            $taxAmount = $subtotal * 0.1;
            $discountAmount = rand(0, $subtotal * 0.15);
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            $amountPaid = rand(0, $totalAmount);
            $balance = $totalAmount - $amountPaid;
            
            $status = $statuses[array_rand($statuses)];
            if ($balance == 0) $status = 'paid';
            elseif ($balance < $totalAmount && $balance > 0) $status = 'partial';
            elseif (now()->subDays(rand(1, 30))->gt(now()->subDays(30))) $status = 'overdue';
            
            Invoice::create([
                'invoice_number' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'deal_id' => $deals->random()->id,
                'quotation_id' => $quotations->random()->id,
                'invoice_date' => now()->subDays(rand(1, 90)),
                'due_date' => now()->addDays(rand(1, 60)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'balance' => $balance,
                'currency' => $currencies[array_rand($currencies)],
                'status' => $status,
                'terms_conditions' => 'Payment due within 30 days. Late payment fees may apply.',
                'notes' => 'Invoice for services rendered.',
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
