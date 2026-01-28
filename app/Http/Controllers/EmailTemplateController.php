<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailTemplate::query()->with(['creator']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        
        if ($request->filled('is_official')) {
            $query->where('is_official', $request->is_official == '1');
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $summary = [
            'total' => EmailTemplate::count(),
            'active' => EmailTemplate::where('is_active', true)->count(),
            'by_type' => EmailTemplate::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];
        
        $templates = $query->paginate(50);
        
        return view('email-templates.index', compact('templates', 'summary'));
    }
    
    public function create()
    {
        return view('email-templates.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:email,sms,letter,call_script,visit_report',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:general,welcome,followup,quotation,invoice,reminder,official_letter',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'is_active' => 'nullable|boolean',
            'is_official' => 'nullable|boolean',
            'variables' => 'nullable|array',
        ]);
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('communication-templates', 'public');
        }
        
        $validated['user_id'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_official'] = $request->has('is_official');
        $validated['file_path'] = $filePath;
        
        // Auto-detect variables in template
        $content = ($validated['subject'] ?? '') . ' ' . $validated['body'];
        preg_match_all('/\{([a-zA-Z_]+)\}/', $content, $matches);
        $validated['variables'] = array_unique($matches[1] ?? []);
        
        EmailTemplate::create($validated);
        return redirect()->route('email-templates.index')->with('success', 'Template created successfully.');
    }
    
    public function show(EmailTemplate $emailTemplate)
    {
        $emailTemplate->load(['creator']);
        return view('email-templates.show', compact('emailTemplate'));
    }
    
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-templates.edit', compact('emailTemplate'));
    }
    
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:email,sms,letter,call_script,visit_report',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:general,welcome,followup,quotation,invoice,reminder,official_letter',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'is_active' => 'nullable|boolean',
            'is_official' => 'nullable|boolean',
            'variables' => 'nullable|array',
        ]);
        
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($emailTemplate->file_path && Storage::disk('public')->exists($emailTemplate->file_path)) {
                Storage::disk('public')->delete($emailTemplate->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('communication-templates', 'public');
        }
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_official'] = $request->has('is_official');
        
        // Auto-detect variables in template
        $content = ($validated['subject'] ?? '') . ' ' . $validated['body'];
        preg_match_all('/\{([a-zA-Z_]+)\}/', $content, $matches);
        $validated['variables'] = array_unique($matches[1] ?? []);
        
        $emailTemplate->update($validated);
        return redirect()->route('email-templates.index')->with('success', 'Template updated successfully.');
    }
    
    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return redirect()->route('email-templates.index')->with('success', 'Email template deleted successfully.');
    }
    
    /**
     * Render template with variables
     */
    public function render(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'variables' => 'required|array',
        ]);
        
        $subject = $emailTemplate->subject ?? '';
        $body = $emailTemplate->body;
        
        foreach ($request->variables as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value ?? '', $subject);
            $body = str_replace('{' . $key . '}', $value ?? '', $body);
        }
        
        return response()->json([
            'subject' => $subject,
            'body' => $body,
        ]);
    }
    
    /**
     * Get templates by category for dropdown
     */
    public function getTemplates(Request $request)
    {
        $category = $request->get('category', 'email');
        $official = $request->get('official', false);
        
        $query = EmailTemplate::where('category', $category)
            ->where('is_active', true);
        
        if ($official) {
            $query->where('is_official', true);
        }
        
        $templates = $query->orderBy('name')->get(['id', 'name', 'subject', 'body', 'variables']);
        
        return response()->json($templates);
    }
}
