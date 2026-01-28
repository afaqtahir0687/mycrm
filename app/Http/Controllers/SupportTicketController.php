<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::query()->with(['account', 'contact', 'assignedUser', 'creator']);
        
        if ($request->filled('search')) {
            $query->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => SupportTicket::count(),
            'new' => SupportTicket::where('status', 'new')->count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];
        
        $tickets = $query->paginate(50);
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'accounts' => $accounts,
            'contacts' => $contacts,
            'users' => $users,
        ];
        
        return view('support-tickets.index', compact('tickets', 'summary', 'accounts', 'contacts', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'account_id' => $request->get('account_id'),
            'contact_id' => $request->get('contact_id'),
            'assigned_to' => $request->get('assigned_to'),
        ];
        
        return view('support-tickets.create', compact('accounts', 'contacts', 'users', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:255|unique:support_tickets',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:new,open,in_progress,resolved,closed,cancelled',
            'type' => 'required|in:question,problem,feature_request,complaint',
            'assigned_to' => 'nullable|exists:users,id',
            'sla_hours' => 'nullable|integer|min:0',
        ]);
        
        $validated['created_by'] = auth()->id();
        if (isset($validated['sla_hours'])) {
            $validated['sla_deadline'] = now()->addHours($validated['sla_hours']);
        }
        
        $ticket = SupportTicket::create($validated);
        
        // Auto-create task if ticket is assigned
        if ($ticket->assigned_to) {
            Task::create([
                'subject' => 'Resolve: ' . $ticket->subject,
                'description' => $ticket->description,
                'priority' => $this->mapPriorityToTask($ticket->priority),
                'status' => 'not_started',
                'assigned_to' => $ticket->assigned_to,
                'created_by' => auth()->id(),
                'support_ticket_id' => $ticket->id,
                'due_date' => $ticket->sla_deadline ? $ticket->sla_deadline->format('Y-m-d') : null,
            ]);
        }
        
        return redirect()->route('support-tickets.index')->with('success', 'Support ticket created successfully.');
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load(['account', 'contact', 'assignedUser', 'creator', 'tasks']);
        return view('support-tickets.show', compact('supportTicket'));
    }

    public function edit(SupportTicket $supportTicket)
    {
        $accounts = Account::all();
        $contacts = Contact::all();
        $users = User::where('is_active', true)->get();
        return view('support-tickets.edit', compact('supportTicket', 'accounts', 'contacts', 'users'));
    }

    public function update(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:255|unique:support_tickets,ticket_number,' . $supportTicket->id,
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:new,open,in_progress,resolved,closed,cancelled',
            'type' => 'required|in:question,problem,feature_request,complaint',
            'assigned_to' => 'nullable|exists:users,id',
            'sla_hours' => 'nullable|integer|min:0',
        ]);
        
        if (isset($validated['sla_hours'])) {
            $validated['sla_deadline'] = now()->addHours($validated['sla_hours']);
        }
        if ($validated['status'] == 'resolved') {
            $validated['resolved_at'] = now();
        }
        if ($validated['status'] == 'closed') {
            $validated['closed_at'] = now();
        }
        
        // Check if assignment changed - create task if newly assigned
        $wasAssigned = $supportTicket->assigned_to;
        $isNowAssigned = $validated['assigned_to'] ?? null;
        
        $supportTicket->update($validated);
        
        // Create task if ticket is newly assigned and no task exists
        if ($isNowAssigned && (!$wasAssigned || $wasAssigned != $isNowAssigned)) {
            $existingTask = Task::where('support_ticket_id', $supportTicket->id)->first();
            if (!$existingTask) {
                Task::create([
                    'subject' => 'Resolve: ' . $supportTicket->subject,
                    'description' => $supportTicket->description,
                    'priority' => $this->mapPriorityToTask($supportTicket->priority),
                    'status' => 'not_started',
                    'assigned_to' => $isNowAssigned,
                    'created_by' => auth()->id(),
                    'support_ticket_id' => $supportTicket->id,
                    'due_date' => $supportTicket->sla_deadline ? $supportTicket->sla_deadline->format('Y-m-d') : null,
                ]);
            }
        }
        
        return redirect()->route('support-tickets.index')->with('success', 'Support ticket updated successfully.');
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();
        return redirect()->route('support-tickets.index')->with('success', 'Support ticket deleted successfully.');
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
    
    public function resolution(Request $request)
    {
        // Show completed tasks (resolutions) for support tickets
        $query = Task::whereNotNull('support_ticket_id')
            ->where('status', 'completed')
            ->with(['supportTicket', 'assignedUser', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supportTicket', function($q) use ($request) {
                      $q->where('ticket_number', 'like', '%' . $request->search . '%')
                        ->orWhere('subject', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        $sortBy = $request->get('sort_by', 'completed_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Task::whereNotNull('support_ticket_id')->where('status', 'completed')->count(),
            'today' => Task::whereNotNull('support_ticket_id')
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'this_week' => Task::whereNotNull('support_ticket_id')
                ->where('status', 'completed')
                ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];
        
        $tasks = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        return view('support-tickets.resolution', compact('tasks', 'summary', 'users'));
    }
    
    private function mapPriorityToTask($ticketPriority)
    {
        $mapping = [
            'low' => 'low',
            'medium' => 'normal',
            'high' => 'high',
            'urgent' => 'urgent',
        ];
        return $mapping[$ticketPriority] ?? 'normal';
    }
}
