<?php

namespace Database\Seeders;

use App\Models\Quotation;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $users = User::where('is_active', true)->get();
        
        $statuses = ['draft', 'sent', 'accepted', 'rejected', 'expired'];
        $currencies = ['USD', 'EUR', 'GBP'];
        
        for ($i = 0; $i < 40; $i++) {
            $subtotal = rand(1000, 100000);
            $taxAmount = $subtotal * 0.1;
            $discountAmount = rand(0, $subtotal * 0.2);
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            
            Quotation::create([
                'quotation_number' => 'QUO-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'deal_id' => $deals->random()->id,
                'quotation_date' => now()->subDays(rand(1, 60)),
                'valid_until' => now()->addDays(rand(30, 90)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => $currencies[array_rand($currencies)],
                'status' => $statuses[array_rand($statuses)],
                'terms_conditions' => 'Payment terms: Net 30 days. Valid for 60 days from issue date.',
                'notes' => 'This quotation includes all standard services and support.',
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
