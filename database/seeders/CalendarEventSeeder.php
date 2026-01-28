<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $accounts = Account::all();
        $contacts = Contact::all();
        $deals = Deal::all();
        $leads = Lead::all();
        
        $eventTypes = ['meeting', 'call', 'email', 'demo', 'presentation', 'training', 'conference', 'lunch', 'webinar', 'follow_up'];
        $locations = [
            'Conference Room A', 'Conference Room B', 'Client Office', 'Virtual Meeting', 'Restaurant',
            'Coffee Shop', 'Hotel Lobby', 'Exhibition Center', 'Office - Main Building', 'Online - Zoom',
            'Online - Teams', 'Online - Google Meet', 'Phone Call', 'Video Call'
        ];
        
        $eventTitles = [
            'Client Meeting - Q1 Review', 'Product Demo Session', 'Follow-up Call', 'Proposal Presentation',
            'Contract Discussion', 'Training Session', 'Quarterly Business Review', 'Discovery Call',
            'Technical Consultation', 'Sales Presentation', 'Account Review Meeting', 'Onboarding Session',
            'Stakeholder Alignment', 'Solution Design Meeting', 'Pricing Negotiation', 'Implementation Planning',
            'Customer Success Check-in', 'Renewal Discussion', 'Partnership Meeting', 'Team Stand-up'
        ];
        
        $descriptions = [
            'Discuss Q1 performance and upcoming initiatives',
            'Product demonstration for key stakeholders',
            'Follow-up on previous conversation and next steps',
            'Present detailed proposal and answer questions',
            'Review contract terms and conditions',
            'Training session for new features',
            'Quarterly business review and planning',
            'Initial discovery call to understand requirements',
            'Technical consultation for implementation',
            'Sales presentation to decision makers',
            'Account review and relationship building',
            'Onboarding session for new customers',
            'Align stakeholders on project objectives',
            'Design solution architecture',
            'Negotiate pricing and contract terms',
            'Plan implementation timeline and resources',
            'Check in on customer satisfaction and success',
            'Discuss contract renewal and expansion',
            'Explore partnership opportunities',
            'Team stand-up and status update'
        ];
        
        for ($i = 0; $i < 80; $i++) {
            $eventType = $eventTypes[array_rand($eventTypes)];
            $startDate = now()->addDays(rand(-30, 60));
            $duration = [30, 60, 90, 120][array_rand([30, 60, 90, 120])];
            $isAllDay = rand(0, 10) < 1;
            
            $relatedModelTypes = [
                Account::class,
                Contact::class,
                Deal::class,
                Lead::class,
            ];
            $relatedModelType = $relatedModelTypes[array_rand($relatedModelTypes)];
            
            // Get the related model based on type
            switch ($relatedModelType) {
                case Account::class:
                    $related = $accounts->count() > 0 ? $accounts->random() : null;
                    break;
                case Contact::class:
                    $related = $contacts->count() > 0 ? $contacts->random() : null;
                    break;
                case Deal::class:
                    $related = $deals->count() > 0 ? $deals->random() : null;
                    break;
                case Lead::class:
                    $related = $leads->count() > 0 ? $leads->random() : null;
                    break;
                default:
                    $related = null;
            }
            
            if (!$related) {
                continue; // Skip if no related model available
            }
            
            CalendarEvent::create([
                'title' => $eventTitles[array_rand($eventTitles)],
                'description' => $descriptions[array_rand($descriptions)],
                'start_time' => $isAllDay ? $startDate->startOfDay() : $startDate->setTime(rand(9, 17), [0, 15, 30, 45][array_rand([0, 15, 30, 45])]),
                'end_time' => $isAllDay ? $startDate->endOfDay() : $startDate->copy()->addMinutes($duration),
                'event_type' => $eventType,
                'related_type' => $relatedModelType,
                'related_id' => $related->id,
                'user_id' => $users->random()->id,
                'attendees' => [
                    $users->random()->email,
                    $users->random()->email,
                ],
                'location' => $locations[array_rand($locations)],
                'is_all_day' => $isAllDay,
                'recurrence_pattern' => rand(0, 10) < 1 ? ['daily', 'weekly', 'monthly'][array_rand(['daily', 'weekly', 'monthly'])] : null,
                'send_reminder' => rand(0, 1) == 1,
                'reminder_minutes' => [15, 30, 60, 1440][array_rand([15, 30, 60, 1440])],
                'status' => ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'][array_rand(['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'])],
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }
    }
}

