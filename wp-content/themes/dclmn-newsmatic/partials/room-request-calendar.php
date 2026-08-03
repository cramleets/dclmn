<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ical.js/build/ical.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar/index.global.min.js"></script>
<div id="calendar"></div>
<dialog id="event-modal">
  <h2 id="event-title"></h2>
  <p><strong>Start:</strong> <span id="event-start"></span></p>
  <p><strong>End:</strong> <span id="event-end"></span></p>
  <div id="event-description"></div>
  <button type="button" onclick="this.closest('dialog').close()">Close</button>
</dialog>
<script>
  const calendar = new FullCalendar.Calendar(
    document.getElementById('calendar'), {
      initialView: 'dayGridMonth',

      events: {
        url: ajaxurl +'?action=get_room_reservations_ics',
        format: 'ics'
      },

      eventClick(info) {
        info.jsEvent.preventDefault();

        const event = info.event;

        const dateOptions = {
          dateStyle: 'long',
          timeStyle: 'short'
        };

        document.querySelector('#event-title').textContent = event.title;
        document.querySelector('#event-start').textContent = event.start ? event.start.toLocaleString([], dateOptions) : '';
        document.querySelector('#event-end').textContent = event.end ? event.end.toLocaleString([], dateOptions) : '';
        document.querySelector('#event-description').innerHTML = event.extendedProps.description || '';
        document.querySelector('#event-modal').showModal();
      }
    }
  );

  calendar.render();
</script>