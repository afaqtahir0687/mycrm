<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $users = User::where('is_active', true)->get();
        
        $stages = ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $statuses = ['open', 'won', 'lost'];
        $currencies = ['USD', 'EUR', 'GBP'];
        $types = ['New Business', 'Existing Business', 'Renewal', 'Upsell'];
        
        $opportunityNames = [
            'Digital Transformation Initiative',
            'Customer Experience Enhancement',
            'Operational Efficiency Project',
            'Technology Modernization',
            'Strategic Partnership Opportunity',
            'Market Expansion Initiative',
            'Product Launch Support',
            'Compliance and Security Upgrade',
            'Data Analytics Implementation',
            'Workflow Automation Project',
        ];
        
        for ($i = 0; $i < 50; $i++) {
            $stage = $stages[array_rand($stages)];
            $status = $statuses[array_rand($statuses)];
            if ($stage == 'closed_won') $status = 'won';
            if ($stage == 'closed_lost') $status = 'lost';
            
            Opportunity::create([
                'opportunity_name' => $opportunityNames[array_rand($opportunityNames)] . ' - ' . ($i + 1),
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'amount' => rand(10000, 1000000),
                'currency' => $currencies[array_rand($currencies)],
                'close_date' => now()->addDays(rand(1, 120)),
                'stage' => $stage,
                'probability' => rand(20, 95),
                'type' => $types[array_rand($types)],
                'description' => 'This opportunity focuses on ' . strtolower($opportunityNames[array_rand($opportunityNames)]) . '.',
                'owner_id' => $users->random()->id,
                'status' => $status,
            ]);
        }
    }
}
