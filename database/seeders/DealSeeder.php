<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $leads = Lead::all();
        $users = User::where('is_active', true)->get();
        
        $stages = ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $statuses = ['open', 'won', 'lost'];
        $currencies = ['USD', 'EUR', 'GBP'];
        
        $dealNames = [
            'Enterprise Software License',
            'Cloud Infrastructure Package',
            'Annual Support Contract',
            'Custom Development Project',
            'Marketing Campaign Services',
            'Consulting Engagement',
            'Training Program Package',
            'Hardware Procurement',
            'Integration Services',
            'Maintenance Agreement',
        ];
        
        for ($i = 0; $i < 60; $i++) {
            $stage = $stages[array_rand($stages)];
            $status = $statuses[array_rand($statuses)];
            if ($stage == 'closed_won') $status = 'won';
            if ($stage == 'closed_lost') $status = 'lost';
            
            Deal::create([
                'deal_name' => $dealNames[array_rand($dealNames)] . ' - ' . ($i + 1),
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'lead_id' => $leads->random()->id,
                'amount' => rand(5000, 500000),
                'currency' => $currencies[array_rand($currencies)],
                'expected_close_date' => now()->addDays(rand(1, 90)),
                'stage' => $stage,
                'probability' => rand(10, 100),
                'description' => 'This deal involves ' . strtolower($dealNames[array_rand($dealNames)]) . ' for the client.',
                'owner_id' => $users->random()->id,
                'status' => $status,
            ]);
        }
    }
}
