<?php

namespace Database\Seeders;

use App\Models\AutomationWorkflow;
use App\Models\User;
use Illuminate\Database\Seeder;

class AutomationWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        
        $workflows = [
            [
                'name' => 'New Lead Welcome Email',
                'description' => 'Automatically send welcome email when a new lead is created',
                'trigger_type' => 'lead_created',
                'trigger_conditions' => ['status' => 'new'],
                'actions' => ['send_email' => 'welcome_template'],
                'is_active' => true,
            ],
            [
                'name' => 'Deal Stage Change Notification',
                'description' => 'Notify owner when deal stage changes',
                'trigger_type' => 'deal_stage_changed',
                'trigger_conditions' => [],
                'actions' => ['send_notification' => 'deal_owner'],
                'is_active' => true,
            ],
            [
                'name' => 'Overdue Invoice Reminder',
                'description' => 'Send reminder for overdue invoices',
                'trigger_type' => 'invoice_overdue',
                'trigger_conditions' => ['days_overdue' => 7],
                'actions' => ['send_email' => 'overdue_reminder'],
                'is_active' => true,
            ],
            [
                'name' => 'High Priority Ticket Escalation',
                'description' => 'Escalate high priority tickets to manager',
                'trigger_type' => 'ticket_created',
                'trigger_conditions' => ['priority' => 'urgent'],
                'actions' => ['assign_to' => 'manager', 'send_notification' => true],
                'is_active' => true,
            ],
            [
                'name' => 'Task Due Reminder',
                'description' => 'Send reminder for tasks due soon',
                'trigger_type' => 'task_due_soon',
                'trigger_conditions' => ['days_before_due' => 1],
                'actions' => ['send_notification' => 'task_owner'],
                'is_active' => true,
            ],
            [
                'name' => 'Quotation Expiry Alert',
                'description' => 'Alert when quotation is about to expire',
                'trigger_type' => 'quotation_expiring',
                'trigger_conditions' => ['days_before_expiry' => 7],
                'actions' => ['send_email' => 'expiry_alert'],
                'is_active' => false,
            ],
        ];
        
        foreach ($workflows as $workflow) {
            AutomationWorkflow::create([
                'name' => $workflow['name'],
                'description' => $workflow['description'],
                'trigger_type' => $workflow['trigger_type'],
                'trigger_conditions' => $workflow['trigger_conditions'],
                'actions' => $workflow['actions'],
                'is_active' => $workflow['is_active'],
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
