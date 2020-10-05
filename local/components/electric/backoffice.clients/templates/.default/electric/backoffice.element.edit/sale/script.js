$(document).ready(function () {

    let $formSaleUpdate = $('[data-back-form-sale-update]');
    if ($formSaleUpdate.length) {
        $formSaleUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {

                        if (data.success) {
                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            window.location.href = "/backoffice/vendors/sales/";
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }
});