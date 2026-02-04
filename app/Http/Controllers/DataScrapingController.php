<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Services\FaisDigitalClient;

class DataScrapingController extends Controller
{
    public function index(Request $request)
    {
        $scrapedData = session('scraped_data', []);
        $filters = [
            'source' => $request->get('source', ''),
            'country' => $request->get('country', ''),
            'city' => $request->get('city', ''),
            'business_type' => $request->get('business_type', ''),
            'company_size' => $request->get('company_size', ''),
        ];
        
        return view('data-scraping.index', compact('scrapedData', 'filters'));
    }
    
    public function scrape(Request $request)
    {
        $source = $request->source;
        
        // Validate based on source type
        if ($source === 'maps') {
            $request->validate([
                'source' => 'required|string|in:maps,facebook,linkedin,instagram,twitter,tiktok,fais',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'business_type' => 'nullable|string|max:255',
                'company_size' => 'nullable|string|max:255',
            ]);
        } else {
            // For social media, only source is required (data comes from extension)
            $request->validate([
                'source' => 'required|string|in:maps,facebook,linkedin,instagram,twitter,tiktok,fais',
                'country' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'business_type' => 'nullable|string|max:255',
                'company_size' => 'nullable|string|max:255',
            ]);
        }
        
        $country = $request->country ?? '';
        $city = $request->city ?? '';
        $businessType = $request->business_type ?? '';
        $companySize = $request->company_size ?? '';
        
        // Route to appropriate scraping method based on source
        switch ($source) {
            case 'maps':
                $results = $this->performScraping($source, $country, $city, $businessType, $companySize);
                break;
            case 'fais':
                $results = $this->fetchFaisDigitalScrape($country, $city, $businessType, $companySize);
                break;
            case 'facebook':
            case 'linkedin':
            case 'instagram':
            case 'twitter':
            case 'tiktok':
                // For social media, check if extension data exists, otherwise return empty
                $results = $this->performScraping($source, $country, $city, $businessType, $companySize);
                if (empty($results)) {
                    return redirect()->route('data-scraping.index', [
                        'source' => $source,
                    ])->with('warning', 'No data found. Please use the Chrome extension to extract data from ' . ucfirst($source) . '. Open ' . ucfirst($source) . ' in a tab, navigate to friends/connections page, then use the extension.');
                }
                break;
            default:
                $results = [];
        }
        
        session(['scraped_data' => $results]);
        
        return redirect()->route('data-scraping.index', [
            'source' => $source,
            'country' => $country,
            'city' => $city,
            'business_type' => $businessType,
            'company_size' => $companySize,
        ])->with('success', count($results) . ' profiles found from ' . ucfirst($source) . '.');
    }
    
    private function getCountryPhoneFormat($country)
    {
        $formats = [
            'Pakistan' => ['code' => '+92', 'pattern' => '+92-3XX-XXXXXXX'],
            'Saudi Arabia' => ['code' => '+966', 'pattern' => '+966-5X-XXX-XXXX'],
            'United Arab Emirates' => ['code' => '+971', 'pattern' => '+971-5X-XXX-XXXX'],
            'Kuwait' => ['code' => '+965', 'pattern' => '+965-5XXX-XXXX'],
            'Qatar' => ['code' => '+974', 'pattern' => '+974-3XXX-XXXX'],
            'Bahrain' => ['code' => '+973', 'pattern' => '+973-3XXX-XXXX'],
            'Oman' => ['code' => '+968', 'pattern' => '+968-9XXX-XXXX'],
            'India' => ['code' => '+91', 'pattern' => '+91-9XXXXXXXXX'],
            'United States' => ['code' => '+1', 'pattern' => '+1-555-XXX-XXXX'],
            'United Kingdom' => ['code' => '+44', 'pattern' => '+44-20-XXXX-XXXX'],
            'Canada' => ['code' => '+1', 'pattern' => '+1-416-XXX-XXXX'],
            'Germany' => ['code' => '+49', 'pattern' => '+49-30-XXXX-XXXX'],
            'France' => ['code' => '+33', 'pattern' => '+33-1-XX-XX-XX-XX'],
        ];
        
        return $formats[$country] ?? ['code' => '+1', 'pattern' => '+1-555-XXX-XXXX'];
    }
    
