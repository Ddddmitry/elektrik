$(document).ready(function () {

    $('[data-history-block]').on("click","[data-show-more-history]",function (e) {
        e.preventDefault();
        $(this).closest('[data-history-block]').find(".js-cabinet__points-item").css("display","flex");
        $(this).remove();
    });

});