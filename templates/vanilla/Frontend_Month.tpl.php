<div class="calendar"></div>
<script type='text/javascript'> 
$(document).ready(function() {
    $('.calendar').fullCalendar({
        theme: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: false,
        defaultView: 'month',
        events: '?upcoming=upcoming&limit=100&format=json'
    });
});
</script> 