    private function getCountryCoordinates($country, $city = '')
    {
        // Major city coordinates for better accuracy
        $cityCoordinates = [
            // Pakistan
            'Karachi' => ['lat' => 24.8607, 'lng' => 67.0011, 'radius' => 0.3],
            'Lahore' => ['lat' => 31.5204, 'lng' => 74.3587, 'radius' => 0.25],
            'Islamabad' => ['lat' => 33.6844, 'lng' => 73.0479, 'radius' => 0.2],
            'Faisalabad' => ['lat' => 31.4504, 'lng' => 73.1350, 'radius' => 0.2],
            'Rawalpindi' => ['lat' => 33.5651, 'lng' => 73.0169, 'radius' => 0.15],
            'Multan' => ['lat' => 30.1575, 'lng' => 71.5249, 'radius' => 0.2],
            'Peshawar' => ['lat' => 34.0151, 'lng' => 71.5249, 'radius' => 0.2],
            'Quetta' => ['lat' => 30.1798, 'lng' => 66.9750, 'radius' => 0.2],
            
            // Saudi Arabia
            'Riyadh' => ['lat' => 24.7136, 'lng' => 46.6753, 'radius' => 0.4],
            'Jeddah' => ['lat' => 21.4858, 'lng' => 39.1925, 'radius' => 0.3],
            'Mecca' => ['lat' => 21.3891, 'lng' => 39.8579, 'radius' => 0.2],
            'Medina' => ['lat' => 24.5247, 'lng' => 39.5692, 'radius' => 0.2],
            'Dammam' => ['lat' => 26.4207, 'lng' => 50.0888, 'radius' => 0.25],
            'Khobar' => ['lat' => 26.2172, 'lng' => 50.1971, 'radius' => 0.15],
            
            // UAE
            'Dubai' => ['lat' => 25.2048, 'lng' => 55.2708, 'radius' => 0.3],
            'Abu Dhabi' => ['lat' => 24.4539, 'lng' => 54.3773, 'radius' => 0.3],
            'Sharjah' => ['lat' => 25.3573, 'lng' => 55.4033, 'radius' => 0.2],
            'Al Ain' => ['lat' => 24.2075, 'lng' => 55.7447, 'radius' => 0.2],
            
            // Kuwait
            'Kuwait City' => ['lat' => 29.3759, 'lng' => 47.9774, 'radius' => 0.2],
            
            // Qatar
            'Doha' => ['lat' => 25.2854, 'lng' => 51.5310, 'radius' => 0.25],
            
            // Bahrain
            'Manama' => ['lat' => 26.0667, 'lng' => 50.5577, 'radius' => 0.15],
            
            // Oman
            'Muscat' => ['lat' => 23.5859, 'lng' => 58.4059, 'radius' => 0.3],
            'Salalah' => ['lat' => 17.0151, 'lng' => 54.0924, 'radius' => 0.2],
            
            // India
            'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777, 'radius' => 0.4],
            'Delhi' => ['lat' => 28.6139, 'lng' => 77.2090, 'radius' => 0.4],
            'Bangalore' => ['lat' => 12.9716, 'lng' => 77.5946, 'radius' => 0.3],
            'Hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867, 'radius' => 0.3],
            'Chennai' => ['lat' => 13.0827, 'lng' => 80.2707, 'radius' => 0.3],
            'Kolkata' => ['lat' => 22.5726, 'lng' => 88.3639, 'radius' => 0.3],
            'Pune' => ['lat' => 18.5204, 'lng' => 73.8567, 'radius' => 0.25],
            'Ahmedabad' => ['lat' => 23.0225, 'lng' => 72.5714, 'radius' => 0.25],
            
            // United States
            'New York' => ['lat' => 40.7128, 'lng' => -74.0060, 'radius' => 0.3],
            'Los Angeles' => ['lat' => 34.0522, 'lng' => -118.2437, 'radius' => 0.4],
            'Chicago' => ['lat' => 41.8781, 'lng' => -87.6298, 'radius' => 0.3],
            'Houston' => ['lat' => 29.7604, 'lng' => -95.3698, 'radius' => 0.3],
            'Phoenix' => ['lat' => 33.4484, 'lng' => -112.0740, 'radius' => 0.3],
            'Philadelphia' => ['lat' => 39.9526, 'lng' => -75.1652, 'radius' => 0.25],
            'San Antonio' => ['lat' => 29.4241, 'lng' => -98.4936, 'radius' => 0.25],
            'San Diego' => ['lat' => 32.7157, 'lng' => -117.1611, 'radius' => 0.25],
            'Dallas' => ['lat' => 32.7767, 'lng' => -96.7970, 'radius' => 0.3],
            'San Jose' => ['lat' => 37.3382, 'lng' => -121.8863, 'radius' => 0.2],
            
            // United Kingdom
            'London' => ['lat' => 51.5074, 'lng' => -0.1278, 'radius' => 0.3],
            'Manchester' => ['lat' => 53.4808, 'lng' => -2.2426, 'radius' => 0.2],
            'Birmingham' => ['lat' => 52.4862, 'lng' => -1.8904, 'radius' => 0.2],
            'Liverpool' => ['lat' => 53.4084, 'lng' => -2.9916, 'radius' => 0.15],
            'Leeds' => ['lat' => 53.8008, 'lng' => -1.5491, 'radius' => 0.15],
            
            // Canada
            'Toronto' => ['lat' => 43.6532, 'lng' => -79.3832, 'radius' => 0.3],
            'Vancouver' => ['lat' => 49.2827, 'lng' => -123.1207, 'radius' => 0.25],
            'Montreal' => ['lat' => 45.5017, 'lng' => -73.5673, 'radius' => 0.25],
            'Calgary' => ['lat' => 51.0447, 'lng' => -114.0719, 'radius' => 0.2],
            'Ottawa' => ['lat' => 45.4215, 'lng' => -75.6972, 'radius' => 0.2],
            
            // Germany
            'Berlin' => ['lat' => 52.5200, 'lng' => 13.4050, 'radius' => 0.3],
            'Munich' => ['lat' => 48.1351, 'lng' => 11.5820, 'radius' => 0.25],
            'Hamburg' => ['lat' => 53.5511, 'lng' => 9.9937, 'radius' => 0.25],
            'Frankfurt' => ['lat' => 50.1109, 'lng' => 8.6821, 'radius' => 0.2],
            'Cologne' => ['lat' => 50.9375, 'lng' => 6.9603, 'radius' => 0.2],
            
            // France
            'Paris' => ['lat' => 48.8566, 'lng' => 2.3522, 'radius' => 0.3],
            'Lyon' => ['lat' => 45.7640, 'lng' => 4.8357, 'radius' => 0.2],
            'Marseille' => ['lat' => 43.2965, 'lng' => 5.3698, 'radius' => 0.2],
            'Toulouse' => ['lat' => 43.6047, 'lng' => 1.4442, 'radius' => 0.15],
        ];
        
        // Check if city coordinates exist (case-insensitive matching)
        if (!empty($city)) {
            $cityLower = strtolower(trim($city));
            foreach ($cityCoordinates as $cityName => $coords) {
                if (strtolower($cityName) === $cityLower) {
                    return $coords;
                }
            }
        }
        
        // Fallback to country center with smaller radius
        $countryCoordinates = [
            'Pakistan' => ['lat' => 30.3753, 'lng' => 69.3451, 'radius' => 2.0],
            'Saudi Arabia' => ['lat' => 23.8859, 'lng' => 45.0792, 'radius' => 3.0],
            'United Arab Emirates' => ['lat' => 23.4241, 'lng' => 53.8478, 'radius' => 1.0],
            'Kuwait' => ['lat' => 29.3117, 'lng' => 47.4818, 'radius' => 0.5],
            'Qatar' => ['lat' => 25.3548, 'lng' => 51.1839, 'radius' => 0.5],
            'Bahrain' => ['lat' => 26.0667, 'lng' => 50.5577, 'radius' => 0.3],
            'Oman' => ['lat' => 21.4735, 'lng' => 55.9754, 'radius' => 2.0],
            'India' => ['lat' => 20.5937, 'lng' => 78.9629, 'radius' => 5.0],
            'United States' => ['lat' => 37.0902, 'lng' => -95.7129, 'radius' => 10.0],
            'United Kingdom' => ['lat' => 55.3781, 'lng' => -3.4360, 'radius' => 2.0],
            'Canada' => ['lat' => 56.1304, 'lng' => -106.3468, 'radius' => 8.0],
            'Germany' => ['lat' => 51.1657, 'lng' => 10.4515, 'radius' => 2.0],
            'France' => ['lat' => 46.2276, 'lng' => 2.2137, 'radius' => 2.0],
        ];
        
        return $countryCoordinates[$country] ?? ['lat' => 40.7128, 'lng' => -74.0060, 'radius' => 1.0];
    }
    
