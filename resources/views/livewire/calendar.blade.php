<div>
    <div id='calendar' wire:ignore></div>
    @dump($events)
    <script>
        var event = @json($events);
        document.addEventListener('livewire:load', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: '2018-06-01', // will be parsed as local
                initialView: 'dayGridMonth',
                events: event,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true
            });
            calendar.render();


        });
    </script>
</div>

