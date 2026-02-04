@extends('layouts.app')

@section('title', 'Data Scraping')

@section('content')
<style>
    .scraping-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }
    
    .scraping-pane {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 20px;
        min-height: 600px;
    }
    
    .scraping-pane h3 {
        color: #1976d2;
        border-bottom: 2px solid #1976d2;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    #map {
        width: 100%;
        height: 600px;
        border-radius: 4px;
        z-index: 0;
    }
    
    .custom-marker {
        background: transparent;
        border: none;
    }
    
    .scraped-item {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f9f9f9;
    }
    
    .scraped-item:hover {
        background: #f0f0f0;
        border-color: #1976d2;
    }
    
    .scraped-item h4 {
        color: #1976d2;
        margin-bottom: 10px;
    }
    
    .scraped-item .info-row {
        display: flex;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .scraped-item .info-label {
        font-weight: 600;
        width: 140px;
        color: #666;
    }
    
    .scraped-item .info-value {
        flex: 1;
        color: #333;
    }
    
    @media (max-width: 1200px) {
        .scraping-container {
            grid-template-columns: 1fr;
        }
        #map {
            height: 400px;
        }
    }
</style>

<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Data Scraping Utility</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'data-scraping.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
        </div>
    </div>
    
    <div class="summary-section">
        <p><strong>Note:</strong> This utility scrapes business data from publicly available free sources. All data collection must comply with terms of service and data privacy regulations. Use responsibly and ethically.</p>
        <p><strong>Map:</strong> Interactive map powered by OpenStreetMap - no API key required. Business locations are approximated based on the selected country and city.</p>
        <div style="background: #e3f2fd; padding: 15px; border-radius: 4px; margin-top: 10px; border-left: 4px solid #1976d2;">
            <p style="margin: 0 0 10px 0;"><strong>📥 Chrome Extension for Social Media:</strong></p>
            <ol style="margin: 0; padding-left: 20px; font-size: 13px;">
                <li>Install the Chrome Extension from: <code style="background: white; padding: 2px 6px; border-radius: 3px;">public/chrome-extension</code> folder</li>
                <li>Open Chrome → <code>chrome://extensions/</code> → Enable "Developer mode" → Click "Load unpacked" → Select the extension folder</li>
                <li>Pin the extension to your Chrome toolbar</li>
                <li>Open your social media account (Facebook, LinkedIn, etc.) in a tab</li>
                <li>Navigate to the page with data (Friends list, Group members, Connections, etc.)</li>
                <li>Click the extension icon → Select source → Click "Extract Data" → Click "Send to CRM"</li>
            </ol>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">The extension will extract data from the currently active tab and send it directly to your CRM.</p>
        </div>
    </div>
    
    <div class="filter-section">
        <form method="POST" action="{{ route('data-scraping.scrape') }}" id="scraping-form">
            @csrf
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Source <span style="color: red;">*</span>:</label>
                    <select name="source" id="source-select" class="form-control" required onchange="handleSourceChange()">
                        <option value="">Select Source</option>
                        <option value="maps" {{ old('source', $filters['source'] ?? '') == 'maps' ? 'selected' : '' }}>Maps / Business Directories</option>
                        <option value="fais" {{ old('source', $filters['source'] ?? '') == 'fais' ? 'selected' : '' }}>Fais Digital</option>
                        <option value="facebook" {{ old('source', $filters['source'] ?? '') == 'facebook' ? 'selected' : '' }}>Facebook (Groups/Friends)</option>
                        <option value="linkedin" {{ old('source', $filters['source'] ?? '') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                        <option value="instagram" {{ old('source', $filters['source'] ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="twitter" {{ old('source', $filters['source'] ?? '') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                        <option value="tiktok" {{ old('source', $filters['source'] ?? '') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    </select>
                    <small id="source-help" style="display: none; color: #666; font-size: 12px; margin-top: 5px;"></small>
                </div>
                <div class="filter-item">
                    <label>Country <span style="color: red;">*</span>:</label>
                    <select name="country" class="form-control" required>
                        <option value="">Select Country</option>
                        <optgroup label="Asia">
                            <option value="Pakistan" {{ old('country', $filters['country']) == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                            <option value="India" {{ old('country', $filters['country']) == 'India' ? 'selected' : '' }}>India</option>
                            <option value="China" {{ old('country', $filters['country']) == 'China' ? 'selected' : '' }}>China</option>
                            <option value="Japan" {{ old('country', $filters['country']) == 'Japan' ? 'selected' : '' }}>Japan</option>
                            <option value="South Korea" {{ old('country', $filters['country']) == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                            <option value="Indonesia" {{ old('country', $filters['country']) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="Thailand" {{ old('country', $filters['country']) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                            <option value="Malaysia" {{ old('country', $filters['country']) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                            <option value="Singapore" {{ old('country', $filters['country']) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                            <option value="Bangladesh" {{ old('country', $filters['country']) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                            <option value="Sri Lanka" {{ old('country', $filters['country']) == 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                            <option value="Philippines" {{ old('country', $filters['country']) == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                            <option value="Vietnam" {{ old('country', $filters['country']) == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                        </optgroup>
                        <optgroup label="GCC (Gulf Cooperation Council)">
                            <option value="Saudi Arabia" {{ old('country', $filters['country']) == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="United Arab Emirates" {{ old('country', $filters['country']) == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates (UAE)</option>
                            <option value="Kuwait" {{ old('country', $filters['country']) == 'Kuwait' ? 'selected' : '' }}>Kuwait</option>
                            <option value="Qatar" {{ old('country', $filters['country']) == 'Qatar' ? 'selected' : '' }}>Qatar</option>
                            <option value="Bahrain" {{ old('country', $filters['country']) == 'Bahrain' ? 'selected' : '' }}>Bahrain</option>
                            <option value="Oman" {{ old('country', $filters['country']) == 'Oman' ? 'selected' : '' }}>Oman</option>
                        </optgroup>
                        <optgroup label="Middle East">
                            <option value="Turkey" {{ old('country', $filters['country']) == 'Turkey' ? 'selected' : '' }}>Turkey</option>
                            <option value="Egypt" {{ old('country', $filters['country']) == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                            <option value="Jordan" {{ old('country', $filters['country']) == 'Jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="Lebanon" {{ old('country', $filters['country']) == 'Lebanon' ? 'selected' : '' }}>Lebanon</option>
                            <option value="Iran" {{ old('country', $filters['country']) == 'Iran' ? 'selected' : '' }}>Iran</option>
                            <option value="Iraq" {{ old('country', $filters['country']) == 'Iraq' ? 'selected' : '' }}>Iraq</option>
                        </optgroup>
                        <optgroup label="Europe">
                            <option value="United Kingdom" {{ old('country', $filters['country']) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="Germany" {{ old('country', $filters['country']) == 'Germany' ? 'selected' : '' }}>Germany</option>
                            <option value="France" {{ old('country', $filters['country']) == 'France' ? 'selected' : '' }}>France</option>
                            <option value="Italy" {{ old('country', $filters['country']) == 'Italy' ? 'selected' : '' }}>Italy</option>
                            <option value="Spain" {{ old('country', $filters['country']) == 'Spain' ? 'selected' : '' }}>Spain</option>
                            <option value="Netherlands" {{ old('country', $filters['country']) == 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                            <option value="Belgium" {{ old('country', $filters['country']) == 'Belgium' ? 'selected' : '' }}>Belgium</option>
                            <option value="Switzerland" {{ old('country', $filters['country']) == 'Switzerland' ? 'selected' : '' }}>Switzerland</option>
                            <option value="Austria" {{ old('country', $filters['country']) == 'Austria' ? 'selected' : '' }}>Austria</option>
                            <option value="Sweden" {{ old('country', $filters['country']) == 'Sweden' ? 'selected' : '' }}>Sweden</option>
                            <option value="Norway" {{ old('country', $filters['country']) == 'Norway' ? 'selected' : '' }}>Norway</option>
                            <option value="Denmark" {{ old('country', $filters['country']) == 'Denmark' ? 'selected' : '' }}>Denmark</option>
                            <option value="Poland" {{ old('country', $filters['country']) == 'Poland' ? 'selected' : '' }}>Poland</option>
                            <option value="Russia" {{ old('country', $filters['country']) == 'Russia' ? 'selected' : '' }}>Russia</option>
                        </optgroup>
                        <optgroup label="North America">
                            <option value="United States" {{ old('country', $filters['country']) == 'United States' ? 'selected' : '' }}>United States</option>
                            <option value="Canada" {{ old('country', $filters['country']) == 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Mexico" {{ old('country', $filters['country']) == 'Mexico' ? 'selected' : '' }}>Mexico</option>
                        </optgroup>
                        <optgroup label="South America">
                            <option value="Brazil" {{ old('country', $filters['country']) == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                            <option value="Argentina" {{ old('country', $filters['country']) == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                            <option value="Chile" {{ old('country', $filters['country']) == 'Chile' ? 'selected' : '' }}>Chile</option>
                            <option value="Colombia" {{ old('country', $filters['country']) == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                        </optgroup>
                        <optgroup label="Oceania">
                            <option value="Australia" {{ old('country', $filters['country']) == 'Australia' ? 'selected' : '' }}>Australia</option>
                            <option value="New Zealand" {{ old('country', $filters['country']) == 'New Zealand' ? 'selected' : '' }}>New Zealand</option>
                        </optgroup>
                        <optgroup label="Africa">
                            <option value="South Africa" {{ old('country', $filters['country']) == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                            <option value="Nigeria" {{ old('country', $filters['country']) == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                            <option value="Kenya" {{ old('country', $filters['country']) == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                            <option value="Morocco" {{ old('country', $filters['country']) == 'Morocco' ? 'selected' : '' }}>Morocco</option>
                        </optgroup>
                    </select>
                </div>
                <div class="filter-item">
                    <label>City <span style="color: red;">*</span>:</label>
                    <input type="text" name="city" value="{{ old('city', $filters['city']) }}" class="form-control" placeholder="Enter city name" required>
                </div>
                <div class="filter-item">
                    <label>Business Type / Industry:</label>
                    <select name="business_type" class="form-control">
                        <option value="">All Types</option>
                        <optgroup label="Technology & IT">
                            <option value="Information Technology" {{ old('business_type', $filters['business_type']) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                            <option value="Software Development" {{ old('business_type', $filters['business_type']) == 'Software Development' ? 'selected' : '' }}>Software Development</option>
                            <option value="Telecommunications" {{ old('business_type', $filters['business_type']) == 'Telecommunications' ? 'selected' : '' }}>Telecommunications</option>
                            <option value="E-commerce" {{ old('business_type', $filters['business_type']) == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="Cybersecurity" {{ old('business_type', $filters['business_type']) == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                            <option value="Data Analytics" {{ old('business_type', $filters['business_type']) == 'Data Analytics' ? 'selected' : '' }}>Data Analytics</option>
                            <option value="Cloud Services" {{ old('business_type', $filters['business_type']) == 'Cloud Services' ? 'selected' : '' }}>Cloud Services</option>
                        </optgroup>
                        <optgroup label="Manufacturing & Industrial">
                            <option value="Manufacturing" {{ old('business_type', $filters['business_type']) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="Automotive" {{ old('business_type', $filters['business_type']) == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                            <option value="Textiles" {{ old('business_type', $filters['business_type']) == 'Textiles' ? 'selected' : '' }}>Textiles</option>
                            <option value="Chemicals" {{ old('business_type', $filters['business_type']) == 'Chemicals' ? 'selected' : '' }}>Chemicals</option>
                            <option value="Pharmaceuticals" {{ old('business_type', $filters['business_type']) == 'Pharmaceuticals' ? 'selected' : '' }}>Pharmaceuticals</option>
                            <option value="Electronics" {{ old('business_type', $filters['business_type']) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Food Processing" {{ old('business_type', $filters['business_type']) == 'Food Processing' ? 'selected' : '' }}>Food Processing</option>
                        </optgroup>
                        <optgroup label="Retail & Wholesale">
                            <option value="Retail" {{ old('business_type', $filters['business_type']) == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Wholesale" {{ old('business_type', $filters['business_type']) == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="Supermarkets" {{ old('business_type', $filters['business_type']) == 'Supermarkets' ? 'selected' : '' }}>Supermarkets</option>
                            <option value="Fashion & Apparel" {{ old('business_type', $filters['business_type']) == 'Fashion & Apparel' ? 'selected' : '' }}>Fashion & Apparel</option>
                            <option value="Consumer Goods" {{ old('business_type', $filters['business_type']) == 'Consumer Goods' ? 'selected' : '' }}>Consumer Goods</option>
                        </optgroup>
                        <optgroup label="Healthcare & Medical">
                            <option value="Healthcare" {{ old('business_type', $filters['business_type']) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Hospitals" {{ old('business_type', $filters['business_type']) == 'Hospitals' ? 'selected' : '' }}>Hospitals</option>
                            <option value="Medical Equipment" {{ old('business_type', $filters['business_type']) == 'Medical Equipment' ? 'selected' : '' }}>Medical Equipment</option>
                            <option value="Pharmaceuticals" {{ old('business_type', $filters['business_type']) == 'Pharmaceuticals' ? 'selected' : '' }}>Pharmaceuticals</option>
                            <option value="Dental Services" {{ old('business_type', $filters['business_type']) == 'Dental Services' ? 'selected' : '' }}>Dental Services</option>
                        </optgroup>
                        <optgroup label="Finance & Banking">
                            <option value="Finance" {{ old('business_type', $filters['business_type']) == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Banking" {{ old('business_type', $filters['business_type']) == 'Banking' ? 'selected' : '' }}>Banking</option>
                            <option value="Insurance" {{ old('business_type', $filters['business_type']) == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                            <option value="Investment" {{ old('business_type', $filters['business_type']) == 'Investment' ? 'selected' : '' }}>Investment</option>
                            <option value="Accounting" {{ old('business_type', $filters['business_type']) == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                            <option value="Financial Advisory" {{ old('business_type', $filters['business_type']) == 'Financial Advisory' ? 'selected' : '' }}>Financial Advisory</option>
                        </optgroup>
                        <optgroup label="Education & Training">
                            <option value="Education" {{ old('business_type', $filters['business_type']) == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Schools" {{ old('business_type', $filters['business_type']) == 'Schools' ? 'selected' : '' }}>Schools</option>
                            <option value="Universities" {{ old('business_type', $filters['business_type']) == 'Universities' ? 'selected' : '' }}>Universities</option>
                            <option value="Training Institutes" {{ old('business_type', $filters['business_type']) == 'Training Institutes' ? 'selected' : '' }}>Training Institutes</option>
                            <option value="Online Education" {{ old('business_type', $filters['business_type']) == 'Online Education' ? 'selected' : '' }}>Online Education</option>
                        </optgroup>
                        <optgroup label="Real Estate & Construction">
                            <option value="Real Estate" {{ old('business_type', $filters['business_type']) == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                            <option value="Construction" {{ old('business_type', $filters['business_type']) == 'Construction' ? 'selected' : '' }}>Construction</option>
                            <option value="Architecture" {{ old('business_type', $filters['business_type']) == 'Architecture' ? 'selected' : '' }}>Architecture</option>
                            <option value="Property Development" {{ old('business_type', $filters['business_type']) == 'Property Development' ? 'selected' : '' }}>Property Development</option>
                            <option value="Interior Design" {{ old('business_type', $filters['business_type']) == 'Interior Design' ? 'selected' : '' }}>Interior Design</option>
                        </optgroup>
                        <optgroup label="Marketing & Advertising">
                            <option value="Marketing" {{ old('business_type', $filters['business_type']) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Advertising" {{ old('business_type', $filters['business_type']) == 'Advertising' ? 'selected' : '' }}>Advertising</option>
                            <option value="Public Relations" {{ old('business_type', $filters['business_type']) == 'Public Relations' ? 'selected' : '' }}>Public Relations</option>
                            <option value="Digital Marketing" {{ old('business_type', $filters['business_type']) == 'Digital Marketing' ? 'selected' : '' }}>Digital Marketing</option>
                            <option value="Event Management" {{ old('business_type', $filters['business_type']) == 'Event Management' ? 'selected' : '' }}>Event Management</option>
                        </optgroup>
                        <optgroup label="Food & Hospitality">
                            <option value="Food & Beverage" {{ old('business_type', $filters['business_type']) == 'Food & Beverage' ? 'selected' : '' }}>Food & Beverage</option>
                            <option value="Restaurants" {{ old('business_type', $filters['business_type']) == 'Restaurants' ? 'selected' : '' }}>Restaurants</option>
                            <option value="Hotels" {{ old('business_type', $filters['business_type']) == 'Hotels' ? 'selected' : '' }}>Hotels</option>
                            <option value="Catering" {{ old('business_type', $filters['business_type']) == 'Catering' ? 'selected' : '' }}>Catering</option>
                            <option value="Hospitality" {{ old('business_type', $filters['business_type']) == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                        </optgroup>
                        <optgroup label="Transportation & Logistics">
                            <option value="Transportation" {{ old('business_type', $filters['business_type']) == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                            <option value="Logistics" {{ old('business_type', $filters['business_type']) == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                            <option value="Shipping" {{ old('business_type', $filters['business_type']) == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                            <option value="Courier Services" {{ old('business_type', $filters['business_type']) == 'Courier Services' ? 'selected' : '' }}>Courier Services</option>
                            <option value="Freight Forwarding" {{ old('business_type', $filters['business_type']) == 'Freight Forwarding' ? 'selected' : '' }}>Freight Forwarding</option>
                        </optgroup>
                        <optgroup label="Energy & Utilities">
                            <option value="Energy" {{ old('business_type', $filters['business_type']) == 'Energy' ? 'selected' : '' }}>Energy</option>
                            <option value="Oil & Gas" {{ old('business_type', $filters['business_type']) == 'Oil & Gas' ? 'selected' : '' }}>Oil & Gas</option>
                            <option value="Renewable Energy" {{ old('business_type', $filters['business_type']) == 'Renewable Energy' ? 'selected' : '' }}>Renewable Energy</option>
                            <option value="Utilities" {{ old('business_type', $filters['business_type']) == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="Power Generation" {{ old('business_type', $filters['business_type']) == 'Power Generation' ? 'selected' : '' }}>Power Generation</option>
                        </optgroup>
                        <optgroup label="Professional Services">
                            <option value="Legal Services" {{ old('business_type', $filters['business_type']) == 'Legal Services' ? 'selected' : '' }}>Legal Services</option>
                            <option value="Consulting" {{ old('business_type', $filters['business_type']) == 'Consulting' ? 'selected' : '' }}>Consulting</option>
                            <option value="Human Resources" {{ old('business_type', $filters['business_type']) == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                            <option value="Recruitment" {{ old('business_type', $filters['business_type']) == 'Recruitment' ? 'selected' : '' }}>Recruitment</option>
                            <option value="Management Services" {{ old('business_type', $filters['business_type']) == 'Management Services' ? 'selected' : '' }}>Management Services</option>
                        </optgroup>
                        <optgroup label="Other">
                            <option value="Entertainment" {{ old('business_type', $filters['business_type']) == 'Entertainment' ? 'selected' : '' }}>Entertainment</option>
                            <option value="Media" {{ old('business_type', $filters['business_type']) == 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Sports & Fitness" {{ old('business_type', $filters['business_type']) == 'Sports & Fitness' ? 'selected' : '' }}>Sports & Fitness</option>
                            <option value="Non-Profit" {{ old('business_type', $filters['business_type']) == 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                            <option value="Agriculture" {{ old('business_type', $filters['business_type']) == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                            <option value="Mining" {{ old('business_type', $filters['business_type']) == 'Mining' ? 'selected' : '' }}>Mining</option>
                        </optgroup>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Company Size:</label>
                    <select name="company_size" class="form-control">
                        <option value="">All Sizes</option>
                        <option value="Micro" {{ old('company_size', $filters['company_size']) == 'Micro' ? 'selected' : '' }}>Micro (1-9 employees)</option>
                        <option value="Small" {{ old('company_size', $filters['company_size']) == 'Small' ? 'selected' : '' }}>Small (10-49 employees)</option>
                        <option value="Medium" {{ old('company_size', $filters['company_size']) == 'Medium' ? 'selected' : '' }}>Medium (50-249 employees)</option>
                        <option value="SMC" {{ old('company_size', $filters['company_size']) == 'SMC' ? 'selected' : '' }}>SMC - Small & Medium Company (1-249 employees)</option>
                        <option value="Large" {{ old('company_size', $filters['company_size']) == 'Large' ? 'selected' : '' }}>Large (250-999 employees)</option>
                        <option value="Large Scale" {{ old('company_size', $filters['company_size']) == 'Large Scale' ? 'selected' : '' }}>Large Scale (1000-4999 employees)</option>
                        <option value="Enterprise" {{ old('company_size', $filters['company_size']) == 'Enterprise' ? 'selected' : '' }}>Enterprise (5000+ employees)</option>
                        <option value="Public" {{ old('company_size', $filters['company_size']) == 'Public' ? 'selected' : '' }}>Public Company (Listed on Stock Exchange)</option>
                        <option value="Multinational" {{ old('company_size', $filters['company_size']) == 'Multinational' ? 'selected' : '' }}>Multinational Corporation</option>
                        <option value="Conglomerate" {{ old('company_size', $filters['company_size']) == 'Conglomerate' ? 'selected' : '' }}>Conglomerate</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Start Scraping</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('scraping-form').reset()">Reset</button>
            </div>
        </form>
    </div>
    
    @if(session('success'))
    <div style="background: #c8e6c9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('warning'))
    <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
        <strong>⚠️ Warning:</strong> {{ session('warning') }}
    </div>
    @endif
    
    @if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
        <strong>❌ Error:</strong> {{ session('error') }}
    </div>
    @endif
    
    @if(count($scrapedData) > 0)
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div><strong>Found {{ count($scrapedData) }} businesses</strong></div>
        <form method="POST" action="{{ route('data-scraping.import') }}" id="import-form" style="display: inline;">
            @csrf
            <div id="selected-ids-container"></div>
            <button type="button" onclick="importSelected()" class="btn btn-success">Import Selected to Leads</button>
        </form>
    </div>
    @endif
    
    <div class="scraping-container">
        <!-- Left Pane: Scraped Data -->
        <div class="scraping-pane">
            <h3>Scraped Business Data</h3>
            
            @if(count($scrapedData) > 0)
            <div style="margin-bottom: 15px; padding: 12px 16px; background: var(--ms-gray-20, #f3f2f1); border: 1px solid var(--ms-gray-30, #edebe9); border-radius: 2px; display: flex; align-items: center; justify-content: space-between;">
                <label style="display: flex; align-items: center; cursor: pointer; font-weight: 600; color: var(--ms-blue, #0078d4); margin: 0; font-size: 14px;">
                    <input type="checkbox" id="select-all-checkbox" style="width: 18px; height: 18px; cursor: pointer; margin-right: 10px; accent-color: var(--ms-blue, #0078d4);" onchange="toggleSelectAll(this);">
                    <span>Select All ({{ count($scrapedData) }} items)</span>
                </label>
                <span id="selected-count" style="color: var(--ms-gray-90, #605e5c); font-size: 13px; font-weight: 500;">0 selected</span>
            </div>
            <div style="max-height: 550px; overflow-y: auto;">
                @foreach($scrapedData as $index => $data)
                <div class="scraped-item" data-id="{{ $data['id'] }}" data-lat="{{ $data['latitude'] }}" data-lng="{{ $data['longitude'] }}" onclick="selectItem(this, {{ $data['latitude'] }}, {{ $data['longitude'] }}, '{{ addslashes($data['company_name']) }}')">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <h4 style="margin: 0;">{{ $data['company_name'] }}</h4>
                        <input type="checkbox" class="data-checkbox" value="{{ $data['id'] }}" style="width: 18px; height: 18px; cursor: pointer;" onclick="event.stopPropagation(); updateSelectedIds();" onchange="updateSelectedIds();">
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $data['address'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $data['phone'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $data['email'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Website:</span>
                        <span class="info-value">{{ $data['website'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Industry:</span>
                        <span class="info-value">{{ $data['industry'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Company Size:</span>
                        <span class="info-value">{{ $data['company_size'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: #666;">
                <p>No data scraped yet.</p>
                <p>Please fill in the search criteria above and click "Start Scraping" to begin.</p>
            </div>
            @endif
        </div>
        
        <!-- Right Pane: Map -->
        <div class="scraping-pane">
            <h3>Map Locations</h3>
            <div id="map"></div>
        </div>
    </div>
</div>

<!-- Leaflet.js - OpenStreetMap (Free, no API key required) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let markers = [];
    
    function initMap() {
        @if(count($scrapedData) > 0)
        const scrapedData = @json($scrapedData);
        
        if (scrapedData.length === 0) {
            document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #666;"><p>No data to display on map.</p></div>';
            return;
        }
        
        // Calculate center from scraped data
        let avgLat = 0;
        let avgLng = 0;
        scrapedData.forEach(data => {
            avgLat += parseFloat(data.latitude);
            avgLng += parseFloat(data.longitude);
        });
        avgLat = avgLat / scrapedData.length;
        avgLng = avgLng / scrapedData.length;
        
        // Initialize map centered on average location
        map = L.map('map').setView([avgLat, avgLng], 12);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Create bounds to fit all markers
        const bounds = L.latLngBounds([]);
        
        scrapedData.forEach((data, index) => {
            const lat = parseFloat(data.latitude);
            const lng = parseFloat(data.longitude);
            
            // Create custom icon with number
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="background-color: #1976d2; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${index + 1}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15]
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            
            const popupContent = `
                <div style="padding: 10px; max-width: 300px;">
                    <h4 style="margin: 0 0 10px 0; color: #1976d2; font-size: 16px;">${data.company_name}</h4>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Address:</strong> ${data.address}</p>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Phone:</strong> ${data.phone}</p>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Email:</strong> <a href="mailto:${data.email}">${data.email}</a></p>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Website:</strong> <a href="http://${data.website}" target="_blank">${data.website}</a></p>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Industry:</strong> ${data.industry}</p>
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Size:</strong> ${data.company_size}</p>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            
            marker.on('click', function() {
                // Highlight corresponding item in left pane
                highlightItem(data.id);
            });
            
            marker.data = data;
            markers.push(marker);
            bounds.extend([lat, lng]);
        });
        
        // Fit map to show all markers
        if (markers.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        @else
        // Default map view (when no data)
        const defaultCenter = [40.7128, -74.0060]; // Default to New York
        map = L.map('map').setView(defaultCenter, 2);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        document.getElementById('map').innerHTML += '<div style="position: absolute; top: 10px; left: 10px; background: white; padding: 10px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 1000;"><p style="margin: 0; font-size: 13px; color: #666;">No data to display. Please search for businesses to see them on the map.</p></div>';
        @endif
    }
    
    // Initialize map when page loads
    window.addEventListener('load', function() {
        initMap();
        // Initialize selected count on page load
        updateSelectedIds();
    });
    
    function selectItem(element, lat, lng, name) {
        // Remove previous highlight
        document.querySelectorAll('.scraped-item').forEach(item => {
            item.style.borderColor = '#e0e0e0';
            item.style.background = '#f9f9f9';
        });
        
        // Highlight selected item
        element.style.borderColor = '#1976d2';
        element.style.background = '#e3f2fd';
        
        // Center map on selected location (Leaflet)
        if (map) {
            map.setView([lat, lng], 15);
            
            // Open popup for the corresponding marker
            const marker = markers.find(m => {
                const markerLat = m.getLatLng().lat;
                const markerLng = m.getLatLng().lng;
                return Math.abs(markerLat - lat) < 0.001 && Math.abs(markerLng - lng) < 0.001;
            });
            
            if (marker) {
                marker.openPopup();
            }
        }
    }
    
    function highlightItem(id) {
        document.querySelectorAll('.scraped-item').forEach(item => {
            if (item.dataset.id == id) {
                item.style.borderColor = '#1976d2';
                item.style.background = '#e3f2fd';
            } else {
                item.style.borderColor = '#e0e0e0';
                item.style.background = '#f9f9f9';
            }
        });
    }
    
    function updateSelectedIds() {
        const checkboxes = document.querySelectorAll('.data-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
        const container = document.getElementById('selected-ids-container');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const selectedCountSpan = document.getElementById('selected-count');
        const totalCount = document.querySelectorAll('.data-checkbox').length;
        
        // Clear previous inputs
        container.innerHTML = '';
        
        // Add hidden inputs for each selected ID
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = id;
            container.appendChild(input);
        });
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            if (selectedIds.length === totalCount && totalCount > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedIds.length > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
        
        // Update selected count
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedIds.length + ' selected';
        }
    }
    
    function toggleSelectAll(checkbox) {
        const allCheckboxes = document.querySelectorAll('.data-checkbox');
        const isChecked = checkbox.checked;
        
        allCheckboxes.forEach(cb => {
            cb.checked = isChecked;
        });
        
        updateSelectedIds();
    }
    
    function importSelected() {
        const checkboxes = document.querySelectorAll('.data-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
        
        if (selectedIds.length === 0) {
            alert('Please select at least one business to import.');
            return;
        }
        
        // Update the form inputs
        updateSelectedIds();
        
        if (confirm('Are you sure you want to import ' + selectedIds.length + ' selected business' + (selectedIds.length > 1 ? 'es' : '') + ' to Leads?')) {
            document.getElementById('import-form').submit();
        }
    }
    
    function handleSourceChange() {
        const source = document.getElementById('source-select').value;
        const helpText = document.getElementById('source-help');
        
        const messages = {
            'maps': '✅ Scraping from Maps and Business Directories. Data will be fetched from publicly available business listings. Click "Start Scraping" to begin.',
            'fais': '✅ Fais Digital connected. Data will be fetched from the configured API endpoint.',
            'facebook': '📥 Use Chrome Extension: 1) Install extension from public/chrome-extension folder 2) Open Facebook in a tab 3) Go to Friends/Groups page 4) Click extension icon → Extract → Send to CRM',
            'linkedin': '📥 Use Chrome Extension: 1) Install extension from public/chrome-extension folder 2) Open LinkedIn in a tab 3) Go to Connections/Search page 4) Click extension icon → Extract → Send to CRM',
            'instagram': '📥 Use Chrome Extension: 1) Install extension from public/chrome-extension folder 2) Open Instagram in a tab 3) Go to Followers/Following page 4) Click extension icon → Extract → Send to CRM',
            'twitter': '📥 Use Chrome Extension: 1) Install extension from public/chrome-extension folder 2) Open Twitter in a tab 3) Go to profile/followers page 4) Click extension icon → Extract → Send to CRM',
            'tiktok': '📥 Use Chrome Extension: 1) Install extension from public/chrome-extension folder 2) Open TikTok in a tab 3) Go to followers/following page 4) Click extension icon → Extract → Send to CRM'
        };
        
        if (source && messages[source]) {
            helpText.textContent = messages[source];
            helpText.style.display = 'block';
            
            // Highlight social media sources with warning
            if (source !== 'maps') {
                helpText.style.color = '#d32f2f';
                helpText.style.fontWeight = 'bold';
            } else {
                helpText.style.color = '#666';
                helpText.style.fontWeight = 'normal';
            }
        } else {
            helpText.style.display = 'none';
        }
    }
    
    // Initialize on page load
    window.addEventListener('load', function() {
        handleSourceChange();
    });
</script>
@endsection