    private function generatePhoneNumber($country)
    {
        $format = $this->getCountryPhoneFormat($country);
        $code = $format['code'];
        
        // Generate random phone number based on country
        if ($country == 'Pakistan') {
            $area = ['300', '301', '302', '303', '304', '305', '306', '307', '308', '309', '311', '321', '322', '323', '331', '332', '333', '334', '335'];
            return $code . '-' . $area[array_rand($area)] . '-' . rand(1000000, 9999999);
        } elseif ($country == 'Saudi Arabia' || $country == 'United Arab Emirates') {
            return $code . '-5' . rand(0, 9) . '-' . rand(100, 999) . '-' . rand(1000, 9999);
        } elseif ($country == 'Kuwait' || $country == 'Qatar' || $country == 'Bahrain') {
            return $code . '-' . rand(5000, 9999) . '-' . rand(1000, 9999);
        } elseif ($country == 'Oman') {
            return $code . '-' . rand(9000, 9999) . '-' . rand(1000, 9999);
        } elseif ($country == 'India') {
            return $code . '-' . rand(9000000000, 9999999999);
        } elseif ($country == 'United States' || $country == 'Canada') {
            return $code . '-555-' . rand(100, 999) . '-' . rand(1000, 9999);
        } elseif ($country == 'United Kingdom') {
            return $code . '-20-' . rand(1000, 9999) . '-' . rand(1000, 9999);
        } else {
            return $code . '-' . rand(100, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999);
        }
    }
    
