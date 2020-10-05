$(document).ready(function () {

    $('.js-showhide-toggler').on("click",function (e) {
        e.preventDefault();
        $(".js-showhide-content").toggle();
    })

});