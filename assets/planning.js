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

document.addEventListener('DOMContentLoaded', function () {
    let calendar = new Calendar(document.getElementById('calendar'), {
        plugins: [timeGridPlugin, interactionPlugin],
        editable: true,
        droppable: true,
        eventDurationEditable: false,
        eventOverlap: false,
        locale: frLocale,
        firstDay: 1,
        initialView: 'timeGridWeek',
        hiddenDays: [ 0 ],
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
        eventReceive: function (info) {
            $(info.draggedEl).remove();
        }
    });

    calendar.render();

    new Draggable(document.getElementById('calendar-draggable'), {
        itemSelector: '.draggable-element'
    });
});