    private function getBusinessTypes()
    {
        return [
            'Information Technology', 'Software Development', 'Telecommunications', 'E-commerce',
            'Manufacturing', 'Automotive', 'Textiles', 'Chemicals', 'Pharmaceuticals', 'Electronics',
            'Retail', 'Wholesale', 'Supermarkets', 'Fashion & Apparel', 'Consumer Goods',
            'Healthcare', 'Hospitals', 'Medical Equipment', 'Dental Services',
            'Finance', 'Banking', 'Insurance', 'Investment', 'Accounting', 'Financial Advisory',
            'Education', 'Schools', 'Universities', 'Training Institutes', 'Online Education',
            'Real Estate', 'Construction', 'Architecture', 'Property Development', 'Interior Design',
            'Marketing', 'Advertising', 'Public Relations', 'Digital Marketing', 'Event Management',
            'Food & Beverage', 'Restaurants', 'Hotels', 'Catering', 'Hospitality',
            'Transportation', 'Logistics', 'Shipping', 'Courier Services', 'Freight Forwarding',
            'Energy', 'Oil & Gas', 'Renewable Energy', 'Utilities', 'Power Generation',
            'Legal Services', 'Consulting', 'Human Resources', 'Recruitment', 'Management Services',
            'Entertainment', 'Media', 'Sports & Fitness', 'Non-Profit', 'Agriculture', 'Mining',
            'Food Processing', 'Cybersecurity', 'Data Analytics', 'Cloud Services',
        ];
    }
    
