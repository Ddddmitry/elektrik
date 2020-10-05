$(document).ready(function () {

    let $formArticlesAdd = $('[data-back-form-articles-add]');
    if ($formArticlesAdd.length) {
        $formArticlesAdd.on('submit', (e) => {
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
                            let text = "Спасибо за размещение статьи!<br>\n" +
                            "                        Статья появится на сайте после того, как пройдёт модерацию.";
                            $currentOrderForm.find('[data-form-content]').html(text);
                            $currentOrderForm.find('[data-form-actions]').remove();

                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }

    let quill = new Quill('#editor', {
        theme: 'snow',
        placeholder:"Текст статьи",
        modules:{toolbar:[["bold","italic","underline"],["blockquote"],[{list:"ordered"},{list:"bullet"}],[{header:[2,3,!1]}],[{color:[]}],[{align:[]}],["link","image"],["clean"]]}
    });

    quill.on('text-change', function(delta, oldDelta, source) {
        $formArticlesAdd.find("textarea").html($("#editor .ql-editor").html());
    });
});