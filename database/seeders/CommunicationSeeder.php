<?php

namespace Database\Seeders;

use App\Models\Communication;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunicationSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $leads = Lead::all();
        $users = User::where('is_active', true)->get();
        
        $types = ['email', 'phone', 'sms', 'whatsapp', 'meeting', 'note'];
        $directions = ['inbound', 'outbound'];
        $statuses = ['sent', 'delivered', 'read', 'failed', 'pending'];
        
        $subjects = [
            'Project Update',
            'Meeting Request',
            'Proposal Discussion',
            'Follow-up Call',
            'Contract Review',
            'Payment Reminder',
            'Product Demo',
            'Support Request',
            'Feedback Request',
            'Thank You Note',
        ];
        
        $contents = [
            'Following up on our previous conversation.',
            'Would like to schedule a meeting to discuss further.',
            'Please review the attached proposal.',
            'Call to discuss next steps.',
            'Contract terms need review and approval.',
            'Reminder about upcoming payment.',
            'Product demonstration scheduled.',
            'Support request received and being processed.',
            'Requesting feedback on recent interaction.',
            'Thank you for your business.',
        ];
        
        for ($i = 0; $i < 70; $i++) {
            $type = $types[array_rand($types)];
            $direction = $directions[array_rand($directions)];
            
            Communication::create([
                'type' => $type,
                'subject' => $subjects[array_rand($subjects)],
                'content' => $contents[array_rand($contents)],
                'direction' => $direction,
                'from_email' => $type == 'email' ? 'sender@example.com' : null,
                'to_email' => $type == 'email' ? 'recipient@example.com' : null,
                'from_phone' => in_array($type, ['phone', 'sms', 'whatsapp']) ? '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) : null,
                'to_phone' => in_array($type, ['phone', 'sms', 'whatsapp']) ? '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) : null,
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'lead_id' => $leads->random()->id,
                'created_by' => $users->random()->id,
                'duration_minutes' => in_array($type, ['phone', 'meeting']) ? rand(5, 60) : null,
                'status' => $statuses[array_rand($statuses)],
                'sent_at' => in_array($type, ['phone', 'meeting']) ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}