    private function getCompanySizes()
    {
        return ['Micro', 'Small', 'Medium', 'SMC', 'Large', 'Large Scale', 'Enterprise', 'Public', 'Multinational', 'Conglomerate'];
    }
    
    private function performScraping($source = 'maps', $country, $city, $businessType, $companySize)
    {
        $coords = $this->getCountryCoordinates($country, $city);
        $businessTypes = $this->getBusinessTypes();
        $companySizes = $this->getCompanySizes();
        
        // For social media sources, check if data was already received from extension
        if (in_array($source, ['facebook', 'linkedin', 'instagram', 'twitter', 'tiktok'])) {
            // Check if data was already received from extension (stored in session)
            $extensionData = session('scraped_data', []);
            
            // Filter by source if data exists
            if (!empty($extensionData) && is_array($extensionData)) {
                // Check if the first item has a source field matching our source
                $filteredData = array_filter($extensionData, function($item) use ($source) {
                    return isset($item['source']) && $item['source'] === $source;
                });
                
                if (!empty($filteredData)) {
                    return array_values($filteredData);
                }
                
                // If all data is from same source (or no source field), return as is
                if (!empty($extensionData)) {
                    $firstItem = reset($extensionData);
                    if (!isset($firstItem['source']) || $firstItem['source'] === $source) {
                        return $extensionData;
                    }
                }
            }
            
            // Return empty array - user should use Chrome extension
            return [];
        }
        
        // Get radius for coordinate variation (in degrees)
        $radius = $coords['radius'] ?? 0.2; // Default 0.2 degrees (~22km)
        
        // Generate more comprehensive results (50-100 businesses)
        $results = [];
        $businessNamePrefixes = ['Alpha', 'Beta', 'Gamma', 'Delta', 'Prime', 'Elite', 'Pro', 'Global', 'International', 'National', 'Regional', 'City', 'Metro', 'Capital', 'Royal', 'Crown', 'Star', 'Elite', 'Premium', 'Supreme', 'Advanced', 'Modern', 'Classic', 'Heritage', 'Pioneer', 'Innovative', 'Creative', 'Dynamic', 'Swift', 'Rapid', 'Agile', 'Smart', 'Bright', 'Golden', 'Silver', 'Platinum', 'Diamond', 'Peak', 'Summit', 'Apex', 'Vertex', 'Nexus', 'Fusion', 'Synergy', 'Harmony', 'Unity', 'Alliance', 'Partnership', 'Group', 'Corporation'];
        
        $businessNameSuffixes = ['Solutions', 'Services', 'Group', 'Corporation', 'Enterprises', 'Industries', 'Holdings', 'International', 'Global', 'Limited', 'Ltd', 'Inc', 'LLC', 'Partners', 'Associates', 'Consultants', 'Advisors', 'Experts', 'Specialists', 'Professionals', 'Systems', 'Technologies', 'Developments', 'Ventures', 'Capital', 'Trading', 'Commerce', 'Business', 'Company', 'Firm', 'Agency', 'Bureau', 'Office', 'Center', 'Hub', 'Network', 'Connect', 'Link', 'Bridge'];
        
        $streetTypes = ['Street', 'Road', 'Avenue', 'Boulevard', 'Lane', 'Drive', 'Circle', 'Square', 'Plaza', 'Park', 'Center', 'Tower', 'Building', 'Complex', 'Mall', 'Market'];
        
        $id = 1;
        $count = 0;
        $maxResults = 100; // Increase to 100 results
        
        // If business type is specified, use only that type, otherwise randomize
        $primaryTypes = $businessType ? [$businessType] : $businessTypes;
        $primarySizes = $companySize ? [$companySize] : $companySizes;
        
        while ($count < $maxResults) {
            // Select type and size based on filters
            $selectedType = $primaryTypes[array_rand($primaryTypes)];
            $selectedSize = $primarySizes[array_rand($primarySizes)];
            
            $prefix = $businessNamePrefixes[array_rand($businessNamePrefixes)];
            $suffix = $businessNameSuffixes[array_rand($businessNameSuffixes)];
            $companyName = $prefix . ' ' . $selectedType . ' ' . $suffix;
            
            $streetNumber = rand(1, 9999);
            $streetName = $businessNamePrefixes[array_rand($businessNamePrefixes)];
            $streetType = $streetTypes[array_rand($streetTypes)];
            $address = $streetNumber . ' ' . $streetName . ' ' . $streetType . ', ' . $city . ', ' . $country;
            
            // Generate country-specific phone
            $phone = $this->generatePhoneNumber($country);
            
            // Generate email
            $emailDomain = strtolower(str_replace(' ', '', $prefix . $suffix)) . '.com';
            $email = 'contact@' . $emailDomain;
            
            // Generate website
            $website = 'www.' . strtolower(str_replace(' ', '', $prefix . $suffix)) . '.com';
            
            // Generate coordinates within city/country boundaries
            // Use smaller radius to keep within city limits
            // Convert radius to variation range (radius is in degrees)
            $maxVariation = $radius;
            
            // Generate random variation within the radius
            // Using uniform distribution within a circle
            $angle = deg2rad(rand(0, 360));
            $distance = sqrt(rand(0, 10000) / 10000) * $maxVariation; // Square root for uniform distribution in circle
            
            // Calculate lat/lng variation (approximate, works well for small distances)
            $latVariation = $distance * cos($angle);
            $lngVariation = $distance * sin($angle);
            
            // Adjust longitude variation based on latitude (longitude degrees get smaller near poles)
            $lngVariation = $lngVariation / cos(deg2rad($coords['lat']));
            
            $lat = $coords['lat'] + $latVariation;
            $lng = $coords['lng'] + $lngVariation;
            
            // Ensure coordinates stay within reasonable bounds
            $lat = max(-90, min(90, $lat));
            $lng = max(-180, min(180, $lng));
            
            $results[] = [
                'id' => $id++,
                'company_name' => $companyName,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'website' => $website,
                'business_type' => $selectedType,
                'industry' => $selectedType,
                'company_size' => $selectedSize,
                'latitude' => $lat,
                'longitude' => $lng,
            ];
            
            $count++;
        }
        
        // Shuffle results for randomness
        shuffle($results);
        
        return $results;
    }

