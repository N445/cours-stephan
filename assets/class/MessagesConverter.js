import CustomNotification from "./CustomNotification";

export default function convertFlashMessagesToNoty() {
    if($('.flash-message-wrapper').length) {

        $('.flash-message-wrapper div.alert').each(function () {
            let alertDiv = $(this);
            //  success, warning, error, info

            let alertType = 'info';
            if(alertDiv.hasClass('alert-success')) {
                alertType = 'success';
            }
            if(alertDiv.hasClass('alert-warning')) {
                alertType = 'warning';
            }
            if(alertDiv.hasClass('alert-danger') || alertDiv.hasClass('alert-error')) {
                alertType = 'error';
            }

            let notyParams = {
                text: $('.message', alertDiv).html(),
                type: alertType,
            };


            new CustomNotification(notyParams);
            alertDiv.remove();
            $('.flash-message-wrapper').remove();
        });
    }
}
