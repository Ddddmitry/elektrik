$(document).ready(function () {

    /**Отправляем или возвращаем из архива мероприятия*/
    $('.js-cabinet__event-action').on("click","[data-back-from-archive]",function (e) {
        e.preventDefault();
        let eventID = $(this).data("event-id");
        let archive = $(this).data("back-from-archive");
        let $this = $(this);
        $.ajax({
            url: '/api/backoffice.event.update.archive/',
            data: {
                eventID: eventID,
                archive: archive
            },
            type: 'POST',
            success: function success(data) {
                if(archive == "Y"){
                    $this.data("back-from-archive","N");
                    $this.text("Вернуть из архива");

                }
                else{
                    $this.data("back-from-archive","Y");
                    $this.text("Архивировать");
                }
                $('[data-event-'+eventID+']').toggleClass("js-is-active");
                $('[data-event-'+eventID+']').toggleClass("cabinet__event_is_archive");
                $('[data-event-'+eventID+']').toggleClass("js-is-archive");
                $(".cabinet__sorting-item.is-active").trigger("click");
            }
        });
    });

});
