<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()->with(['user', 'subject'])->latest('activity_date');
        
        if ($request->filled('type')) {
            $query->where('activity_type', $request->type);
        }
        
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('activity_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('activity_date', '<=', $request->date_to);
        }
        
        $activities = $query->paginate(50);
        
        $summary = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('activity_date', today())->count(),
            'this_week' => Activity::whereBetween('activity_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Activity::whereMonth('activity_date', now()->month)->count(),
        ];
        
        return view('activities.index', compact('activities', 'summary'));
    }
    
    public function create()
    {
        return view('activities.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_type' => 'required|in:created,updated,deleted,called,emailed,met,note',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
            'activity_date' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);
        
        $validated['user_id'] = auth()->id();
        $validated['metadata'] = [];
        
        Activity::create($validated);
        
        return redirect()->route('activities.index')->with('success', 'Activity logged successfully.');
    }
    
    public function show(Activity $activity)
    {
        $activity->load(['user', 'subject']);
        return view('activities.show', compact('activity'));
    }
    
    /**
     * Get activities for a specific subject (polymorphic)
     */
    public function getSubjectActivities(Request $request)
    {
        $request->validate([
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
        ]);
        
        $activities = Activity::where('subject_type', $request->subject_type)
            ->where('subject_id', $request->subject_id)
            ->with(['user'])
            ->latest('activity_date')
            ->get();
        
        return response()->json($activities);
    }
}
