$(document).ready(function () {

    $('.js-cabinet__stock-action').on("click","[data-back-from-archive]",function (e) {
        e.preventDefault();
        let saleID = $(this).data("sale-id");
        let archive = $(this).data("back-from-archive");
        let $this = $(this);
        $.ajax({
            url: '/api/backoffice.sale.update.archive/',
            data: {
                saleID: saleID,
                archive: archive
            },
            type: 'POST',
            success: function success(data) {
                //Здесь почему-то не сработало через .attr написано через .data
                if(archive == "Y"){
                    $this.data("back-from-archive","N");
                    $this.text("Вернуть из архива");
                }
                else{
                    $this.data("back-from-archive","Y");
                    $this.text("Архивировать");
                }
                $('[data-sale-'+saleID+']').toggleClass("is-archive");
            }
        });
    });

});
