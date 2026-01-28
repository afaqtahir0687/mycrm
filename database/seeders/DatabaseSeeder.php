<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AccountSeeder::class,
            LeadSeeder::class,
            ContactSeeder::class,
            DealSeeder::class,
            OpportunitySeeder::class,
            QuotationSeeder::class,
            InvoiceSeeder::class,
            SupportTicketSeeder::class,
            TaskSeeder::class,
            CommunicationSeeder::class,
            AutomationWorkflowSeeder::class,
            CalendarEventSeeder::class,
            EmailTemplateSeeder::class,
            ActivitySeeder::class,
            NotificationSeeder::class,
            TagSeeder::class, // TagSeeder must run last as it attaches tags to other models
        ]);
    }
}
