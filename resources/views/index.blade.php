@extends('layout.base')
@section('content')
    <h1 class="text-center mt-3 mb-3">Menú semanal
        <i class="fas fa-calendar"></i>
    </h1>
    <div id='calendar'></div>

@endsection
@section('scripts')
    <script src='{{ asset('/js/fullcalendar/dist/index.global.min.js') }}'></script>
    <script src='{{ asset('/js/fullcalendar/packages/core/locales/es.global.js') }}'></script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es', // Added locale option
            });
            calendar.render();
                
        });
    </script>
@endsection
