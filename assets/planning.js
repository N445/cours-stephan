/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/planning.scss';
import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import frLocale from '@fullcalendar/core/locales/fr';
import interactionPlugin, {Draggable} from '@fullcalendar/interaction';

let calendar;

document.addEventListener('DOMContentLoaded', function () {
    calendar = new Calendar(document.getElementById('calendar'), {
        plugins: [timeGridPlugin, interactionPlugin],
        editable: true,
        droppable: true,
        allDaySlot: false,
        height: 'auto',
        eventDurationEditable: false,
        eventOverlap: false,
        locale: frLocale,
        firstDay: 1,
        initialView: 'timeGridWeek',
        hiddenDays: [0],
        events: $('[data-events]').data('events'),
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        validRange: {
            start: $('[data-start-at]').data('start-at'),
            end: $('[data-end-at]').data('end-at')
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        // eventReceive: function (info) {
        //     $(info.draggedEl).remove();
        // },
        eventClick: function (info) {
            let extendedProps = info.event._def.extendedProps;
            if ('sub-event' === extendedProps['type']) {
                return;
            }
            let occurenceId = extendedProps['occurenceId'];

            localStorage.setItem("occurenceId", occurenceId);

            selectEventByOccurenceId(occurenceId);
        }
    });

    calendar.render();

    if (null !== localStorage.getItem("occurenceId")) {
        selectEventByOccurenceId(localStorage.getItem("occurenceId"));
    }
});

function selectEventByOccurenceId(occurenceId) {
    $('.fc-event.event-clicked').removeClass('event-clicked');
    $(`.${occurenceId}`).addClass('event-clicked');
}