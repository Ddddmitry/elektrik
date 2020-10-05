$(document).ready(function () {

    $('[data-reg-checkbox]').on("change",function(){
        $("[data-register-field]").toggle();
    });

    $('[data-to-time]').on('change',function () {
        if($(this).val() == "20"){
            $('[data-to-time-block]').removeClass("disabled");
        }else {
            $('[data-to-time-block]').addClass("disabled");
        }
    });

    $('[data-anytime]').on("change",function (){
        if($(this).is(':checked')){
            $('[data-time-recall]').addClass("disabled");
        }else{
            $('[data-time-recall]').removeClass("disabled");
        }
    });

});