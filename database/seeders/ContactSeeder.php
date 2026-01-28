<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $users = User::where('is_active', true)->get();
        
        $firstNames = ['James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];
        $titles = ['CEO', 'CTO', 'CFO', 'VP Sales', 'Director', 'Manager', 'Senior Manager', 'Account Executive', 'Business Development', 'Operations Manager'];
        $departments = ['Sales', 'Marketing', 'Operations', 'IT', 'Finance', 'HR', 'Product Development', 'Customer Service'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        
        for ($i = 0; $i < 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $account = $accounts->random();
            $city = $cities[array_rand($cities)];
            
            Contact::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'title' => $titles[array_rand($titles)],
                'email' => strtolower($firstName . '.' . $lastName . '@' . str_replace(' ', '', strtolower($account->account_name)) . '.com'),
                'phone' => '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'mobile' => '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'department' => $departments[array_rand($departments)],
                'address' => rand(100, 9999) . ' Contact Street',
                'city' => $city,
                'state' => ['CA', 'NY', 'TX', 'FL', 'IL'][array_rand(['CA', 'NY', 'TX', 'FL', 'IL'])],
                'country' => 'United States',
                'postal_code' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'birthdate' => now()->subYears(rand(25, 65))->subDays(rand(0, 365)),
                'notes' => rand(0, 1) ? 'Key decision maker. Preferred contact method: email.' : 'Regular contact. Responds well to phone calls.',
                'account_id' => $account->id,
                'assigned_to' => $users->random()->id ?? null,
                'status' => ['active', 'inactive'][array_rand(['active', 'inactive'])],
                'created_at' => now()->subDays(rand(0, 120)),
            ]);
        }
    }
}
