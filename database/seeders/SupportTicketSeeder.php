<?php

namespace Database\Seeders;

use App\Models\SupportTicket;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportTicketSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $users = User::where('is_active', true)->get();
        
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['new', 'open', 'in_progress', 'resolved', 'closed', 'cancelled'];
        $types = ['question', 'problem', 'feature_request', 'complaint'];
        
        $subjects = [
            'Login Issues',
            'Feature Request',
            'Performance Problem',
            'Billing Question',
            'Account Access',
            'Data Export Request',
            'Integration Support',
            'Training Request',
            'Bug Report',
            'Configuration Help',
        ];
        
        $descriptions = [
            'User experiencing difficulties logging into the system.',
            'Request for new feature to improve workflow.',
            'System performance degradation reported.',
            'Question regarding invoice and payment terms.',
            'Need assistance with account permissions.',
            'Request to export data in specific format.',
            'Help needed with third-party integration.',
            'Training session requested for new users.',
            'Bug identified in reporting module.',
            'Configuration assistance required.',
        ];
        
        for ($i = 0; $i < 55; $i++) {
            $priority = $priorities[array_rand($priorities)];
            $status = $statuses[array_rand($statuses)];
            $slaHours = $priority == 'urgent' ? 2 : ($priority == 'high' ? 4 : ($priority == 'medium' ? 8 : 24));
            
            SupportTicket::create([
                'ticket_number' => 'TKT-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'subject' => $subjects[array_rand($subjects)],
                'description' => $descriptions[array_rand($descriptions)],
                'account_id' => $accounts->random()->id,
                'contact_id' => $contacts->random()->id,
                'priority' => $priority,
                'status' => $status,
                'type' => $types[array_rand($types)],
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'sla_hours' => $slaHours,
                'sla_deadline' => now()->addHours($slaHours),
                'resolved_at' => $status == 'resolved' || $status == 'closed' ? now()->subDays(rand(1, 10)) : null,
                'closed_at' => $status == 'closed' ? now()->subDays(rand(1, 5)) : null,
            ]);
        }
    }
}
