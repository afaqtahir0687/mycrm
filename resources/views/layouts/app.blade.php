<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRM System')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Microsoft Dynamics 365 Theme */
            :root {
                --ms-blue: #0078d4;
                --ms-blue-dark: #005a9e;
                --ms-blue-light: #40a6ff;
                --ms-blue-hover: #106ebe;
                --ms-gray-10: #faf9f8;
                --ms-gray-20: #f3f2f1;
                --ms-gray-30: #edebe9;
                --ms-gray-40: #e1dfdd;
                --ms-gray-50: #d2d0ce;
                --ms-gray-60: #c8c6c4;
                --ms-gray-70: #a19f9d;
                --ms-gray-80: #8a8886;
                --ms-gray-90: #605e5c;
                --ms-gray-100: #484644;
                --ms-gray-110: #323130;
                --ms-gray-120: #201f1e;
                --ms-white: #ffffff;
                --ms-black: #000000;
                --ms-success: #107c10;
                --ms-error: #d13438;
                --ms-warning: #ffaa44;
            }
            
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body { 
                font-family: 'Segoe UI', 'Segoe UI Web (West European)', 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Roboto', 'Helvetica Neue', sans-serif;
                background: var(--ms-gray-10);
                color: var(--ms-gray-120);
                line-height: 1.5;
                font-size: 14px;
            }
            
            /* Header - Dynamics 365 Style */
            .header { 
                background: var(--ms-blue);
                color: white; 
                padding: 0 16px;
                height: 48px;
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .header-title { 
                font-size: 18px; 
                font-weight: 600; 
                color: white;
            }
            
            .header-actions { 
                display: flex; 
                gap: 16px; 
                align-items: center; 
            }
            
            .header-search { 
                display: flex; 
                align-items: center; 
            }
            
            .header-search input { 
                padding: 6px 12px; 
                border: 1px solid rgba(255, 255, 255, 0.3); 
                border-radius: 2px; 
                width: 300px; 
                font-size: 14px;
                background: rgba(255, 255, 255, 0.15);
                color: white;
                transition: all 0.2s ease;
            }
            
            .header-search input::placeholder { 
                color: rgba(255, 255, 255, 0.7); 
            }
            
            .header-search input:focus { 
                outline: none; 
                border-color: rgba(255, 255, 255, 0.6);
                background: rgba(255, 255, 255, 0.25);
            }
            
            .header-search button {
                padding: 6px 12px; 
                background: rgba(255, 255, 255, 0.2); 
                color: white; 
                border: 1px solid rgba(255, 255, 255, 0.3); 
                border-left: none;
                border-radius: 0 2px 2px 0;
                cursor: pointer;
                transition: all 0.2s ease;
                font-size: 14px;
            }
            
            .header-search button:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            
            .header-notifications { 
                position: relative; 
            }
            
            .header-notifications a {
                color: white;
                font-size: 18px;
                text-decoration: none;
                padding: 8px;
                display: flex;
                align-items: center;
                transition: background 0.2s ease;
                border-radius: 2px;
            }
            
            .header-notifications a:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            
            .header-user { 
                display: flex; 
                gap: 12px; 
                align-items: center; 
            }
            
            .header-user span {
                font-weight: 400;
                color: white;
                font-size: 14px;
            }
            
            .header-user .btn {
                padding: 6px 12px;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: white;
                border-radius: 2px;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .header-user .btn:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            
            /* Horizontal Navigation Bar - Dynamics 365 Style */
            .navbar { 
                background: var(--ms-white);
                border-bottom: 1px solid var(--ms-gray-30); 
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                position: sticky; 
                top: 0; 
                z-index: 1000; 
            }
            
            .navbar-container { 
                display: flex; 
                align-items: center; 
                padding: 0;
            }
            
            .nav-menu { 
                display: flex; 
                list-style: none; 
                margin: 0; 
                padding: 0; 
                flex-wrap: wrap; 
            }
            
            .nav-item { 
                position: relative; 
            }
            
            .nav-link { 
                display: flex; 
                align-items: center; 
                padding: 12px 16px; 
                color: var(--ms-gray-100); 
                text-decoration: none; 
                font-size: 14px; 
                font-weight: 400; 
                transition: all 0.15s ease; 
                border-bottom: 2px solid transparent; 
                position: relative;
            }
            
            .nav-link:hover { 
                background: var(--ms-gray-20); 
                color: var(--ms-blue); 
            }
            
            .nav-link.active { 
                color: var(--ms-blue); 
                border-bottom-color: var(--ms-blue);
                background: var(--ms-gray-20);
                font-weight: 600;
            }
            
            .nav-link.has-dropdown::after { 
                content: '▼'; 
                font-size: 8px; 
                margin-left: 6px; 
                color: var(--ms-gray-80);
                transition: transform 0.2s ease;
            }
            
            .nav-item:hover .nav-link.has-dropdown::after {
                color: var(--ms-blue);
            }
            
            /* Dropdown Menu - Dynamics 365 Style */
            .dropdown-menu { 
                position: absolute; 
                top: 100%; 
                left: 0; 
                background: var(--ms-white); 
                min-width: 200px; 
                box-shadow: 0 3.2px 7.2px rgba(0, 0, 0, 0.13), 0 0.6px 1.8px rgba(0, 0, 0, 0.11); 
                border: 1px solid var(--ms-gray-30); 
                border-radius: 2px; 
                opacity: 0; 
                visibility: hidden; 
                transform: translateY(-8px); 
                transition: all 0.15s ease; 
                z-index: 1000; 
                margin-top: 0; 
                list-style: none; 
                padding: 4px 0; 
                display: none;
            }
            
            .nav-item:hover .dropdown-menu { 
                opacity: 1; 
                visibility: visible; 
                transform: translateY(0); 
                display: block !important; 
            }
            
            .dropdown-menu li { 
                margin: 0; 
                padding: 0; 
                list-style: none; 
            }
            
            .dropdown-item { 
                display: block; 
                padding: 8px 16px; 
                color: var(--ms-gray-100); 
                text-decoration: none; 
                font-size: 14px; 
                transition: all 0.15s ease;
            }
            
            .dropdown-item:hover { 
                background: var(--ms-gray-20);
                color: var(--ms-blue);
            }
            
            .dropdown-item.active { 
                background: var(--ms-gray-20);
                color: var(--ms-blue); 
                font-weight: 600;
            }
            
            .dropdown-divider { 
                height: 1px; 
                background: var(--ms-gray-30); 
                margin: 4px 0; 
            }
            
            /* Main Content - Dynamics 365 Style */
            .main-content { 
                padding: 16px; 
                min-height: calc(100vh - 96px);
            }
            
            .content-card { 
                background: var(--ms-white); 
                border: 1px solid var(--ms-gray-30); 
                padding: 24px; 
                border-radius: 2px; 
                box-shadow: 0 1.6px 3.6px rgba(0, 0, 0, 0.13), 0 0.3px 0.9px rgba(0, 0, 0, 0.11);
            }
            
            .form-header { 
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                margin-bottom: 24px; 
                border-bottom: 1px solid var(--ms-gray-30); 
                padding-bottom: 16px; 
            }
            
            .form-title { 
                font-size: 24px; 
                font-weight: 600; 
                color: var(--ms-gray-120);
            }
            
            .form-actions { 
                display: flex; 
                gap: 8px; 
                flex-wrap: wrap; 
            }
            
            /* Buttons - Dynamics 365 Style */
            .btn { 
                padding: 8px 16px; 
                border: 1px solid transparent; 
                border-radius: 2px; 
                cursor: pointer; 
                font-size: 14px; 
                font-weight: 400;
                transition: all 0.15s ease; 
                text-decoration: none; 
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-height: 32px;
            }
            
            .btn-primary { 
                background: var(--ms-blue);
                color: white; 
                border-color: var(--ms-blue);
            }
            
            .btn-primary:hover { 
                background: var(--ms-blue-hover);
                border-color: var(--ms-blue-hover);
            }
            
            .btn-success { 
                background: var(--ms-success);
                color: white; 
                border-color: var(--ms-success);
            }
            
            .btn-success:hover {
                background: #0e6e0e;
                border-color: #0e6e0e;
            }
            
            .btn-danger { 
                background: var(--ms-error);
                color: white; 
                border-color: var(--ms-error);
            }
            
            .btn-danger:hover {
                background: #b02a2f;
                border-color: #b02a2f;
            }
            
            .btn-secondary { 
                background: var(--ms-gray-20);
                color: var(--ms-gray-120);
                border-color: var(--ms-gray-40);
            }
            
            .btn-secondary:hover {
                background: var(--ms-gray-30);
                border-color: var(--ms-gray-50);
            }
            
            /* Summary Section */
            .summary-section { 
                background: var(--ms-gray-20);
                padding: 16px; 
                margin-bottom: 16px; 
                border-radius: 2px;
                border: 1px solid var(--ms-gray-30);
            }
            
            /* Filter Section */
            .filter-section { 
                background: var(--ms-white);
                padding: 16px; 
                margin-bottom: 16px; 
                border: 1px solid var(--ms-gray-30); 
                border-radius: 2px;
            }
            
            .filter-grid { 
                display: grid; 
                grid-template-columns: repeat(4, 1fr); 
                gap: 16px; 
                margin-bottom: 16px; 
            }
            
            .filter-item { 
                display: flex; 
                flex-direction: column; 
            }
            
            .filter-item label { 
                display: block; 
                margin-bottom: 6px; 
                font-weight: 600; 
                color: var(--ms-gray-100); 
                font-size: 12px;
            }
            
            .filter-item .form-control { 
                width: 100%; 
                padding: 6px 12px; 
                border: 1px solid var(--ms-gray-40); 
                border-radius: 2px; 
                font-size: 14px;
                transition: all 0.15s ease;
                min-height: 32px;
            }
            
            .filter-item .form-control:focus {
                outline: none;
                border-color: var(--ms-blue);
                box-shadow: 0 0 0 1px var(--ms-blue);
            }
            
            .filter-actions { 
                display: flex; 
                gap: 8px; 
                justify-content: flex-start; 
            }
            
            @media (max-width: 1200px) {
                .filter-grid { grid-template-columns: repeat(3, 1fr); }
            }
            
            @media (max-width: 768px) {
                .filter-grid { grid-template-columns: repeat(2, 1fr); }
                .main-content { padding: 12px; }
            }
            
            @media (max-width: 480px) {
                .filter-grid { grid-template-columns: 1fr; }
            }
            
            /* Tables - Dynamics 365 Style */
            .table-container { 
                overflow-x: auto; 
                border: 1px solid var(--ms-gray-30);
                border-radius: 2px;
            }
            
            table { 
                width: 100%; 
                border-collapse: collapse; 
                background: var(--ms-white);
            }
            
            table th, table td { 
                padding: 12px 16px; 
                text-align: left; 
                border-bottom: 1px solid var(--ms-gray-30); 
            }
            
            table th { 
                background: var(--ms-gray-20);
                font-weight: 600;
                color: var(--ms-gray-100);
                font-size: 12px;
                text-transform: uppercase;
            }
            
            table tr:hover {
                background: var(--ms-gray-20);
            }
            
            table tr:last-child td {
                border-bottom: none;
            }
            
            /* Pagination - Dynamics 365 Style */
            .pagination { 
                display: flex; 
                justify-content: flex-end; 
                margin-top: 16px; 
                gap: 4px; 
            }
            
            .pagination a, .pagination span { 
                padding: 6px 12px; 
                border: 1px solid var(--ms-gray-40); 
                text-decoration: none; 
                color: var(--ms-gray-100); 
                border-radius: 2px;
                transition: all 0.15s ease;
                min-height: 32px;
                display: inline-flex;
                align-items: center;
            }
            
            .pagination a:hover {
                background: var(--ms-gray-20);
                border-color: var(--ms-gray-50);
            }
            
            .pagination .active { 
                background: var(--ms-blue);
                color: white; 
                border-color: var(--ms-blue);
                font-weight: 600;
            }
            
            /* Help Button - Dynamics 365 Style */
            .help-btn { 
                position: fixed; 
                bottom: 24px; 
                right: 24px; 
                width: 48px; 
                height: 48px; 
                border-radius: 50%; 
                background: var(--ms-blue);
                color: white; 
                border: none; 
                cursor: pointer; 
                font-size: 20px; 
                font-weight: 600;
                box-shadow: 0 3.2px 7.2px rgba(0, 0, 0, 0.13), 0 0.6px 1.8px rgba(0, 0, 0, 0.11); 
                transition: all 0.15s ease; 
                z-index: 999;
            }
            
            .help-btn:hover { 
                background: var(--ms-blue-hover);
                box-shadow: 0 4.8px 10.8px rgba(0, 0, 0, 0.18), 0 0.9px 2.7px rgba(0, 0, 0, 0.13);
            }
            
            /* Dashboard Tiles - Dynamics 365 Style */
            .dashboard-tiles { 
                display: grid; 
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
                gap: 16px; 
                margin-bottom: 24px; 
            }
            
            .dashboard-tile { 
                background: var(--ms-white);
                padding: 20px; 
                border-radius: 2px; 
                border: 1px solid var(--ms-gray-30);
                box-shadow: 0 1.6px 3.6px rgba(0, 0, 0, 0.13), 0 0.3px 0.9px rgba(0, 0, 0, 0.11);
                transition: box-shadow 0.15s ease;
            }
            
            .dashboard-tile:hover {
                box-shadow: 0 3.2px 7.2px rgba(0, 0, 0, 0.13), 0 0.6px 1.8px rgba(0, 0, 0, 0.11);
            }
            
            .dashboard-tile h3 { 
                font-size: 12px; 
                margin-bottom: 8px;
                color: var(--ms-gray-80);
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .dashboard-tile .value { 
                font-size: 32px; 
                font-weight: 600; 
                color: var(--ms-blue);
            }
            
            /* Form Groups - Dynamics 365 Style */
            .form-group { 
                margin-bottom: 20px; 
            }
            
            .form-group label { 
                display: block; 
                margin-bottom: 6px; 
                font-weight: 600; 
                color: var(--ms-gray-100);
                font-size: 12px;
            }
            
            .form-group input, 
            .form-group select, 
            .form-group textarea { 
                width: 100%; 
                padding: 6px 12px; 
                border: 1px solid var(--ms-gray-40); 
                border-radius: 2px; 
                font-size: 14px;
                transition: all 0.15s ease;
                background: var(--ms-white);
                color: var(--ms-gray-120);
                min-height: 32px;
            }
            
            .form-group input:focus, 
            .form-group select:focus, 
            .form-group textarea:focus {
                outline: none;
                border-color: var(--ms-blue);
                box-shadow: 0 0 0 1px var(--ms-blue);
            }
            
            .form-group textarea { 
                resize: vertical; 
                min-height: 80px;
            }
            
            .form-control { 
                padding: 6px 12px; 
                border: 1px solid var(--ms-gray-40); 
                border-radius: 2px; 
                font-size: 14px;
                transition: all 0.15s ease;
                min-height: 32px;
            }
            
            .form-control:focus {
                outline: none;
                border-color: var(--ms-blue);
                box-shadow: 0 0 0 1px var(--ms-blue);
            }
            
            .error { 
                color: var(--ms-error); 
                font-size: 12px; 
                margin-top: 4px;
                font-weight: 400;
            }
            
            /* Mobile Responsive */
            @media (max-width: 768px) {
                .nav-menu { flex-direction: column; }
                .nav-item { width: 100%; }
                .dropdown-menu { 
                    position: static; 
                    opacity: 1; 
                    visibility: visible; 
                    transform: none; 
                    box-shadow: none; 
                    border: none;
                    border-radius: 0;
                }
                .nav-link.has-dropdown::after { content: '▶'; }
                .header-search input {
                    width: 200px;
                }
            }
        </style>
    @endif
</head>
<body>
    <header class="header">
        <div class="header-title">CRM System</div>
        <div class="header-actions">
            @auth
            <div class="header-search">
                <form action="{{ route('search.global') }}" method="GET" style="display: flex; align-items: center;">
                    <input type="text" name="q" placeholder="Quick search..." style="padding: 6px 12px; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 2px 0 0 2px; width: 300px; font-size: 14px; background: rgba(255, 255, 255, 0.15); color: white;">
                    <button type="submit" style="padding: 6px 12px; background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-left: none; border-radius: 0 2px 2px 0; cursor: pointer; font-size: 14px;">🔍</button>
                </form>
            </div>
            <div class="header-notifications">
                <a href="{{ route('notifications.index') }}" style="position: relative; color: white; text-decoration: none; padding: 8px;">
                    🔔
                    <span id="notification-badge" style="position: absolute; top: 0; right: 0; background: #f44336; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: none; align-items: center; justify-content: center;">0</span>
                </a>
            </div>
            @endauth
            <div class="header-user">
                <span>{{ Auth::user()->name ?? 'Guest' }}</span>
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn" style="background: transparent; border: 1px solid rgba(255, 255, 255, 0.3); color: white; padding: 6px 12px; border-radius: 2px; font-size: 14px; cursor: pointer;">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    @auth
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link has-dropdown {{ request()->routeIs('dashboard') || request()->routeIs('master-flow.*') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('dashboard') }}" class="dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Process Flow</a></li>
                        <li><a href="{{ route('master-flow.index') }}" class="dropdown-item {{ request()->routeIs('master-flow.*') ? 'active' : '' }}">Master Flow</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link has-dropdown {{ request()->routeIs('data-scraping.*') || request()->routeIs('leads.*') || request()->routeIs('communications.*') || request()->routeIs('ai.lead-qualification') || request()->routeIs('ai.score-lead') ? 'active' : '' }}">
                        Marketing
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('data-scraping.index') }}" class="dropdown-item {{ request()->routeIs('data-scraping.*') ? 'active' : '' }}">Data Scraping</a></li>
                        <li><a href="{{ route('leads.index') }}" class="dropdown-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">Leads & Assignment</a></li>
                        <li><a href="{{ route('communications.index') }}" class="dropdown-item {{ request()->routeIs('communications.*') && !request()->routeIs('communications.my-engagements') && !request()->routeIs('communications.engagement-summary') ? 'active' : '' }}">Communication</a></li>
                        <li><a href="{{ route('communications.my-engagements') }}" class="dropdown-item {{ request()->routeIs('communications.my-engagements') || request()->routeIs('communications.engagement-summary') || request()->routeIs('engagements.*') ? 'active' : '' }}">Engagement Results</a></li>
                        <li><a href="{{ route('ai.lead-qualification') }}" class="dropdown-item {{ request()->routeIs('ai.lead-qualification') || request()->routeIs('ai.score-lead') ? 'active' : '' }}">AI Lead Qualification</a></li>
                        <li><a href="{{ route('email-templates.index') }}" class="dropdown-item {{ request()->routeIs('email-templates.*') ? 'active' : '' }}">Templates & Messages</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link has-dropdown {{ request()->routeIs('client-registration.*') || request()->routeIs('quotations.*') || request()->routeIs('products.*') || request()->routeIs('services.*') || request()->routeIs('agreements.*') || request()->routeIs('deals.*') ? 'active' : '' }}">
                        Sales
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('client-registration.index') }}" class="dropdown-item {{ request()->routeIs('client-registration.*') ? 'active' : '' }}">Client Registration</a></li>
                        <li><a href="{{ route('quotations.index') }}" class="dropdown-item {{ request()->routeIs('quotations.*') ? 'active' : '' }}">Quotation</a></li>
                        <li><a href="{{ route('products.index') }}" class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a></li>
                        <li><a href="{{ route('services.index') }}" class="dropdown-item {{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a></li>
                        <li><a href="{{ route('agreements.index') }}" class="dropdown-item {{ request()->routeIs('agreements.*') ? 'active' : '' }}">STC/Agreements/SLAs</a></li>
                        <li><a href="{{ route('deals.index') }}" class="dropdown-item {{ request()->routeIs('deals.*') ? 'active' : '' }}">Deals/Agreements</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link has-dropdown {{ request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('expenses.*') ? 'active' : '' }}">
                        Accounts
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('invoices.index') }}" class="dropdown-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">Sales Invoices</a></li>
                        <li><a href="{{ route('payments.index', ['type' => 'received']) }}" class="dropdown-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">Funds Received</a></li>
                        <li><a href="{{ route('payments.index', ['type' => 'made']) }}" class="dropdown-item">Fund Payments</a></li>
                        <li><a href="{{ route('expenses.index') }}" class="dropdown-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">Expenses</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link has-dropdown {{ request()->routeIs('support-tickets.*') || request()->routeIs('tasks.*') ? 'active' : '' }}">
                        Help Desk
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('support-tickets.index') }}" class="dropdown-item {{ request()->routeIs('support-tickets.index') || request()->routeIs('support-tickets.create') || request()->routeIs('support-tickets.show') || request()->routeIs('support-tickets.edit') ? 'active' : '' }}">Ticket Registration</a></li>
                        <li><a href="{{ route('tasks.index') }}" class="dropdown-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">Tasks</a></li>
                        <li><a href="{{ route('support-tickets.resolution') }}" class="dropdown-item {{ request()->routeIs('support-tickets.resolution') ? 'active' : '' }}">Resolution</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        User Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link has-dropdown {{ request()->routeIs('analytics.*') || request()->routeIs('calendar.*') || request()->routeIs('activities.*') || request()->routeIs('email-templates.*') || request()->routeIs('notifications.*') || request()->routeIs('search.*') || request()->routeIs('ai.forecast-sales') ? 'active' : '' }}">
                        Tools & Utilities
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('analytics.index') }}" class="dropdown-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">Analytics Dashboard</a></li>
                        <li><a href="{{ route('calendar.index') }}" class="dropdown-item {{ request()->routeIs('calendar.*') ? 'active' : '' }}">Calendar</a></li>
                        <li><a href="{{ route('activities.index') }}" class="dropdown-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">Activity Feed</a></li>
                        <li><a href="{{ route('email-templates.index') }}" class="dropdown-item {{ request()->routeIs('email-templates.*') ? 'active' : '' }}">Email Templates</a></li>
                        <li><a href="{{ route('notifications.index') }}" class="dropdown-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">Notifications</a></li>
                        <li><a href="{{ route('search.global') }}" class="dropdown-item {{ request()->routeIs('search.*') ? 'active' : '' }}">Global Search</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a href="{{ route('ai.forecast-sales') }}" class="dropdown-item {{ request()->routeIs('ai.forecast-sales') ? 'active' : '' }}">AI Sales Forecast</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    @endauth

    <main class="main-content">
        @yield('content')
    </main>

    @auth
    <button class="help-btn" onclick="window.open('{{ route('help.show', ['form' => request()->route()->getName() ?? 'dashboard']) }}', '_blank', 'width=800,height=600')" title="Help">?</button>
    
    <script>
        // Ensure dropdown menus work on hover (fallback for browsers that need it)
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(function(item) {
                const dropdown = item.querySelector('.dropdown-menu');
                if (dropdown) {
                    item.addEventListener('mouseenter', function() {
                        dropdown.style.display = 'block';
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateY(0)';
                    });
                    item.addEventListener('mouseleave', function() {
                        dropdown.style.display = 'none';
                        dropdown.style.opacity = '0';
                        dropdown.style.visibility = 'hidden';
                        dropdown.style.transform = 'translateY(-10px)';
                    });
                }
            });
        });
        
        // Load notification count on page load
        fetch('{{ route('notifications.unread-count') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                if (badge && data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                } else if (badge) {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error loading notifications:', error));
        
        // Refresh notification count every 30 seconds
        setInterval(function() {
            fetch('{{ route('notifications.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }, 30000);
    </script>
    @endauth

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif
</body>
</html>
