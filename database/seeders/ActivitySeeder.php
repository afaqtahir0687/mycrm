<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $leads = Lead::all();
        
        $activityTypes = ['call', 'email', 'meeting', 'note', 'task', 'document', 'presentation', 'demo', 'training', 'visit'];
        
        $titles = [
            'Initial contact call', 'Follow-up email sent', 'Discovery meeting held', 'Proposal presented',
            'Product demonstration', 'Contract negotiation', 'Onboarding session', 'Quarterly review',
            'Technical consultation', 'Stakeholder meeting', 'Training completed', 'Site visit conducted',
            'Document shared', 'Requirements gathering', 'Solution presentation', 'Pricing discussion',
            'Implementation planning', 'Go-live support', 'User feedback session', 'Status update call'
        ];
        
        $descriptions = [
            'Initial contact established. Discussed basic requirements and next steps.',
            'Sent follow-up email with requested information and pricing details.',
            'Conducted discovery meeting to understand business needs and objectives.',
            'Presented comprehensive proposal covering solution, timeline, and investment.',
            'Demonstrated product features and capabilities to key stakeholders.',
            'Discussed contract terms, pricing, and implementation details.',
            'Completed onboarding session for new customer. Training provided.',
            'Quarterly business review meeting. Reviewed performance and future plans.',
            'Technical consultation to address implementation questions and concerns.',
            'Stakeholder alignment meeting to ensure project objectives are clear.',
            'Training session completed successfully. Users are now familiar with the system.',
            'Site visit to assess infrastructure and requirements.',
            'Shared important documents including proposals, contracts, and specifications.',
            'Gathered detailed requirements from all stakeholders.',
            'Presented solution architecture and implementation approach.',
            'Discussed pricing options and negotiated contract terms.',
            'Planned implementation timeline, resources, and milestones.',
            'Provided go-live support and addressed initial issues.',
            'Collected user feedback and identified areas for improvement.',
            'Status update call to keep stakeholders informed of progress.'
        ];
        
        $locations = [
            'Phone Call', 'Email', 'Video Conference', 'Client Office', 'Our Office',
            'Conference Room', 'Coffee Shop', 'Restaurant', 'Virtual Meeting', 'On-site Visit'
        ];
        
        for ($i = 0; $i < 150; $i++) {
            $activityType = $activityTypes[array_rand($activityTypes)];
            $relatedModels = [
                Account::class => $accounts->random(),
                Contact::class => $contacts->random(),
                Deal::class => $deals->random(),
                Lead::class => $leads->random(),
            ];
            $relatedModelType = array_rand($relatedModels);
            $related = $relatedModels[$relatedModelType];
            
            $duration = $activityType === 'call' ? rand(15, 60) : ($activityType === 'meeting' ? rand(30, 120) : rand(5, 30));
            $activityDate = now()->subDays(rand(0, 120));
            
            Activity::create([
                'activity_type' => $activityType,
                'title' => $titles[array_rand($titles)],
                'description' => $descriptions[array_rand($descriptions)],
                'subject_type' => $relatedModelType,
                'subject_id' => $related->id,
                'user_id' => $users->random()->id,
                'activity_date' => $activityDate,
                'duration_minutes' => $duration,
                'location' => $locations[array_rand($locations)],
                'metadata' => [
                    'outcome' => ['positive', 'neutral', 'needs_followup'][array_rand(['positive', 'neutral', 'needs_followup'])],
                    'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                    'participants' => rand(1, 5),
                ],
                'created_at' => $activityDate,
            ]);
        }
    }
}

