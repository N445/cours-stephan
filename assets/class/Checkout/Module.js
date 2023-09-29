export default class Module {
    id;
    dateTime;

    constructor(calendarEvent) {
        this.id = calendarEvent._def.extendedProps.moduleId;
        this.dateTime = calendarEvent._instance.range.start;
    }
}