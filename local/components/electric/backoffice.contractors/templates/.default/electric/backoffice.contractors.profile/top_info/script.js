$(document).ready(function () {



    let $checkBoxStatus = $('[data-checkbox-status]');
    if ($checkBoxStatus.length) {
        $checkBoxStatus.on('change', (e) => {
            //e.preventDefault();
            let $currentItem = $(e.target);
            let formData = new FormData();
            formData.append('status', $currentItem.prop("checked"));
            let action = "/api/backoffice.update.client/";
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    type: 'POST',
                    success: (data) => {

                        if (data.success) {

                        } else if (data.error) {

                        }

                    },
                })
            }
        })
    }

});