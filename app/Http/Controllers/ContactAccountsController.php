<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;

class ContactAccountsController extends Controller
{
    public function index()
    {
        $totalContacts = Contact::count();
        $totalAccounts = Account::count();
        $activeContacts = Contact::where('status', 'active')->count();
        $activeAccounts = Account::where('status', 'active')->count();
        
        $summary = [
            'total_contacts' => $totalContacts,
            'active_contacts' => $activeContacts,
            'inactive_contacts' => $totalContacts - $activeContacts,
            'total_accounts' => $totalAccounts,
            'active_accounts' => $activeAccounts,
            'inactive_accounts' => $totalAccounts - $activeAccounts,
        ];
        
        return view('contact-accounts.index', compact('summary'));
    }
}
