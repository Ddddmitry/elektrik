$(document).ready(function () {

    $("[data-get-poffer]").on("click",function (e) {
        e.preventDefault();
        let button = $(this);
        let arData = {};
        arData.pofferId = $(this).data("poffer-id");
        arData.name = $(this).data("contractor-name");
        arData.phone = $(this).data("contractor-phone");
        arData.email = $(this).data("contractor-email");

        $.ajax({
            url: '/api/poffers.order.add/',
            data: arData,
            type: 'POST',
            success: function success(data) {
                if(data.result){
                    button.replaceWith("Ваша заявка прията, с вами свяжутся!");
                }
            }
        });
    });

});