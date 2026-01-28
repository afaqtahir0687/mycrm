<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Deal;
use App\Models\Opportunity;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $analytics = [
            'sales' => $this->getSalesAnalytics(),
            'leads' => $this->getLeadsAnalytics(),
            'accounts' => $this->getAccountsAnalytics(),
            'conversion' => $this->getConversionAnalytics(),
            'revenue' => $this->getRevenueAnalytics(),
            'activities' => $this->getActivityAnalytics(),
        ];
        
        return view('analytics.index', compact('analytics'));
    }
    
    private function getSalesAnalytics(): array
    {
        return [
            'total_deals' => Deal::count(),
            'open_deals' => Deal::where('status', 'open')->count(),
            'won_deals' => Deal::where('status', 'won')->count(),
            'lost_deals' => Deal::where('status', 'lost')->count(),
            'total_value' => Deal::where('status', 'open')->sum('amount'),
            'won_value' => Deal::where('status', 'won')->sum('amount'),
            'win_rate' => Deal::count() > 0 ? (Deal::where('status', 'won')->count() / Deal::count()) * 100 : 0,
            'avg_deal_size' => Deal::avg('amount') ?? 0,
            'pipeline_by_stage' => Deal::select('stage', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                ->where('status', 'open')
                ->groupBy('stage')
                ->get(),
        ];
    }
    
    private function getLeadsAnalytics(): array
    {
        return [
            'total' => Lead::count(),
            'by_status' => Lead::select('status', DB::raw('count(*) as count'))->groupBy('status')->get(),
            'by_source' => Lead::select('lead_source', DB::raw('count(*) as count'))->whereNotNull('lead_source')->groupBy('lead_source')->get(),
            'conversion_rate' => Lead::count() > 0 ? (Lead::where('status', 'converted')->count() / Lead::count()) * 100 : 0,
            'avg_lead_score' => round(Lead::avg('lead_score') ?? 0, 1),
            'top_sources' => Lead::select('lead_source', DB::raw('count(*) as count'))
                ->whereNotNull('lead_source')
                ->groupBy('lead_source')
                ->orderByDesc('count')
                ->limit(5)
                ->get(),
        ];
    }
    
    private function getAccountsAnalytics(): array
    {
        $accountsWithContacts = Account::withCount('contacts')->get();
        $avgContacts = $accountsWithContacts->count() > 0 ? $accountsWithContacts->avg('contacts_count') : 0;
        
        return [
            'total' => Account::count(),
            'active' => Account::where('status', 'active')->count(),
            'by_industry' => Account::select('industry', DB::raw('count(*) as count'))
                ->whereNotNull('industry')
                ->groupBy('industry')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'avg_contacts_per_account' => round($avgContacts, 2),
        ];
    }
    
    private function getConversionAnalytics(): array
    {
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $totalDeals = Deal::count();
        $wonDeals = Deal::where('status', 'won')->count();
        $dealsFromLeads = Deal::whereNotNull('lead_id')->count();
        
        return [
            'lead_to_contact' => $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0,
            'deal_win_rate' => $totalDeals > 0 ? ($wonDeals / $totalDeals) * 100 : 0,
            'lead_to_deal' => $totalLeads > 0 ? ($dealsFromLeads / $totalLeads) * 100 : 0,
        ];
    }
    
    private function getRevenueAnalytics(): array
    {
        $totalInvoiced = Invoice::sum('total_amount') ?? 0;
        $totalPaid = Invoice::where('status', 'paid')->sum('total_amount') ?? 0;
        $outstanding = Invoice::whereIn('status', ['sent', 'partial', 'overdue'])->sum('balance') ?? 0;
        $overdue = Invoice::where('status', 'overdue')->sum('balance') ?? 0;
        
        return [
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'outstanding' => $outstanding,
            'overdue' => $overdue,
            'monthly_revenue' => Invoice::selectRaw('YEAR(invoice_date) as year, MONTH(invoice_date) as month, sum(total_amount) as revenue')
                ->where('status', 'paid')
                ->whereNotNull('invoice_date')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
        ];
    }
    
    private function getActivityAnalytics(): array
    {
        return [
            'activities_today' => \App\Models\Activity::whereDate('activity_date', today())->count(),
            'activities_this_week' => \App\Models\Activity::whereBetween('activity_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'activities_this_month' => \App\Models\Activity::whereMonth('activity_date', now()->month)->count(),
            'by_type' => \App\Models\Activity::select('activity_type', DB::raw('count(*) as count'))
                ->groupBy('activity_type')
                ->get(),
        ];
    }
    
    /**
     * Export analytics as JSON
     */
    public function export()
    {
        $analytics = [
            'sales' => $this->getSalesAnalytics(),
            'leads' => $this->getLeadsAnalytics(),
            'accounts' => $this->getAccountsAnalytics(),
            'conversion' => $this->getConversionAnalytics(),
            'revenue' => $this->getRevenueAnalytics(),
        ];
        
        return response()->json($analytics);
    }
}
