import {getComponent} from '@symfony/ux-live-component';
import {Controller} from "@hotwired/stimulus";
import ScheduleCalendar from "../class/Checkout/ScheduleCalendar";

import './../styles/planning.scss';

export default class extends Controller {
    async initialize() {
        this.component = await getComponent(this.element);

        this.scheduleCalendar = new ScheduleCalendar(this);
    }
}