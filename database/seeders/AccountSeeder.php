<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $industries = [
            'Information Technology', 'Software Development', 'Healthcare', 'Finance & Banking', 
            'Retail & E-commerce', 'Manufacturing', 'Education', 'Real Estate', 'Telecommunications',
            'Energy & Utilities', 'Transportation & Logistics', 'Construction', 'Hospitality & Tourism',
            'Food & Beverage', 'Automotive', 'Pharmaceuticals', 'Media & Entertainment', 'Legal Services',
            'Consulting', 'Non-Profit', 'Agriculture', 'Mining', 'Textiles', 'Chemical Manufacturing'
        ];
        
        // International companies
        $companies = [
            // Pakistan
            'PakTech Solutions', 'Karachi Trading Company', 'Lahore Digital Services', 'Islamabad Industries',
            'Faisalabad Manufacturing', 'Pak Software House', 'National Trading Co', 'Premium Services PK',
            // GCC
            'Dubai Tech Ventures', 'Riyadh Business Group', 'Abu Dhabi Holdings', 'Doha Enterprises',
            'Kuwait Trading Corp', 'Sharjah Industries', 'Jeddah Solutions', 'Manama Corporation',
            'Muscat Services Ltd', 'Al Khobar Technologies',
            // International
            'Acme Corporation', 'Tech Innovations Ltd', 'Global Services Inc', 'Premium Solutions Corp',
            'Modern Enterprises', 'Strategic Partners LLC', 'Advanced Systems Group', 'Elite Business Solutions',
            'Dynamic Technologies', 'Prime Services Ltd', 'Innovation Hub', 'Future Solutions Inc',
            'NextGen Industries', 'Smart Systems Corp', 'Digital Solutions Group', 'Enterprise Partners',
            'Mega Corp', 'Super Tech Ltd', 'Best Services Inc', 'Top Companies Group',
            'Worldwide Business Solutions', 'International Trade Co', 'Global Ventures', 'Continental Services'
        ];
        
        $locations = [
            // Pakistan
            ['city' => 'Karachi', 'state' => 'Sindh', 'country' => 'Pakistan', 'postal' => '75' . rand(100, 999)],
            ['city' => 'Lahore', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '54' . rand(100, 999)],
            ['city' => 'Islamabad', 'state' => 'ICT', 'country' => 'Pakistan', 'postal' => '44' . rand(100, 999)],
            ['city' => 'Faisalabad', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '38' . rand(100, 999)],
            // GCC
            ['city' => 'Dubai', 'state' => 'Dubai', 'country' => 'United Arab Emirates', 'postal' => rand(10000, 99999)],
            ['city' => 'Riyadh', 'state' => 'Riyadh', 'country' => 'Saudi Arabia', 'postal' => rand(10000, 99999)],
            ['city' => 'Abu Dhabi', 'state' => 'Abu Dhabi', 'country' => 'United Arab Emirates', 'postal' => rand(10000, 99999)],
            ['city' => 'Doha', 'state' => 'Doha', 'country' => 'Qatar', 'postal' => rand(10000, 99999)],
            ['city' => 'Kuwait City', 'state' => 'Kuwait', 'country' => 'Kuwait', 'postal' => rand(10000, 99999)],
            ['city' => 'Jeddah', 'state' => 'Makkah', 'country' => 'Saudi Arabia', 'postal' => rand(10000, 99999)],
            ['city' => 'Manama', 'state' => 'Manama', 'country' => 'Bahrain', 'postal' => rand(10000, 99999)],
            ['city' => 'Muscat', 'state' => 'Muscat', 'country' => 'Oman', 'postal' => rand(10000, 99999)],
            // US
            ['city' => 'New York', 'state' => 'NY', 'country' => 'United States', 'postal' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT)],
            ['city' => 'Los Angeles', 'state' => 'CA', 'country' => 'United States', 'postal' => str_pad(rand(90000, 99999), 5, '0', STR_PAD_LEFT)],
            ['city' => 'Chicago', 'state' => 'IL', 'country' => 'United States', 'postal' => str_pad(rand(60000, 69999), 5, '0', STR_PAD_LEFT)],
            ['city' => 'Houston', 'state' => 'TX', 'country' => 'United States', 'postal' => str_pad(rand(77000, 77999), 5, '0', STR_PAD_LEFT)],
            // UK
            ['city' => 'London', 'state' => 'England', 'country' => 'United Kingdom', 'postal' => strtoupper(substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 1)) . rand(1, 2) . ' ' . rand(1, 9) . substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 2)],
        ];
        
        $phoneFormats = [
            'Pakistan' => fn($index) => '+92-' . ['300', '301', '302', '303', '321', '322', '331', '332'][array_rand(['300', '301', '302', '303', '321', '322', '331', '332'])] . '-' . rand(1000000, 9999999),
            'Saudi Arabia' => fn($index) => '+966-1' . rand(1, 9) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            'United Arab Emirates' => fn($index) => '+971-4-' . rand(200, 999) . '-' . rand(1000, 9999),
            'Qatar' => fn($index) => '+974-4' . rand(4, 8) . rand(0, 9) . '-' . rand(1000, 9999),
            'Kuwait' => fn($index) => '+965-2' . rand(2, 6) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            'Bahrain' => fn($index) => '+973-1' . rand(7, 9) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'Oman' => fn($index) => '+968-2' . rand(4, 9) . '-' . rand(100000, 999999),
            'United States' => fn($index) => '+1-' . ['212', '213', '214', '310', '312', '415', '469', '713'][array_rand(['212', '213', '214', '310', '312', '415', '469', '713'])] . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'United Kingdom' => fn($index) => '+44-20-' . rand(7000, 8999) . '-' . rand(1000, 9999),
        ];
        
        foreach ($companies as $index => $company) {
            $location = $locations[array_rand($locations)];
            $phoneFormat = $phoneFormats[$location['country']] ?? $phoneFormats['United States'];
            
            Account::create([
                'account_name' => $company,
                'account_type' => ['Customer', 'Partner', 'Prospect', 'Competitor', 'Reseller'][array_rand(['Customer', 'Partner', 'Prospect', 'Competitor', 'Reseller'])],
                'industry' => $industries[array_rand($industries)],
                'email' => 'contact@' . strtolower(str_replace([' ', 'Ltd', 'Inc', 'Corp', 'LLC', 'Co'], ['', '', '', '', '', ''], $company)) . '.com',
                'phone' => $phoneFormat($index),
                'website' => 'https://www.' . strtolower(str_replace([' ', 'Ltd', 'Inc', 'Corp', 'LLC', 'Co'], ['', '', '', '', '', ''], $company)) . '.com',
                'billing_address' => rand(10, 999) . ' ' . ['Business Avenue', 'Commercial Road', 'Trade Center', 'Financial District', 'Industrial Area'][array_rand(['Business Avenue', 'Commercial Road', 'Trade Center', 'Financial District', 'Industrial Area'])],
                'billing_city' => $location['city'],
                'billing_state' => $location['state'],
                'billing_country' => $location['country'],
                'billing_postal_code' => $location['postal'],
                'shipping_address' => rand(10, 999) . ' ' . ['Distribution Street', 'Warehouse Road', 'Logistics Center', 'Shipping Plaza'][array_rand(['Distribution Street', 'Warehouse Road', 'Logistics Center', 'Shipping Plaza'])],
                'shipping_city' => $location['city'],
                'shipping_state' => $location['state'],
                'shipping_country' => $location['country'],
                'shipping_postal_code' => $location['postal'],
                'employees' => [rand(1, 50), rand(50, 250), rand(250, 1000), rand(1000, 5000), rand(5000, 50000)][array_rand([rand(1, 50), rand(50, 250), rand(250, 1000), rand(1000, 5000), rand(5000, 50000)])],
                'annual_revenue' => [rand(100000, 1000000), rand(1000000, 10000000), rand(10000000, 100000000), rand(100000000, 500000000)][array_rand([rand(100000, 1000000), rand(1000000, 10000000), rand(10000000, 100000000), rand(100000000, 500000000)])],
                'description' => 'Established ' . strtolower($industries[array_rand($industries)]) . ' company in ' . $location['city'] . ', ' . $location['country'] . ' with strong market presence and excellent reputation in the industry.',
                'status' => ['active', 'active', 'active', 'inactive', 'suspended'][array_rand(['active', 'active', 'active', 'inactive', 'suspended'])],
                'owner_id' => $users->random()->id ?? null,
                'created_at' => now()->subDays(rand(0, 365)),
            ]);
        }
    }
}
