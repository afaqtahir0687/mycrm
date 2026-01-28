@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Calendar</h1>
        <div class="form-actions">
            <a href="{{ route('help.show', ['form' => 'calendar.index']) }}" target="_blank" class="btn btn-secondary" title="Help" style="background: #757575; color: white; padding: 8px 12px; font-size: 16px; font-weight: bold;">?</a>
            <a href="{{ route('calendar.create') }}" class="btn btn-primary">Create Event</a>
        </div>
    </div>
    
    <div id="calendar" style="margin-top: 20px;"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route('calendar.events') }}',
        eventClick: function(info) {
            window.location.href = '{{ url('calendar') }}/' + info.event.id;
        },
        dateClick: function(info) {
            window.location.href = '{{ route('calendar.create') }}?date=' + info.dateStr;
        }
    });
    calendar.render();
});
</script>
@endsection

