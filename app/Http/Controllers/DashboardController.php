<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\SupportTicket;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'total_accounts' => Account::count(),
            'total_contacts' => Contact::count(),
            'total_deals' => Deal::count(),
            'total_tickets' => SupportTicket::count(),
            'total_tasks' => Task::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'active_deals' => Deal::where('status', 'open')->count(),
        ];
        
        return view('dashboard.index', compact('stats'));
    }
    
    public function masterFlow()
    {
        return view('master-flow.index');
    }
}
