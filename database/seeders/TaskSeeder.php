<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Lead;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $leads = Lead::all();
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $statuses = ['not_started', 'in_progress', 'completed', 'cancelled'];
        
        $subjects = [
            'Follow up with client',
            'Prepare proposal document',
            'Schedule meeting',
            'Update account information',
            'Review contract terms',
            'Send quotation',
            'Process invoice payment',
            'Update CRM records',
            'Contact prospect',
            'Prepare presentation',
            'Send follow-up email',
            'Schedule demo call',
            'Review sales pipeline',
            'Update lead status',
            'Contact decision maker',
        ];
        
        $descriptions = [
            'Follow up call to discuss proposal details.',
            'Prepare comprehensive proposal for client review.',
            'Schedule meeting with key stakeholders.',
            'Update account details in system.',
            'Review and finalize contract terms.',
            'Send quotation to client for approval.',
            'Process pending invoice payment.',
            'Update CRM with latest information.',
            'Initial contact with new prospect.',
            'Prepare presentation for client meeting.',
            'Send follow-up email with requested information.',
            'Schedule product demonstration call.',
            'Review and update sales pipeline status.',
            'Update lead status based on latest interaction.',
            'Contact decision maker to discuss proposal.',
        ];
        
        $relatedModels = [
            Lead::class => $leads,
            Account::class => $accounts,
            Contact::class => $contacts,
            Deal::class => $deals,
        ];
        
        for ($i = 0; $i < 80; $i++) {
            $status = $statuses[array_rand($statuses)];
            $relatedModelType = array_rand($relatedModels);
            $relatedModel = $relatedModels[$relatedModelType];
            
            if ($relatedModel->count() > 0) {
                $related = $relatedModel->random();
            } else {
                // If no related model exists, skip this iteration
                continue;
            }
            
            Task::create([
                'subject' => $subjects[array_rand($subjects)] . ' - ' . ($i + 1),
                'description' => $descriptions[array_rand($descriptions)],
                'priority' => $priorities[array_rand($priorities)],
                'status' => $status,
                'due_date' => now()->addDays(rand(1, 30)),
                'due_time' => now()->setTime(rand(9, 17), [0, 15, 30, 45][array_rand([0, 15, 30, 45])]),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'related_type' => $relatedModelType,
                'related_id' => $related->id,
                'is_reminder' => rand(0, 1) == 1,
                'reminder_at' => rand(0, 1) == 1 ? now()->addDays(rand(1, 7)) : null,
                'completed_at' => $status == 'completed' ? now()->subDays(rand(1, 10)) : null,
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }
    }
}
