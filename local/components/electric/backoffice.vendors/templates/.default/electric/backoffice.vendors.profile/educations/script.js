$(document).ready(function () {

    $('.js-cabinet__education-action').on("click","[data-back-from-archive]",function (e) {
        e.preventDefault();
        let educationID = $(this).data("education-id");
        let archive = $(this).data("back-from-archive");
        let $this = $(this);
        $.ajax({
            url: '/api/backoffice.education.update.archive/',
            data: {
                educationID: educationID,
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
                $('[data-education-'+educationID+']').toggleClass("js-is-active");
                $('[data-education-'+educationID+']').toggleClass("cabinet__education_is_archive");
                $('[data-education-'+educationID+']').toggleClass("js-is-archive");
                $(".cabinet__sorting-item.is-active").trigger("click");
            }
        });
    });

});
