<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $sources = ['Website', 'Referral', 'Cold Call', 'Email Campaign', 'Trade Show', 'Social Media', 'Partner', 'LinkedIn', 'Facebook', 'Data Scraping', 'Google Ads', 'Bing Ads', 'YouTube', 'Podcast', 'Webinar'];
        $industries = ['Information Technology', 'Software Development', 'Healthcare', 'Finance & Banking', 'Retail & E-commerce', 'Manufacturing', 'Education', 'Real Estate', 'Telecommunications', 'Energy & Utilities', 'Transportation & Logistics', 'Construction', 'Hospitality & Tourism', 'Food & Beverage', 'Automotive', 'Pharmaceuticals', 'Media & Entertainment', 'Legal Services', 'Consulting', 'Non-Profit'];
        $statuses = ['new', 'contacted', 'qualified', 'converted', 'lost'];
        
        // International names including Pakistan and GCC
        $firstNames = [
            // Western
            'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth', 
            'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Christopher', 'Karen',
            // Middle Eastern/Pakistani
            'Ahmed', 'Fatima', 'Muhammad', 'Aisha', 'Ali', 'Zainab', 'Hassan', 'Khadija', 'Omar', 'Maryam',
            'Usman', 'Amina', 'Ibrahim', 'Hafsa', 'Khalid', 'Safia', 'Yusuf', 'Layla', 'Hamza', 'Noor'
        ];
        $lastNames = [
            // Western
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
            'Hernandez', 'Lopez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee',
            // Middle Eastern/Pakistani
            'Khan', 'Ali', 'Ahmed', 'Hassan', 'Hussain', 'Malik', 'Sheikh', 'Rehman', 'Abbas', 'Iqbal',
            'Raza', 'Zaman', 'Baig', 'Butt', 'Qureshi', 'Siddiqui', 'Al-Mansoori', 'Al-Saud', 'Al-Nahyan', 'Al-Thani'
        ];
        
        // International companies
        $companies = [
            // Pakistan
            'PakTech Solutions', 'Karachi Enterprises', 'Lahore Digital', 'Islamabad Innovations', 'Faisalabad Industries',
            'Pak Software House', 'National Trading Co', 'Premium Services PK', 'Modern Solutions PK', 'Elite Business PK',
            // GCC
            'Dubai Tech Ventures', 'Riyadh Business Group', 'Abu Dhabi Holdings', 'Doha Enterprises', 'Kuwait Trading',
            'Sharjah Industries', 'Jeddah Solutions', 'Manama Corp', 'Muscat Services', 'Al Khobar Technologies',
            // International
            'Tech Solutions Inc', 'Global Enterprises', 'Modern Services Ltd', 'Innovation Corp', 'Premium Solutions',
            'Advanced Systems', 'Prime Technologies', 'Elite Services', 'Strategic Partners', 'Dynamic Solutions',
            'Future Industries', 'NextGen Corp', 'Smart Business Solutions', 'Digital Ventures', 'Cloud Services Ltd'
        ];
        
        // International cities
        $locations = [
            // Pakistan
            ['city' => 'Karachi', 'state' => 'Sindh', 'country' => 'Pakistan', 'postal' => '75' . rand(100, 999)],
            ['city' => 'Lahore', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '54' . rand(100, 999)],
            ['city' => 'Islamabad', 'state' => 'ICT', 'country' => 'Pakistan', 'postal' => '44' . rand(100, 999)],
            ['city' => 'Faisalabad', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '38' . rand(100, 999)],
            ['city' => 'Rawalpindi', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '46' . rand(100, 999)],
            ['city' => 'Multan', 'state' => 'Punjab', 'country' => 'Pakistan', 'postal' => '60' . rand(100, 999)],
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
            ['city' => 'Miami', 'state' => 'FL', 'country' => 'United States', 'postal' => str_pad(rand(33000, 33999), 5, '0', STR_PAD_LEFT)],
            // UK
            ['city' => 'London', 'state' => 'England', 'country' => 'United Kingdom', 'postal' => strtoupper(substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 1)) . rand(1, 2) . ' ' . rand(1, 9) . substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 2)],
            // Others
            ['city' => 'Toronto', 'state' => 'Ontario', 'country' => 'Canada', 'postal' => strtoupper(substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 1)) . rand(1, 9) . substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 1) . ' ' . rand(1, 9) . substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 25), 1) . rand(1, 9)],
            ['city' => 'Sydney', 'state' => 'NSW', 'country' => 'Australia', 'postal' => rand(1000, 2999)],
        ];
        
        $phoneFormats = [
            'Pakistan' => fn() => '+92-' . ['300', '301', '302', '303', '304', '305', '321', '322', '331', '332'][array_rand(['300', '301', '302', '303', '304', '305', '321', '322', '331', '332'])] . '-' . rand(1000000, 9999999),
            'Saudi Arabia' => fn() => '+966-5' . rand(0, 9) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'United Arab Emirates' => fn() => '+971-5' . rand(0, 9) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'Qatar' => fn() => '+974-' . rand(3000, 7999) . '-' . rand(1000, 9999),
            'Kuwait' => fn() => '+965-' . rand(5000, 9999) . '-' . rand(1000, 9999),
            'Bahrain' => fn() => '+973-' . rand(3000, 9999) . '-' . rand(1000, 9999),
            'Oman' => fn() => '+968-' . rand(9000, 9999) . '-' . rand(1000, 9999),
            'United States' => fn() => '+1-' . ['212', '213', '214', '215', '216', '310', '312', '313', '314', '415', '469', '713', '720', '770', '786', '818'][array_rand(['212', '213', '214', '215', '216', '310', '312', '313', '314', '415', '469', '713', '720', '770', '786', '818'])] . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'United Kingdom' => fn() => '+44-20-' . rand(7000, 8999) . '-' . rand(1000, 9999),
            'Canada' => fn() => '+1-' . ['416', '514', '604', '613', '647', '778'][array_rand(['416', '514', '604', '613', '647', '778'])] . '-' . rand(100, 999) . '-' . rand(1000, 9999),
            'Australia' => fn() => '+61-2-' . rand(8000, 9999) . '-' . rand(1000, 9999),
        ];
        
        for ($i = 0; $i < 200; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $company = $companies[array_rand($companies)];
            $location = $locations[array_rand($locations)];
            
            $phoneFormat = $phoneFormats[$location['country']] ?? $phoneFormats['United States'];
            $phone = $phoneFormat();
            
            $leadScore = rand(20, 95);
            $aiScore = $leadScore + rand(-10, 10);
            $aiScore = max(0, min(100, $aiScore));
            
            Lead::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'company_name' => $company,
                'email' => strtolower($firstName . '.' . $lastName . rand(1, 999) . '@' . strtolower(str_replace(' ', '', explode(' ', $company)[0])) . '.com'),
                'phone' => $phone,
                'mobile' => $phone,
                'address' => rand(10, 999) . ' ' . ['Main Street', 'Business Avenue', 'Commercial Road', 'Trade Center', 'Market Square', 'Tech Park', 'Industrial Area'][array_rand(['Main Street', 'Business Avenue', 'Commercial Road', 'Trade Center', 'Market Square', 'Tech Park', 'Industrial Area'])],
                'city' => $location['city'],
                'state' => $location['state'],
                'country' => $location['country'],
                'postal_code' => $location['postal'],
                'website' => 'https://www.' . strtolower(str_replace([' ', 'Co', 'Ltd', 'Inc', 'Corp', 'LLC'], ['', '', '', '', '', ''], $company)) . '.com',
                'lead_source' => $sources[array_rand($sources)],
                'industry' => $industries[array_rand($industries)],
                'lead_score' => $leadScore,
                'ai_score' => $aiScore,
                'ai_insights' => $leadScore > 70 ? 'High-value prospect with strong conversion potential. Recommend immediate follow-up.' : ($leadScore > 50 ? 'Moderate interest level. Follow up within 48 hours.' : 'Initial contact needed. Monitor engagement.'),
                'ai_recommendations' => $leadScore > 70 ? ['Schedule demo call', 'Send detailed proposal', 'Invite to webinar'] : ['Send introductory email', 'Follow up call', 'Share case studies'],
                'sentiment' => ['positive', 'neutral', 'positive'][array_rand(['positive', 'neutral', 'positive'])],
                'status' => $statuses[array_rand($statuses)],
                'notes' => [
                    'Interested in our premium services. Requires follow-up within 48 hours.',
                    'Initial contact made. Awaiting response.',
                    'Expressed interest in enterprise solution. Needs technical consultation.',
                    'Looking for cost-effective solution. Budget discussions needed.',
                    'High priority lead. Decision maker identified.',
                    'Requested product demonstration. Schedule next week.',
                    'Follow-up required after initial presentation.',
                    'Interested in annual contract. Discuss pricing options.',
                ][array_rand(['Interested in our premium services. Requires follow-up within 48 hours.', 'Initial contact made. Awaiting response.', 'Expressed interest in enterprise solution. Needs technical consultation.', 'Looking for cost-effective solution. Budget discussions needed.', 'High priority lead. Decision maker identified.', 'Requested product demonstration. Schedule next week.', 'Follow-up required after initial presentation.', 'Interested in annual contract. Discuss pricing options.'])],
                'assigned_to' => $users->random()->id ?? null,
                'created_by' => $users->random()->id ?? null,
                'created_at' => now()->subDays(rand(0, 180)),
                'converted_at' => (rand(0, 10) < 2) ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}