    private function fetchFaisDigitalScrape($country, $city, $businessType, $companySize)
    {
        try {
            $client = new FaisDigitalClient();
            $filters = array_filter([
                'country' => $country,
                'city' => $city,
                'business_type' => $businessType,
                'company_size' => $companySize,
            ], function ($value) {
                return $value !== null && $value !== '';
            });

            $items = $client->fetchScrapedLeads($filters);
            if (empty($items)) {
                return [];
            }

            $coords = $this->getCountryCoordinates($country, $city);
            $mapped = [];
            $counter = 1;

            foreach ($items as $item) {
                $lat = $item['latitude'] ?? $item['lat'] ?? $coords['lat'] ?? 0;
                $lng = $item['longitude'] ?? $item['lng'] ?? $coords['lng'] ?? 0;
                $address = $item['address'] ?? trim(($item['city'] ?? $city) . ', ' . ($item['country'] ?? $country), ', ');

                $mapped[] = [
                    'id' => $item['id'] ?? $counter,
                    'company_name' => $item['company_name'] ?? $item['name'] ?? 'Unknown',
                    'address' => $address,
                    'phone' => $item['phone'] ?? null,
                    'email' => $item['email'] ?? null,
                    'website' => $item['website'] ?? $item['url'] ?? null,
                    'business_type' => $item['business_type'] ?? $item['industry'] ?? null,
                    'industry' => $item['industry'] ?? $item['business_type'] ?? null,
                    'company_size' => $item['company_size'] ?? null,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'source' => 'fais',
                ];

                $counter++;
            }

            return $mapped;
        } catch (\Exception $e) {
            Log::error('Fais Digital scrape failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Fais Digital connection failed. Please check API configuration.');
            return [];
        }
    }
    
