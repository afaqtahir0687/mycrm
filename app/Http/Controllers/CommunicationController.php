<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunicationController extends Controller
{
    public function index(Request $request)
    {
        // This is for lead-specific communications (new submenu)
        return $this->leadCommunications($request);
    }
    
    public function leadCommunications(Request $request)
    {
        // Show leads assigned to current user (or all for admin)
        $user = auth()->user()->load('role');
        $query = Lead::query();
        
        if (!$user->role || $user->role->slug !== 'admin') {
            $query->where('assigned_to', auth()->id());
        }
        
        $query->whereNotNull('assigned_to')
            ->whereNotNull('assignment_action')
            ->with(['assignedUser', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('assignment_action')) {
            $query->where('assignment_action', $request->assignment_action);
        }
        
        if ($request->filled('assigned_to') && ($user->role && $user->role->slug === 'admin')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        $sortBy = $request->get('sort_by', 'assignment_deadline');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate(50);
        
        // Get communication history for these leads
        $leadIds = $leads->pluck('id');
        $communications = Communication::whereIn('lead_id', $leadIds)
            ->orWhereIn('assigned_lead_id', $leadIds)
            ->with(['lead', 'assignedLead', 'creator', 'template'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($comm) {
                return $comm->lead_id ?? $comm->assigned_lead_id;
            });
        
        $users = User::where('is_active', true)->orderBy('name')->get();
        $templates = EmailTemplate::where('is_active', true)->orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        return view('communications.lead-communications', compact('leads', 'communications', 'users', 'templates'));
    }
    
    public function recordLeadCommunication(Request $request)
    {
        // Record communication with assigned lead
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'communication_category' => 'required|in:email,sms,letter,voice_call,video_call,visit',
            'template_id' => 'nullable|exists:email_templates,id',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'to_email' => 'nullable|email|max:255',
            'to_phone' => 'nullable|string|max:255',
            'duration_minutes' => 'nullable|integer|min:0',
            'visit_report' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'nullable|in:sent,delivered,read,failed,pending',
        ]);
        
        // Verify the lead is assigned to current user (unless admin)
        $lead = Lead::findOrFail($validated['lead_id']);
        $user = auth()->user()->load('role');
        if (!$user->role || $user->role->slug !== 'admin') {
            if ($lead->assigned_to != auth()->id()) {
                return redirect()->back()->with('error', 'You are not authorized to record communication for this lead.');
            }
        }
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('communication-attachments', 'public');
        }
        
        $validated['assigned_lead_id'] = $validated['lead_id'];
        $validated['lead_id'] = $validated['lead_id'];
        $validated['type'] = $validated['communication_category'];
        $validated['direction'] = 'outbound';
        $validated['created_by'] = auth()->id();
        $validated['sent_at'] = now();
        $validated['attachment_path'] = $attachmentPath;
        
        if (!$validated['subject'] && $validated['template_id']) {
            $template = EmailTemplate::find($validated['template_id']);
            if ($template) {
                $validated['subject'] = $template->subject;
            }
        }
        
        Communication::create($validated);
        
        return redirect()->back()->with('success', 'Communication recorded successfully.');
    }
    
    public function myEngagements(Request $request)
    {
        // Show leads assigned to current user
        $query = Lead::where('assigned_to', auth()->id())
            ->whereNotNull('assignment_action')
            ->with(['assignedUser', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('assignment_action')) {
            $query->where('assignment_action', $request->assignment_action);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'completed') {
                $query->whereHas('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')
                      ->where('engagement_status', 'completed');
                });
            } elseif ($request->status == 'pending') {
                $query->whereDoesntHave('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')
                      ->where('engagement_status', 'completed');
                })->where(function($q) {
                    $q->whereNull('assignment_deadline')
                      ->orWhere('assignment_deadline', '>=', now());
                });
            } elseif ($request->status == 'overdue') {
                $query->where('assignment_deadline', '<', now())
                      ->whereDoesntHave('communications', function($q) {
                          $q->whereNotNull('assigned_lead_id')
                            ->where('engagement_status', 'completed');
                      });
            }
        }
        
        $sortBy = $request->get('sort_by', 'assignment_deadline');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate(50);
        
        // Get engagement results for these leads
        $engagementResults = Communication::whereIn('assigned_lead_id', $leads->pluck('id'))
            ->with(['assignedLead', 'creator'])
            ->get()
            ->keyBy('assigned_lead_id');
        
        return view('communications.my-engagements', compact('leads', 'engagementResults'));
    }
    
    public function engagementSummary(Request $request)
    {
        // Admin view: Show all assigned leads with engagement status
        $query = Lead::whereNotNull('assigned_to')
            ->whereNotNull('assignment_action')
            ->with(['assignedUser', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        if ($request->filled('assignment_action')) {
            $query->where('assignment_action', $request->assignment_action);
        }
        
        if ($request->filled('engagement_status')) {
            if ($request->engagement_status == 'completed') {
                $query->whereHas('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')
                      ->where('engagement_status', 'completed');
                });
            } elseif ($request->engagement_status == 'pending') {
                $query->whereDoesntHave('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')
                      ->where('engagement_status', 'completed');
                })->where(function($q) {
                    $q->whereNull('assignment_deadline')
                      ->orWhere('assignment_deadline', '>=', now());
                });
            } elseif ($request->engagement_status == 'overdue') {
                $query->where('assignment_deadline', '<', now())
                      ->whereDoesntHave('communications', function($q) {
                          $q->whereNotNull('assigned_lead_id')
                            ->where('engagement_status', 'completed');
                      });
            }
        }
        
        $sortBy = $request->get('sort_by', 'assignment_deadline');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate(50);
        
        // Get engagement results
        $engagementResults = Communication::whereIn('assigned_lead_id', $leads->pluck('id'))
            ->with(['assignedLead', 'creator', 'assignedLead.assignedUser'])
            ->get()
            ->keyBy('assigned_lead_id');
        
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $summary = [
            'total' => Lead::whereNotNull('assigned_to')->whereNotNull('assignment_action')->count(),
            'completed' => Lead::whereHas('communications', function($q) {
                $q->whereNotNull('assigned_lead_id')->where('engagement_status', 'completed');
            })->count(),
            'pending' => Lead::whereNotNull('assigned_to')
                ->whereNotNull('assignment_action')
                ->whereDoesntHave('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')->where('engagement_status', 'completed');
                })
                ->where(function($q) {
                    $q->whereNull('assignment_deadline')->orWhere('assignment_deadline', '>=', now());
                })->count(),
            'overdue' => Lead::whereNotNull('assigned_to')
                ->whereNotNull('assignment_action')
                ->where('assignment_deadline', '<', now())
                ->whereDoesntHave('communications', function($q) {
                    $q->whereNotNull('assigned_lead_id')->where('engagement_status', 'completed');
                })->count(),
        ];
        
        return view('communications.engagement-summary', compact('leads', 'engagementResults', 'users', 'summary'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $leads = Lead::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'lead_id' => $request->get('lead_id'),
        ];
        
        return view('communications.create', compact('accounts', 'contacts', 'leads', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        // Check if this is an engagement result submission
        if ($request->has('assigned_lead_id')) {
            return $this->storeEngagementResult($request);
        }
        
        $validated = $request->validate([
            'type' => 'required|in:email,phone,sms,whatsapp,meeting,note',
            'subject' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'direction' => 'required|in:inbound,outbound',
            'from_email' => 'nullable|email|max:255',
            'to_email' => 'nullable|email|max:255',
            'from_phone' => 'nullable|string|max:255',
            'to_phone' => 'nullable|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'status' => 'nullable|in:sent,delivered,read,failed,pending',
        ]);
        
        $validated['created_by'] = auth()->id();
        if ($validated['type'] == 'phone' || $validated['type'] == 'meeting') {
            $validated['sent_at'] = now();
        }
        
        Communication::create($validated);
        return redirect()->route('communications.index')->with('success', 'Communication created successfully.');
    }
    
    public function storeEngagementResult(Request $request)
    {
        $validated = $request->validate([
            'assigned_lead_id' => 'required|exists:leads,id',
            'type' => 'required|in:call,sms,email,whatsapp,online_meeting,personal_visit',
            'engagement_outcome' => 'required|string',
            'engagement_date' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:0',
            'subject' => 'nullable|string|max:255',
        ]);
        
        // Verify the lead is assigned to current user (unless admin)
        $lead = Lead::findOrFail($validated['assigned_lead_id']);
        $user = auth()->user()->load('role');
        if (!$user->role || $user->role->slug !== 'admin') {
            if ($lead->assigned_to != auth()->id()) {
                return redirect()->route('communications.index')->with('error', 'You are not authorized to submit engagement for this lead.');
            }
        }
        
        $validated['engagement_status'] = 'completed';
        $validated['direction'] = 'outbound';
        $validated['created_by'] = auth()->id();
        $validated['sent_at'] = now();
        
        Communication::create($validated);
        
        return redirect()->route('communications.index')->with('success', 'Engagement result recorded successfully.');
    }

    public function show(Communication $communication)
    {
        $communication->load(['account', 'contact', 'lead', 'creator']);
        return view('communications.show', compact('communication'));
    }

    public function edit(Communication $communication)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $leads = Lead::all();
        $users = User::where('is_active', true)->get();
        return view('communications.edit', compact('communication', 'accounts', 'contacts', 'leads', 'users'));
    }

    public function update(Request $request, Communication $communication)
    {
        $validated = $request->validate([
            'type' => 'required|in:email,phone,sms,whatsapp,meeting,note',
            'subject' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'direction' => 'required|in:inbound,outbound',
            'from_email' => 'nullable|email|max:255',
            'to_email' => 'nullable|email|max:255',
            'from_phone' => 'nullable|string|max:255',
            'to_phone' => 'nullable|string|max:255',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'status' => 'nullable|in:sent,delivered,read,failed,pending',
        ]);
        
        $communication->update($validated);
        return redirect()->route('communications.index')->with('success', 'Communication updated successfully.');
    }

    public function destroy(Communication $communication)
    {
        $communication->delete();
        return redirect()->route('communications.index')->with('success', 'Communication deleted successfully.');
    }
    
    public function exportExcel()
    {
        return response()->json(['message' => 'Excel export functionality requires maatwebsite/excel package']);
    }
    
    public function exportPdf()
    {
        return response()->json(['message' => 'PDF export functionality requires barryvdh/laravel-dompdf package']);
    }
    
    public function importExcel(Request $request)
    {
        return response()->json(['message' => 'Excel import functionality requires maatwebsite/excel package']);
    }
}
