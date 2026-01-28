<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        
        $templates = [
            [
                'name' => 'Welcome Email',
                'type' => 'welcome',
                'subject' => 'Welcome to {{company_name}} - Let\'s Get Started!',
                'body' => '<p>Dear {{first_name}} {{last_name}},</p>
<p>Welcome to {{company_name}}! We\'re thrilled to have you on board.</p>
<p>As a valued client, we\'re committed to providing you with exceptional service and support. Your dedicated account manager, {{account_manager}}, will be reaching out to you shortly to schedule an onboarding call.</p>
<p>In the meantime, please don\'t hesitate to reach out if you have any questions.</p>
<p>Best regards,<br>{{sender_name}}<br>{{sender_title}}</p>',
                'variables' => ['first_name', 'last_name', 'company_name', 'account_manager', 'sender_name', 'sender_title'],
            ],
            [
                'name' => 'Follow-up Email',
                'type' => 'follow_up',
                'subject' => 'Following up on our conversation - {{company_name}}',
                'body' => '<p>Dear {{first_name}},</p>
<p>Thank you for taking the time to speak with me {{meeting_date}} regarding {{topic}}.</p>
<p>As discussed, I\'m following up with the information you requested. {{additional_info}}</p>
<p>I would appreciate the opportunity to continue our conversation and discuss how we can help {{company_name}} achieve your goals.</p>
<p>Please let me know your availability for a follow-up call this week.</p>
<p>Best regards,<br>{{sender_name}}<br>{{sender_title}}<br>{{sender_phone}}</p>',
                'variables' => ['first_name', 'company_name', 'meeting_date', 'topic', 'additional_info', 'sender_name', 'sender_title', 'sender_phone'],
            ],
            [
                'name' => 'Proposal Email',
                'type' => 'proposal',
                'subject' => 'Proposal for {{company_name}} - {{proposal_title}}',
                'body' => '<p>Dear {{first_name}} {{last_name}},</p>
<p>I hope this email finds you well. As promised, I\'ve prepared a detailed proposal for {{proposal_title}} tailored to {{company_name}}\'s specific needs.</p>
<p>The proposal outlines:</p>
<ul>
<li>Our recommended solution</li>
<li>Implementation timeline</li>
<li>Investment details</li>
<li>Next steps</li>
</ul>
<p>Please review the attached proposal document and let me know if you have any questions. I\'m available to discuss this proposal at your convenience.</p>
<p>I look forward to partnering with {{company_name}} on this initiative.</p>
<p>Best regards,<br>{{sender_name}}<br>{{sender_title}}</p>',
                'variables' => ['first_name', 'last_name', 'company_name', 'proposal_title', 'sender_name', 'sender_title'],
            ],
            [
                'name' => 'Meeting Invitation',
                'type' => 'meeting',
                'subject' => 'Meeting Invitation: {{meeting_topic}}',
                'body' => '<p>Dear {{first_name}},</p>
<p>I would like to schedule a meeting to discuss {{meeting_topic}}.</p>
<p><strong>Proposed Date & Time:</strong> {{meeting_date}} at {{meeting_time}}<br>
<strong>Duration:</strong> {{meeting_duration}} minutes<br>
<strong>Location:</strong> {{meeting_location}}</p>
<p>Please confirm your availability or suggest an alternative time that works better for you.</p>
<p>Looking forward to our conversation.</p>
<p>Best regards,<br>{{sender_name}}<br>{{sender_title}}</p>',
                'variables' => ['first_name', 'meeting_topic', 'meeting_date', 'meeting_time', 'meeting_duration', 'meeting_location', 'sender_name', 'sender_title'],
            ],
            [
                'name' => 'Thank You Email',
                'type' => 'thank_you',
                'subject' => 'Thank You for Your Business - {{company_name}}',
                'body' => '<p>Dear {{first_name}} {{last_name}},</p>
<p>On behalf of the entire team at {{sender_company}}, I want to express our sincere gratitude for choosing to work with us.</p>
<p>We truly appreciate your trust and confidence in our services. Your partnership means a great deal to us, and we\'re committed to exceeding your expectations.</p>
<p>If there\'s anything we can do to enhance your experience or support your success, please don\'t hesitate to reach out.</p>
<p>Thank you again for your business.</p>
<p>Warm regards,<br>{{sender_name}}<br>{{sender_title}}<br>{{sender_company}}</p>',
                'variables' => ['first_name', 'last_name', 'company_name', 'sender_company', 'sender_name', 'sender_title'],
            ],
            [
                'name' => 'Invoice Reminder',
                'type' => 'invoice',
                'subject' => 'Payment Reminder: Invoice #{{invoice_number}}',
                'body' => '<p>Dear {{first_name}} {{last_name}},</p>
<p>This is a friendly reminder that invoice #{{invoice_number}} for {{invoice_amount}} is due on {{due_date}}.</p>
<p><strong>Invoice Details:</strong><br>
Invoice Number: {{invoice_number}}<br>
Amount Due: {{invoice_amount}}<br>
Due Date: {{due_date}}</p>
<p>If you\'ve already made this payment, please disregard this email. If you have any questions about this invoice or need to discuss payment arrangements, please contact us at {{billing_email}} or {{billing_phone}}.</p>
<p>Thank you for your prompt attention to this matter.</p>
<p>Best regards,<br>Accounts Receivable<br>{{sender_company}}</p>',
                'variables' => ['first_name', 'last_name', 'invoice_number', 'invoice_amount', 'due_date', 'billing_email', 'billing_phone', 'sender_company'],
            ],
            [
                'name' => 'Newsletter',
                'type' => 'newsletter',
                'subject' => '{{newsletter_title}} - Monthly Newsletter',
                'body' => '<p>Dear {{first_name}},</p>
<p>We hope this newsletter finds you well. Here\'s what\'s new this month:</p>
<h3>{{newsletter_title}}</h3>
<p>{{newsletter_content}}</p>
<p>If you have any questions or would like to learn more about any of these updates, please don\'t hesitate to reach out.</p>
<p>Best regards,<br>The {{sender_company}} Team</p>',
                'variables' => ['first_name', 'newsletter_title', 'newsletter_content', 'sender_company'],
            ],
        ];
        
        foreach ($templates as $template) {
            EmailTemplate::create([
                'name' => $template['name'],
                'subject' => $template['subject'],
                'body' => $template['body'],
                'type' => $template['type'],
                'is_active' => true,
                'user_id' => $users->random()->id,
                'variables' => $template['variables'],
                'created_at' => now()->subDays(rand(0, 90)),
            ]);
        }
    }
}

