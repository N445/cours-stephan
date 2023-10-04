import 'noty/src/noty.scss';
import 'noty/src/themes/metroui.scss';
import Noty from 'noty';

export default class CustomNotification {
    constructor(params, callback) {
        let options = {
            theme  : 'metroui',
            type   : params.type || 'success',
            text   : params.text || params.message || 'Notification',
            layout : 'topLeft',
            timeout: params.timeout || 3000,
            queue  : "notification",
        };

        if ((callback !== undefined || true) && typeof callback === 'function') {
            options['callbacks'] = {
                afterClose: callback,
            };
        }

        new Noty(options).show();
    }
}