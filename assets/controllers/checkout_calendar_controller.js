import {getComponent} from '@symfony/ux-live-component';
import {Controller} from "@hotwired/stimulus";
import CheckoutCalendar from "../class/Checkout/CheckoutCalendar";

import './../styles/planning.scss';

export default class extends Controller {
    async initialize() {
        this.component = await getComponent(this.element);

        this.checkoutCalendar = new CheckoutCalendar(this);

        console.log(this);
    }


}