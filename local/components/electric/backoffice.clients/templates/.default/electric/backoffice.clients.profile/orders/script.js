$(document).ready(function () {

    $(".js-cabinet__sorting-item").on("click","[data-sort]",function () {
        let type = $(this).data("sort");
        $("[data-all]").hide();
        $("[data-"+type+"]").show();
        console.log(type);
    });


});