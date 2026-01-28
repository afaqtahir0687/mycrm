<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Opportunity;
use App\Models\Quotation;
use App\Models\Invoice;
use Illuminate\Http\Request;

class SalesPipelineController extends Controller
{
    public function index()
    {
        $totalDeals = Deal::count();
        $totalOpportunities = Opportunity::count();
        $totalQuotations = Quotation::count();
        $totalInvoices = Invoice::count();
        
        $openDeals = Deal::where('status', 'open')->count();
        $wonDeals = Deal::where('status', 'won')->count();
        $openOpportunities = Opportunity::where('status', 'open')->count();
        $wonOpportunities = Opportunity::where('status', 'won')->count();
        
        $totalDealValue = Deal::where('status', 'open')->sum('amount');
        $totalOpportunityValue = Opportunity::where('status', 'open')->sum('amount');
        
        $sentQuotations = Quotation::where('status', 'sent')->count();
        $acceptedQuotations = Quotation::where('status', 'accepted')->count();
        $totalQuotationValue = Quotation::sum('total_amount');
        
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $totalInvoiceValue = Invoice::sum('total_amount');
        $outstandingInvoices = Invoice::sum('balance');
        
        $summary = [
            'total_deals' => $totalDeals,
            'open_deals' => $openDeals,
            'won_deals' => $wonDeals,
            'total_deal_value' => $totalDealValue,
            'total_opportunities' => $totalOpportunities,
            'open_opportunities' => $openOpportunities,
            'won_opportunities' => $wonOpportunities,
            'total_opportunity_value' => $totalOpportunityValue,
            'total_quotations' => $totalQuotations,
            'sent_quotations' => $sentQuotations,
            'accepted_quotations' => $acceptedQuotations,
            'total_quotation_value' => $totalQuotationValue,
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'overdue_invoices' => $overdueInvoices,
            'total_invoice_value' => $totalInvoiceValue,
            'outstanding_invoices' => $outstandingInvoices,
        ];
        
        return view('sales-pipeline.index', compact('summary'));
    }
}
