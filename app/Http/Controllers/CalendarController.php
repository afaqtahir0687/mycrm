<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }
    
    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $events = CalendarEvent::where('user_id', auth()->id())
            ->whereBetween('start_time', [$start, $end])
            ->orWhere(function($query) use ($start, $end) {
                $query->where('user_id', auth()->id())
                      ->whereBetween('end_time', [$start, $end]);
            })
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time->toIso8601String(),
                    'end' => $event->end_time->toIso8601String(),
                    'allDay' => $event->is_all_day,
                    'backgroundColor' => $this->getEventColor($event->event_type),
                    'borderColor' => $this->getEventColor($event->event_type),
                    'extendedProps' => [
                        'type' => $event->event_type,
                        'location' => $event->location,
                        'description' => $event->description,
                    ],
                ];
            });
        
        return response()->json($events);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'event_type' => 'required|in:meeting,call,reminder,task,deadline',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'nullable|boolean',
            'reminder_minutes' => 'nullable|integer|min:0',
        ]);
        
        $validated['user_id'] = auth()->id();
        $validated['is_all_day'] = $request->has('is_all_day');
        
        CalendarEvent::create($validated);
        
        return redirect()->route('calendar.index')->with('success', 'Event created successfully.');
    }
    
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'event_type' => 'required|in:meeting,call,reminder,task,deadline',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'nullable|boolean',
            'reminder_minutes' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_all_day'] = $request->has('is_all_day');
        
        $calendarEvent->update($validated);
        
        return redirect()->route('calendar.index')->with('success', 'Event updated successfully.');
    }
    
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();
        return redirect()->route('calendar.index')->with('success', 'Event deleted successfully.');
    }
    
    private function getEventColor(string $type): string
    {
        $colors = [
            'meeting' => '#1976d2',
            'call' => '#388e3c',
            'reminder' => '#f57c00',
            'task' => '#7b1fa2',
            'deadline' => '#d32f2f',
        ];
        
        return $colors[$type] ?? '#757575';
    }
}