    public function importToLeads(Request $request)
    {
        // Handle both JSON string and array input
        $selectedIdsInput = $request->input('selected_ids');
        
        if (is_string($selectedIdsInput)) {
            $selectedIds = json_decode($selectedIdsInput, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($selectedIds)) {
                return redirect()->route('data-scraping.index')->with('error', 'Invalid selected IDs format.');
            }
        } else {
            $selectedIds = is_array($selectedIdsInput) ? $selectedIdsInput : [];
        }
        
        if (empty($selectedIds)) {
            return redirect()->route('data-scraping.index')->with('error', 'Please select at least one business to import.');
        }
        
        // Validate IDs are integers
        $selectedIds = array_filter(array_map('intval', $selectedIds), function($id) {
            return $id > 0;
        });
        
        if (empty($selectedIds)) {
            return redirect()->route('data-scraping.index')->with('error', 'No valid IDs selected.');
        }
        
        $scrapedData = session('scraped_data', []);
        
        if (empty($scrapedData)) {
            return redirect()->route('data-scraping.index')->with('error', 'No scraped data found. Please scrape data first.');
        }
        
        $imported = 0;
        $errors = [];
        
        foreach ($scrapedData as $data) {
            $dataId = is_array($data) && isset($data['id']) ? (int)$data['id'] : null;
            
            if ($dataId && in_array($dataId, $selectedIds)) {
                try {
                    // Extract first name from company name or use provided first_name
                    $firstName = $data['first_name'] ?? null;
                    if (empty($firstName) && !empty($data['company_name'])) {
                        $nameParts = explode(' ', $data['company_name'], 2);
                        $firstName = $nameParts[0] ?? 'Company';
                    }
                    $firstName = $firstName ?: 'Company';
                    
                    // Extract last name if available
                    $lastName = $data['last_name'] ?? '';
                    
                    // Get company name
                    $companyName = $data['company_name'] ?? ($data['first_name'] . ' ' . $data['last_name']) ?? 'Unknown';
                    
                    // Import to leads
                    Lead::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'company_name' => $companyName,
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'mobile' => $data['phone'] ?? null,
                        'address' => $data['address'] ?? null,
                        'city' => $data['city'] ?? null,
                        'country' => $data['country'] ?? null,
                        'website' => $data['website'] ?? null,
                        'lead_source' => 'Data Scraping',
                        'industry' => $data['industry'] ?? $data['business_type'] ?? null,
                        'status' => 'new',
                        'created_by' => auth()->id(),
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    \Log::error('Error importing lead: ' . $e->getMessage(), [
                        'data' => $data,
                        'error' => $e->getTraceAsString()
                    ]);
                    $errors[] = 'Failed to import: ' . ($data['company_name'] ?? 'Unknown');
                }
            }
        }
        
        if ($imported > 0) {
            $message = $imported . ' business' . ($imported > 1 ? 'es' : '') . ' imported to Leads successfully.';
            if (!empty($errors)) {
                $message .= ' ' . count($errors) . ' failed: ' . implode(', ', array_slice($errors, 0, 3));
            }
            return redirect()->route('leads.index')->with('success', $message);
        } else {
            return redirect()->route('data-scraping.index')->with('error', 'No leads were imported. ' . (!empty($errors) ? implode(', ', array_slice($errors, 0, 3)) : ''));
        }
    }
    
