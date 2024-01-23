import {Calendar} from "@fullcalendar/core";
import multiMonthPlugin from "@fullcalendar/multimonth";
import frLocale from "@fullcalendar/core/locales/fr";

export default class ScheduleCalendar {
    liveComponent;
    calendar;

    constructor(liveComponent) {
        console.log(multiMonthPlugin)
        this.liveComponent = liveComponent;
        this.calendar = new Calendar(document.getElementById('calendar'), {
            plugins: [multiMonthPlugin],
            themeSystem: 'bootstrap5',
            editable: false,
            droppable: false,
            height: 'auto',
            eventDurationEditable: false,
            eventOverlap: false,
            displayEventTime: false,
            locale: frLocale,
            firstDay: 1,
            initialView: 'multiMonthYear',
            views: {
                multiMonthFourMonth: {
                    type: 'multiMonth',
                    duration: { months: 4 }
                }
            },
            hiddenDays: [0],
            events: $('[data-events]').data('events'),
            // slotMinTime: '08:00:00',
            // slotMaxTime: '20:00:00',
            // validRange: {
            //     start: $('[data-start-at]').data('start-at'),
            //     end: $('[data-end-at]').data('end-at')
            // },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            // eventClick: function(info) {
            //     info.jsEvent.preventDefault(); // don't let the browser navigate
            //
            //     if (info.event.url) {
            //         document.location.href = info.event.url;
            //         // window.open(info.event.url);
            //     }
            //
            // }
        });

        this.calendar.render();
    }
}