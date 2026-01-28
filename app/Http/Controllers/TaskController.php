<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // For Help Desk integration: Show tasks linked to support tickets assigned to current user
        $query = Task::whereNotNull('support_ticket_id')
            ->with(['supportTicket', 'assignedUser', 'creator', 'supportTicket.account', 'supportTicket.contact']);
        
        // If not admin, show only tasks assigned to current user
        $user = auth()->user()->load('role');
        if (!$user->role || $user->role->slug !== 'admin') {
            $query->where('assigned_to', auth()->id());
        }
        
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
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        $sortBy = $request->get('sort_by', 'due_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => Task::whereNotNull('support_ticket_id')
                ->when(!$user->role || $user->role->slug !== 'admin', function($q) {
                    $q->where('assigned_to', auth()->id());
                })
                ->count(),
            'not_started' => Task::whereNotNull('support_ticket_id')
                ->where('status', 'not_started')
                ->when(!$user->role || $user->role->slug !== 'admin', function($q) {
                    $q->where('assigned_to', auth()->id());
                })
                ->count(),
            'in_progress' => Task::whereNotNull('support_ticket_id')
                ->where('status', 'in_progress')
                ->when(!$user->role || $user->role->slug !== 'admin', function($q) {
                    $q->where('assigned_to', auth()->id());
                })
                ->count(),
            'completed' => Task::whereNotNull('support_ticket_id')
                ->where('status', 'completed')
                ->when(!$user->role || $user->role->slug !== 'admin', function($q) {
                    $q->where('assigned_to', auth()->id());
                })
                ->count(),
            'overdue' => Task::whereNotNull('support_ticket_id')
                ->where('due_date', '<', now())
                ->where('status', '!=', 'completed')
                ->when(!$user->role || $user->role->slug !== 'admin', function($q) {
                    $q->where('assigned_to', auth()->id());
                })
                ->count(),
        ];
        
        $tasks = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'users' => $users,
        ];
        
        return view('tasks.index', compact('tasks', 'summary', 'users', 'filterOptions'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        // Get all possible related items
        $accounts = Account::orderBy('account_name')->get();
        $contacts = Contact::orderBy('first_name')->get();
        $leads = Lead::orderBy('first_name')->get();
        $deals = Deal::orderBy('deal_name')->get();
        
        // Pre-populate from query parameters
        $preSelected = [
            'assigned_to' => $request->get('assigned_to'),
            'related_type' => $request->get('related_type'),
            'related_id' => $request->get('related_id'),
        ];
        
        return view('tasks.create', compact('users', 'accounts', 'contacts', 'leads', 'deals', 'preSelected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:not_started,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'assigned_to' => 'nullable|exists:users,id',
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|integer',
            'is_reminder' => 'nullable|boolean',
            'reminder_at' => 'nullable|date',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['assignedUser', 'creator', 'related', 'supportTicket']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::where('is_active', true)->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:not_started,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'support_ticket_id' => 'nullable|exists:support_tickets,id',
            'due_time' => 'nullable',
            'assigned_to' => 'nullable|exists:users,id',
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|integer',
            'is_reminder' => 'nullable|boolean',
            'reminder_at' => 'nullable|date',
        ]);
        
        // If task is being marked as completed, set completed_at
        if ($validated['status'] == 'completed' && !$task->completed_at) {
            $validated['completed_at'] = now();
            
            // Update related support ticket status if task is linked
            if ($task->support_ticket_id) {
                $ticket = SupportTicket::find($task->support_ticket_id);
                if ($ticket && $ticket->status != 'resolved' && $ticket->status != 'closed') {
                    $ticket->update([
                        'status' => 'resolved',
                        'resolved_at' => now(),
                    ]);
                }
            }
        }
        
        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
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
