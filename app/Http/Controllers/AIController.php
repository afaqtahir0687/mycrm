<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Deal;
use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    /**
     * AI Lead Qualification Index Page
     */
    public function leadQualification(Request $request)
    {
        $query = Lead::query()->with(['assignedUser', 'creator']);
        
        // Filter by AI score if requested
        if ($request->filled('min_score')) {
            $query->where('ai_score', '>=', $request->min_score);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort by AI score by default
        $sortBy = $request->get('sort_by', 'ai_score');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate(50);
        
        $summary = [
            'total' => Lead::count(),
            'scored' => Lead::whereNotNull('ai_score')->count(),
            'high_score' => Lead::where('ai_score', '>=', 70)->count(),
            'medium_score' => Lead::whereBetween('ai_score', [40, 69])->count(),
            'low_score' => Lead::where('ai_score', '<', 40)->orWhereNull('ai_score')->count(),
        ];
        
        return view('ai.lead-qualification', compact('leads', 'summary'));
    }
    
    /**
     * AI-powered lead scoring
     */
    public function scoreLead(Request $request, Lead $lead)
    {
        // AI scoring algorithm based on multiple factors
        $score = 0;
        $factors = [];
        
        // Factor 1: Email domain quality (5 points)
        if ($lead->email) {
            $domain = substr(strrchr($lead->email, "@"), 1);
            if (in_array($domain, ['gmail.com', 'yahoo.com', 'hotmail.com'])) {
                $score += 2;
                $factors[] = 'Personal email domain: +2';
            } elseif (preg_match('/\.(com|org|net|edu|gov)$/i', $domain)) {
                $score += 5;
                $factors[] = 'Professional domain: +5';
            }
        }
        
        // Factor 2: Company name (10 points)
        if ($lead->company_name) {
            $score += 10;
            $factors[] = 'Company name provided: +10';
        }
        
        // Factor 3: Phone number (5 points)
        if ($lead->phone || $lead->mobile) {
            $score += 5;
            $factors[] = 'Contact number provided: +5';
        }
        
        // Factor 4: Website (5 points)
        if ($lead->website) {
            $score += 5;
            $factors[] = 'Website provided: +5';
        }
        
        // Factor 5: Lead source quality (10 points)
        if (in_array($lead->lead_source, ['Website', 'Referral', 'Partner', 'website', 'referral', 'partner'])) {
            $score += 10;
            $factors[] = 'High-quality source: +10';
        } elseif ($lead->lead_source) {
            $score += 5;
            $factors[] = 'Lead source provided: +5';
        }
        
        // Factor 7: Interaction history (up to 20 points)
        $interactions = Communication::where('lead_id', $lead->id)->count();
        $interactionScore = min($interactions * 5, 20);
        $score += $interactionScore;
        if ($interactionScore > 0) {
            $factors[] = "Interaction history: +{$interactionScore}";
        }
        
        // Factor 8: Status progression (15 points)
        if ($lead->status == 'qualified' || $lead->status == 'Qualified') {
            $score += 15;
            $factors[] = 'Qualified lead: +15';
        } elseif ($lead->status == 'contacted' || $lead->status == 'Contacted') {
            $score += 10;
            $factors[] = 'Contacted lead: +10';
        }
        
        // Cap at 100
        $score = min($score, 100);
        
        // Generate AI insights
        $insights = $this->generateInsights($lead, $score, $factors);
        
        // Generate recommendations
        $recommendations = $this->generateRecommendations($lead, $score);
        
        // Analyze sentiment from communications
        $sentiment = $this->analyzeSentiment($lead);
        
        // Update lead
        $lead->update([
            'ai_score' => $score,
            'ai_insights' => $insights,
            'ai_recommendations' => $recommendations,
            'sentiment' => $sentiment,
        ]);
        
        return response()->json([
            'score' => $score,
            'factors' => $factors,
            'insights' => $insights,
            'recommendations' => $recommendations,
            'sentiment' => $sentiment,
        ]);
    }
    
    /**
     * Generate AI insights for a lead
     */
    private function generateInsights(Lead $lead, int $score, array $factors): string
    {
        $insights = [];
        
        if ($score >= 70) {
            $insights[] = "High-value lead with strong conversion potential. This lead shows multiple positive indicators.";
        } elseif ($score >= 50) {
            $insights[] = "Medium-value lead with moderate conversion potential. Additional qualification may be needed.";
        } else {
            $insights[] = "Low-value lead. Focus on qualification before dedicating significant resources.";
        }
        
        if (!$lead->company_name) {
            $insights[] = "Missing company information may limit qualification accuracy.";
        }
        
        if (!$lead->phone && !$lead->mobile) {
            $insights[] = "No phone number available. Consider requesting contact information.";
        }
        
        return implode(' ', $insights);
    }
    
    /**
     * Generate AI recommendations
     */
    private function generateRecommendations(Lead $lead, int $score): array
    {
        $recommendations = [];
        
        if ($score >= 70) {
            $recommendations[] = "Prioritize this lead for immediate follow-up";
            $recommendations[] = "Schedule a personalized demo or consultation";
            $recommendations[] = "Engage sales team for personalized outreach";
        } elseif ($score >= 50) {
            $recommendations[] = "Send qualification email with key questions";
            $recommendations[] = "Request additional company information";
            $recommendations[] = "Add to nurturing email sequence";
        } else {
            $recommendations[] = "Add to automated email nurturing campaign";
            $recommendations[] = "Monitor for engagement signals";
            $recommendations[] = "Request additional qualification information";
        }
        
        if (!$lead->phone && !$lead->mobile) {
            $recommendations[] = "Request phone number for direct contact";
        }
        
        if ($lead->status == 'new' || $lead->status == 'New') {
            $recommendations[] = "Initiate first contact within 24 hours";
        }
        
        return $recommendations;
    }
    
    /**
     * Analyze sentiment from communications
     */
    private function analyzeSentiment(Lead $lead): string
    {
        $communications = Communication::where('lead_id', $lead->id)
            ->whereNotNull('content')
            ->get();
        
        if ($communications->isEmpty()) {
            return 'neutral';
        }
        
        $positiveWords = ['great', 'excellent', 'interested', 'good', 'thanks', 'yes', 'please', 'excited'];
        $negativeWords = ['no', 'not interested', 'unsubscribe', 'stop', 'cancel', 'bad', 'poor'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($communications as $comm) {
            $content = strtolower($comm->content);
            foreach ($positiveWords as $word) {
                if (strpos($content, $word) !== false) {
                    $positiveCount++;
                }
            }
            foreach ($negativeWords as $word) {
                if (strpos($content, $word) !== false) {
                    $negativeCount++;
                }
            }
        }
        
        if ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount) {
            return 'negative';
        }
        
        return 'neutral';
    }
    
    /**
     * AI email suggestion generator
     */
    public function suggestEmail(Request $request)
    {
        $request->validate([
            'context' => 'required|string',
            'type' => 'required|in:welcome,followup,proposal,reminder',
            'lead_id' => 'nullable|exists:leads,id',
        ]);
        
        $lead = $request->lead_id ? Lead::find($request->lead_id) : null;
        $context = $request->context;
        $type = $request->type;
        
        $suggestions = $this->generateEmailSuggestions($type, $context, $lead);
        
        return response()->json([
            'suggestions' => $suggestions,
            'recommended' => $suggestions[0] ?? null,
        ]);
    }
    
    /**
     * Generate email suggestions based on type and context
     */
    private function generateEmailSuggestions(string $type, string $context, ?Lead $lead): array
    {
        $suggestions = [];
        
        $firstName = $lead ? $lead->first_name : '[Name]';
        $company = $lead ? $lead->company_name : '[Company]';
        
        switch ($type) {
            case 'welcome':
                $suggestions[] = [
                    'subject' => "Welcome to " . ($company ?: 'our platform'),
                    'body' => "Hi {$firstName},\n\nThank you for your interest in our services. We're excited to learn more about how we can help {$company} achieve its goals.\n\nI'd love to schedule a brief call to discuss your specific needs. Would you be available for a 15-minute conversation this week?\n\nBest regards",
                ];
                break;
                
            case 'followup':
                $suggestions[] = [
                    'subject' => "Following up on our conversation",
                    'body' => "Hi {$firstName},\n\nI wanted to follow up regarding {$context}.\n\nBased on our previous discussion, I believe our solution would be a great fit for {$company}. I've prepared a brief overview that addresses your specific requirements.\n\nWould you be interested in reviewing it?\n\nBest regards",
                ];
                break;
                
            case 'proposal':
                $suggestions[] = [
                    'subject' => "Proposal for {$company}",
                    'body' => "Dear {$firstName},\n\nThank you for considering our proposal. I've attached a detailed proposal that outlines how we can help {$company} with {$context}.\n\nThe proposal includes:\n- Overview of our solution\n- Implementation timeline\n- Investment details\n- Next steps\n\nI'm available to discuss any questions you may have.\n\nBest regards",
                ];
                break;
                
            case 'reminder':
                $suggestions[] = [
                    'subject' => "Reminder: {$context}",
                    'body' => "Hi {$firstName},\n\nThis is a friendly reminder about {$context}.\n\nI wanted to ensure we're still on track and address any questions you might have.\n\nPlease let me know if you need any clarification.\n\nBest regards",
                ];
                break;
        }
        
        return $suggestions;
    }
    
    /**
     * AI-powered sales forecasting
     */
    public function forecastSales(Request $request)
    {
        $timeframe = $request->input('timeframe', '30_days');
        
        $forecast = [
            'next_30_days' => Deal::where('status', 'open')
                ->where('expected_close_date', '<=', now()->addDays(30))
                ->sum('amount'),
            'next_60_days' => Deal::where('status', 'open')
                ->where('expected_close_date', '<=', now()->addDays(60))
                ->sum('amount'),
            'next_90_days' => Deal::where('status', 'open')
                ->where('expected_close_date', '<=', now()->addDays(90))
                ->sum('amount'),
            'weighted_forecast' => $this->calculateWeightedForecast(),
            'conversion_probability' => $this->calculateConversionProbability(),
            'deals_by_stage' => Deal::select('stage', \DB::raw('count(*) as count'), \DB::raw('sum(amount) as total'))
                ->where('status', 'open')
                ->groupBy('stage')
                ->get(),
        ];
        
        // If request expects JSON (AJAX), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($forecast);
        }
        
        // Otherwise return view
        return view('ai.forecast-sales', compact('forecast', 'timeframe'));
    }
    
    private function calculateWeightedForecast(): float
    {
        $deals = Deal::where('stage', '!=', 'Closed Won')
            ->where('stage', '!=', 'Closed Lost')
            ->get();
        $weightedTotal = 0;
        
        foreach ($deals as $deal) {
            // Estimate probability based on stage
            $probability = match($deal->stage) {
                'Prospecting' => 10,
                'Qualification' => 25,
                'Proposal' => 50,
                'Negotiation' => 75,
                default => 50,
            };
            $weightedTotal += ($deal->amount ?? 0) * ($probability / 100);
        }
        
        return $weightedTotal;
    }
    
    private function calculateConversionProbability(): array
    {
        $totalDeals = Deal::count();
        $wonDeals = Deal::where('status', 'won')->count();
        $lostDeals = Deal::where('status', 'lost')->count();
        
        return [
            'win_rate' => $totalDeals > 0 ? ($wonDeals / $totalDeals) * 100 : 0,
            'loss_rate' => $totalDeals > 0 ? ($lostDeals / $totalDeals) * 100 : 0,
            'total_deals' => $totalDeals,
        ];
    }
}
