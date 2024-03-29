import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import {Calendar} from "@fullcalendar/core";
import Module from "./Module";

export default class CheckoutCalendar {
    liveComponent;
    calendar;
    selectedModule;

    constructor(liveComponent) {
        this.liveComponent = liveComponent;

        $('.event-add-to-cart').on('click', (e) => {
            e.preventDefault();
            let target = $(e.currentTarget);
            let moduleId = target.data('module-id');
            let occurenceId = target.data('occurence-id');

            if(target.hasClass('active')){
                target.removeClass('active');
            }else{
                target.addClass('active');
            }

            this.liveComponent.component.action('addModule', {
                moduleId: moduleId,
                occurenceId: occurenceId,
            })

            console.log(target);
            console.log(moduleId);
            console.log(occurenceId);
        })


        // this.calendar = new Calendar(document.getElementById('calendar'), {
        //     plugins: [timeGridPlugin, interactionPlugin],
        //     themeSystem: 'bootstrap5',
        //     editable: true,
        //     droppable: true,
        //     allDaySlot: false,
        //     height: 'auto',
        //     eventDurationEditable: false,
        //     eventOverlap: false,
        //     locale: frLocale,
        //     firstDay: 1,
        //     initialView: 'timeGridWeek',
        //     hiddenDays: [0],
        //     events: $('[data-events]').data('events'),
        //     slotMinTime: '09:00:00',
        //     slotMaxTime: '19:00:00',
        //     validRange: {
        //         start: $('[data-start-at]').data('start-at'),
        //         end: $('[data-end-at]').data('end-at')
        //     },
        //     headerToolbar: {
        //         left: 'prev,next today',
        //         center: 'title',
        //         right: ''
        //     },
        //     eventClick: (info) => {
        //         let extendedProps = info.event._def.extendedProps;
        //         if ('sub-event' === extendedProps['type']) {
        //             return;
        //         }
        //
        //         this.selectedModule = new Module(info.event);
        //
        //         let occurenceId = extendedProps['occurenceId'];
        //
        //         localStorage.setItem("occurenceId", occurenceId);
        //
        //         // this.selectEventByOccurenceId(occurenceId);
        //         this.liveComponent.component.action('addModule', {
        //             moduleId: this.selectedModule.id,
        //             occurenceId: occurenceId,
        //         })
        //     }
        // });

        // this.calendar.render();

        // window.addEventListener('fullcalendar:render', () => console.log('fullcalendar:render'));
        // window.addEventListener('fullcalendar:render', () => {
        //     console.log('fullcalendar:render')
        //     this.calendar.setOption('events', this.liveComponent.component.valueStore.props.events)
        //     this.calendar.render()
        // });
    }


    selectEventByOccurenceId(occurenceId) {
        $('.fc-event.event-clicked').removeClass('event-clicked');
        $(`.${occurenceId}`).addClass('event-clicked');
    }
}