<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\Account;
use App\Models\Deal;
use App\Models\Opportunity;
use App\Models\Task;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        // If no query parameter, show search form
        if (!$request->has('q') || empty($request->q)) {
            return view('search.index');
        }
        
        $request->validate([
            'q' => 'required|string|min:2',
        ]);
        
        $query = $request->q;
        $results = [
            'leads' => $this->searchLeads($query),
            'contacts' => $this->searchContacts($query),
            'accounts' => $this->searchAccounts($query),
            'deals' => $this->searchDeals($query),
            'opportunities' => $this->searchOpportunities($query),
            'tasks' => $this->searchTasks($query),
        ];
        
        $total = array_sum(array_map('count', $results));
        
        return view('search.results', compact('results', 'query', 'total'));
    }
    
    public function quickSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);
        
        $query = $request->q;
        $limit = $request->get('limit', 5);
        
        $results = [
            'leads' => $this->searchLeads($query, $limit),
            'contacts' => $this->searchContacts($query, $limit),
            'accounts' => $this->searchAccounts($query, $limit),
            'deals' => $this->searchDeals($query, $limit),
        ];
        
        return response()->json($results);
    }
    
    private function searchLeads(string $query, int $limit = null)
    {
        $q = Lead::query()
            ->where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('company_name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($lead) {
            return [
                'id' => $lead->id,
                'type' => 'lead',
                'title' => $lead->first_name . ' ' . $lead->last_name,
                'subtitle' => $lead->company_name,
                'url' => route('leads.show', $lead),
            ];
        });
    }
    
    private function searchContacts(string $query, int $limit = null)
    {
        $q = Contact::query()
            ->where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($contact) {
            return [
                'id' => $contact->id,
                'type' => 'contact',
                'title' => $contact->first_name . ' ' . $contact->last_name,
                'subtitle' => $contact->email,
                'url' => route('contacts.show', $contact),
            ];
        });
    }
    
    private function searchAccounts(string $query, int $limit = null)
    {
        $q = Account::query()
            ->where('account_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('industry', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($account) {
                return [
                    'id' => $account->id,
                    'type' => 'account',
                    'title' => $account->account_name,
                    'subtitle' => $account->industry,
                    'url' => route('accounts.show', $account),
                ];
        });
    }
    
    private function searchDeals(string $query, int $limit = null)
    {
        $q = Deal::query()
            ->where('deal_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($deal) {
                return [
                    'id' => $deal->id,
                    'type' => 'deal',
                    'title' => $deal->deal_name,
                    'subtitle' => '$' . number_format($deal->amount, 2),
                    'url' => route('deals.show', $deal),
                ];
        });
    }
    
    private function searchOpportunities(string $query, int $limit = null)
    {
        $q = Opportunity::query()
            ->where('opportunity_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($opportunity) {
                return [
                    'id' => $opportunity->id,
                    'type' => 'opportunity',
                    'title' => $opportunity->opportunity_name,
                    'subtitle' => '$' . number_format($opportunity->amount, 2),
                    'url' => route('opportunities.show', $opportunity),
                ];
        });
    }
    
    private function searchTasks(string $query, int $limit = null)
    {
        $q = Task::query()
            ->where('subject', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");
        
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get()->map(function ($task) {
                return [
                    'id' => $task->id,
                    'type' => 'task',
                    'title' => $task->subject,
                    'subtitle' => ucfirst($task->status),
                    'url' => route('tasks.show', $task),
                ];
        });
    }
}
