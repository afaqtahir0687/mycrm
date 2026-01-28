<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $leads = Lead::all();
        $tasks = Task::all();
        $tickets = SupportTicket::all();
        
        $types = ['info', 'success', 'warning', 'error', 'reminder', 'assignment', 'update', 'alert'];
        
        $notifications = [
            // Lead notifications
            ['type' => 'success', 'title' => 'New Lead Created', 'message' => 'A new lead "{{lead_name}}" has been assigned to you.'],
            ['type' => 'info', 'title' => 'Lead Status Updated', 'message' => 'Lead "{{lead_name}}" status has been changed to {{status}}.'],
            ['type' => 'warning', 'title' => 'Lead Follow-up Required', 'message' => 'Lead "{{lead_name}}" requires follow-up. Last contact was {{days_ago}} days ago.'],
            
            // Deal notifications
            ['type' => 'info', 'title' => 'New Deal Created', 'message' => 'Deal "{{deal_name}}" has been assigned to you.'],
            ['type' => 'success', 'title' => 'Deal Won', 'message' => 'Congratulations! Deal "{{deal_name}}" has been closed as won.'],
            ['type' => 'warning', 'title' => 'Deal Closing Soon', 'message' => 'Deal "{{deal_name}}" is expected to close in {{days}} days.'],
            
            // Task notifications
            ['type' => 'reminder', 'title' => 'Task Due Soon', 'message' => 'Task "{{task_name}}" is due in {{hours}} hours.'],
            ['type' => 'warning', 'title' => 'Task Overdue', 'message' => 'Task "{{task_name}}" is overdue. Please complete it as soon as possible.'],
            ['type' => 'success', 'title' => 'Task Completed', 'message' => 'Task "{{task_name}}" has been marked as completed.'],
            
            // Support ticket notifications
            ['type' => 'alert', 'title' => 'New Support Ticket', 'message' => 'A new support ticket "{{ticket_number}}" has been assigned to you.'],
            ['type' => 'warning', 'title' => 'Ticket SLA Approaching', 'message' => 'Support ticket "{{ticket_number}}" SLA deadline is approaching.'],
            ['type' => 'info', 'title' => 'Ticket Updated', 'message' => 'Support ticket "{{ticket_number}}" has been updated.'],
            
            // Account notifications
            ['type' => 'info', 'title' => 'Account Assigned', 'message' => 'Account "{{account_name}}" has been assigned to you.'],
            ['type' => 'reminder', 'title' => 'Account Review Due', 'message' => 'Quarterly review for account "{{account_name}}" is due.'],
            
            // Contact notifications
            ['type' => 'info', 'title' => 'Contact Updated', 'message' => 'Contact "{{contact_name}}" information has been updated.'],
            
            // General notifications
            ['type' => 'success', 'title' => 'Invoice Paid', 'message' => 'Invoice {{invoice_number}} has been marked as paid.'],
            ['type' => 'warning', 'title' => 'Payment Overdue', 'message' => 'Invoice {{invoice_number}} payment is overdue.'],
            ['type' => 'info', 'title' => 'Meeting Reminder', 'message' => 'You have a meeting "{{meeting_title}}" in {{minutes}} minutes.'],
        ];
        
        for ($i = 0; $i < 100; $i++) {
            $user = $users->random();
            $notificationTemplate = $notifications[array_rand($notifications)];
            $type = $notificationTemplate['type'];
            
            // Select related model
            $relatedModels = [
                ['type' => Lead::class, 'model' => $leads->random(), 'name' => 'lead_name'],
                ['type' => Deal::class, 'model' => $deals->random(), 'name' => 'deal_name'],
                ['type' => Task::class, 'model' => $tasks->random(), 'name' => 'task_name'],
                ['type' => SupportTicket::class, 'model' => $tickets->random(), 'name' => 'ticket_number'],
                ['type' => Account::class, 'model' => $accounts->random(), 'name' => 'account_name'],
                ['type' => Contact::class, 'model' => $contacts->random(), 'name' => 'contact_name'],
            ];
            $related = $relatedModels[array_rand($relatedModels)];
            
            $message = $notificationTemplate['message'];
            if ($related['type'] === Lead::class) {
                $message = str_replace('{{lead_name}}', $related['model']->first_name . ' ' . $related['model']->last_name, $message);
                $message = str_replace('{{status}}', $related['model']->status, $message);
                $message = str_replace('{{days_ago}}', rand(1, 10), $message);
            } elseif ($related['type'] === Deal::class) {
                $message = str_replace('{{deal_name}}', $related['model']->deal_name, $message);
                $message = str_replace('{{days}}', rand(1, 7), $message);
            } elseif ($related['type'] === Task::class) {
                $message = str_replace('{{task_name}}', $related['model']->subject, $message);
                $message = str_replace('{{hours}}', rand(1, 24), $message);
            } elseif ($related['type'] === SupportTicket::class) {
                $message = str_replace('{{ticket_number}}', $related['model']->ticket_number, $message);
            } elseif ($related['type'] === Account::class) {
                $message = str_replace('{{account_name}}', $related['model']->account_name, $message);
            } elseif ($related['type'] === Contact::class) {
                $message = str_replace('{{contact_name}}', $related['model']->first_name . ' ' . $related['model']->last_name, $message);
            }
            $message = str_replace(['{{invoice_number}}', '{{meeting_title}}', '{{minutes}}'], ['INV-' . rand(100000, 999999), 'Quarterly Review', rand(15, 60)], $message);
            
            $isRead = rand(0, 10) < 6;
            
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $notificationTemplate['title'],
                'message' => $message,
                'notifiable_type' => $related['type'],
                'notifiable_id' => $related['model']->id,
                'action_url' => '/',
                'is_read' => $isRead,
                'read_at' => $isRead ? now()->subDays(rand(0, 30)) : null,
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }
    }
}

