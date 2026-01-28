<?php

namespace App\Http\Controllers;

use App\Models\AutomationWorkflow;
use App\Models\User;
use Illuminate\Http\Request;

class AutomationWorkflowController extends Controller
{
    public function index(Request $request)
    {
        $query = AutomationWorkflow::query()->with(['creator']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        
        if ($request->filled('trigger_type')) {
            $query->where('trigger_type', $request->trigger_type);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => AutomationWorkflow::count(),
            'active' => AutomationWorkflow::where('is_active', true)->count(),
            'inactive' => AutomationWorkflow::where('is_active', false)->count(),
        ];
        
        $workflows = $query->paginate(50);
        $users = User::where('is_active', true)->orderBy('name')->get();
        
        $filterOptions = [
            'users' => $users,
            'trigger_types' => AutomationWorkflow::select('trigger_type')->distinct()->whereNotNull('trigger_type')->orderBy('trigger_type')->pluck('trigger_type'),
        ];
        
        return view('automation-workflows.index', compact('workflows', 'summary', 'users', 'filterOptions'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('automation-workflows.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|string|max:255',
            'trigger_conditions' => 'nullable|json',
            'actions' => 'nullable|json',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        AutomationWorkflow::create($validated);
        return redirect()->route('automation-workflows.index')->with('success', 'Automation workflow created successfully.');
    }

    public function show(AutomationWorkflow $automationWorkflow)
    {
        $automationWorkflow->load(['creator']);
        return view('automation-workflows.show', compact('automationWorkflow'));
    }

    public function edit(AutomationWorkflow $automationWorkflow)
    {
        $users = User::where('is_active', true)->get();
        return view('automation-workflows.edit', compact('automationWorkflow', 'users'));
    }

    public function update(Request $request, AutomationWorkflow $automationWorkflow)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|string|max:255',
            'trigger_conditions' => 'nullable|json',
            'actions' => 'nullable|json',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $automationWorkflow->update($validated);
        return redirect()->route('automation-workflows.index')->with('success', 'Automation workflow updated successfully.');
    }

    public function destroy(AutomationWorkflow $automationWorkflow)
    {
        $automationWorkflow->delete();
        return redirect()->route('automation-workflows.index')->with('success', 'Automation workflow deleted successfully.');
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
