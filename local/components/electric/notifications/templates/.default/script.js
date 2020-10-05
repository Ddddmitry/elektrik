$(document).ready(function () {

    $('.js-notify-block').on("click","[data-notify-bell]",function(e){
        e.preventDefault();
        $("[data-notifies]").toggleClass("active");
        if ($(this).hasClass('new')) {
            $.post( "/api/notifications.update.viewed/", {});
            $(this).removeClass('new');
        }
        $(this).find('[data-notification-number]').remove();

    });


});