<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Lead;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33F5', '#F5FF33', '#33FFF5', '#FF8C33', '#8C33FF', '#33FF8C', '#FF3333', '#33FF33', '#3333FF'];
        
        $tagNames = [
            // Priority tags
            'High Priority', 'Urgent', 'Low Priority', 'VIP',
            // Status tags
            'Hot Lead', 'Cold Lead', 'Warm Lead', 'Qualified', 'Unqualified',
            // Industry tags
            'Technology', 'Healthcare', 'Finance', 'Retail', 'Manufacturing',
            // Type tags
            'Enterprise', 'SMB', 'Startup', 'Enterprise Client', 'Partner',
            // Geographic tags
            'Pakistan', 'GCC', 'Middle East', 'US Market', 'European Market',
            // Product tags
            'Product A', 'Product B', 'Service Package', 'Enterprise Solution',
            // Action tags
            'Needs Follow-up', 'Meeting Scheduled', 'Proposal Sent', 'Contract Pending',
            // Other
            'Referral', 'Partner Channel', 'Direct Sale', 'Renewal', 'Upsell Opportunity'
        ];
        
        $tags = [];
        foreach ($tagNames as $index => $name) {
            $tag = Tag::create([
                'name' => $name,
                'color' => $colors[$index % count($colors)],
                'type' => ['priority', 'status', 'industry', 'type', 'geographic', 'product', 'action', 'other'][rand(0, 7)],
            ]);
            $tags[] = $tag;
        }
        
        // Attach tags to leads
        $leads = Lead::all();
        foreach ($leads->random(rand(50, 100)) as $lead) {
            $leadTags = collect($tags)->random(rand(1, 3));
            $lead->tags()->attach($leadTags->pluck('id')->toArray());
        }
        
        // Attach tags to accounts
        $accounts = Account::all();
        foreach ($accounts->random(rand(10, 20)) as $account) {
            $accountTags = collect($tags)->random(rand(1, 2));
            $account->tags()->attach($accountTags->pluck('id')->toArray());
        }
        
        // Attach tags to contacts
        $contacts = Contact::all();
        foreach ($contacts->random(rand(30, 60)) as $contact) {
            $contactTags = collect($tags)->random(rand(1, 2));
            $contact->tags()->attach($contactTags->pluck('id')->toArray());
        }
        
        // Attach tags to deals
        $deals = Deal::all();
        foreach ($deals->random(rand(20, 40)) as $deal) {
            $dealTags = collect($tags)->random(rand(1, 3));
            $deal->tags()->attach($dealTags->pluck('id')->toArray());
        }
    }
}