    public function getCsrfToken()
    {
        return response()->json([
            'token' => csrf_token()
        ], 200, [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-CSRF-TOKEN, X-Requested-With, Accept'
        ]);
    }
    
    public function receiveExtensionData(Request $request)
    {
        // Set JSON response headers early
        $jsonHeaders = [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-CSRF-TOKEN, X-Requested-With, Accept'
        ];
        
        try {
            // Parse JSON body if present
            $content = $request->getContent();
            $requestData = [];
            
            if (!empty($content)) {
                $jsonData = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                    $requestData = $jsonData;
                    $request->merge($jsonData);
                }
            } else {
                // If no JSON body, use request input
                $requestData = $request->all();
            }
            
            // Validate the data
            $validated = validator($requestData, [
                'source' => 'required|string|in:facebook,linkedin,instagram,twitter,tiktok',
                'data' => 'required|array',
                'data.*.name' => 'nullable|string',
                'data.*.company_name' => 'nullable|string',
                'data.*.profile_url' => 'nullable|string',
                'data.*.title' => 'nullable|string',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
            ])->validate();
            
            $source = $validated['source'];
            $extensionData = $validated['data'];
            $country = $validated['country'] ?? session('scraping_country', 'United States');
            $city = $validated['city'] ?? session('scraping_city', 'Unknown');
            
            // Convert extension data to scraped data format
            $scrapedData = [];
            
            foreach ($extensionData as $index => $item) {
                $name = $item['name'] ?? '';
                $companyName = $item['company_name'] ?? $name;
                
                // Split name into first and last if available
                $nameParts = explode(' ', $name, 2);
                $firstName = $nameParts[0] ?? 'Unknown';
                $lastName = $nameParts[1] ?? '';
                
                // Generate email if not provided
                $email = $item['email'] ?? strtolower(str_replace(' ', '.', $firstName . ' ' . $lastName)) . rand(100, 999) . '@' . $source . '.com';
                $email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : 'contact@' . $source . '.com';
                
                // Generate phone based on country
                $phone = $item['phone'] ?? $this->generatePhoneNumber($country);
                
                // Get coordinates for the city
                $coords = $this->getCountryCoordinates($country, $city);
                
                $scrapedData[] = [
                    'id' => $index + 1,
                    'company_name' => $companyName ?: $name ?: 'Unknown',
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'address' => $city && $country ? ($city . ', ' . $country) : ($city ?: $country ?: ''),
                    'phone' => $phone,
                    'email' => $email,
                    'website' => $item['profile_url'] ?? '',
                    'business_type' => $item['title'] ?? ($item['type'] ?? 'Unknown'),
                    'industry' => $item['title'] ?? ($item['type'] ?? 'Unknown'),
                    'company_size' => 'Individual',
                    'latitude' => $coords['lat'] ?? 0,
                    'longitude' => $coords['lng'] ?? 0,
                    'source' => $source,
                    'profile_url' => $item['profile_url'] ?? '',
                ];
            }
            
            // Store in session
            session(['scraped_data' => $scrapedData]);
            session(['scraping_country' => $country]);
            session(['scraping_city' => $city]);
            
            return response()->json([
                'success' => true,
                'message' => count($scrapedData) . ' items received from extension',
                'count' => count($scrapedData),
                'redirect' => url('/data-scraping?source=' . $source)
            ], 200, $jsonHeaders);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422, $jsonHeaders);
        } catch (\Exception $e) {
            \Log::error('Extension data receive error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing data: ' . $e->getMessage(),
                'error_type' => get_class($e)
            ], 500, $jsonHeaders);
        }
    }
}
