// Отправка формы добавления комментария
$(document).on('submit', 'form[data-form-comment]', function (e) {
    e.preventDefault();
    var $formAddComment = $(e.target);
    var $submitButton = $(e.target).find('[type=submit]');
    var fields = $formAddComment.serialize();
    var action = $formAddComment.attr('action');
    if (!action) {
        alert('Не указан обработчик запроса');
        return false;
    } else {
        $submitButton.attr("disabled", true);
        $.ajax({
            url: action,
            data: fields,
            type: 'POST',
            success: function success(data) {
                if (data.result) {
                    window.location.reload();
                } else if (data.error) {
                    var errorBlock = $('.js-form-error');
                    errorBlock.html(data.error);
                }
            }
        });
    }
});

// Отправка формы изменения комментария
$(document).on('submit', 'form[data-form-comment-edit]', function (e) {
    e.preventDefault();
    var $formEditComment = $(e.target);
    var fields = $formEditComment.serialize();
    var action = $formEditComment.attr('action');
    if (!action) {
        alert('Не указан обработчик запроса');
        return false;
    } else {
        $.ajax({
            url: action,
            data: fields,
            type: 'POST',
            success: function success(data) {
                if (data.result) {
                    var text = $formEditComment.find('textarea').val();
                    var $comment = $formEditComment.closest('.js-marketDetail-comment-single');
                    $comment.children('.js-marketDetail-reviews-list__text').html(text);
                    $comment.children('.js-marketDetail-reviews-list__text').slideToggle(300);
                    $comment.children('.js-marketDetail-reviews-list__edit-form').slideToggle(300);
                    $comment.children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
                } else if (data.error) {
                    var errorBlock = $('.js-form-error');
                    errorBlock.html(data.error);
                }
            }
        });
    }
});

// Удаление комментария
$(document).on('click', '.js-marketDetail-reviews-list__delete', function (e) {
    e.preventDefault();
    var data = {
        id: $(e.target).data('id')
    };
    $.ajax({
        url: '/api/comments.delete/',
        data: JSON.stringify(data),
        type: 'POST',
        success: function success(data) {
            if (data.result) {
                var $comment = $(e.target).closest('.js-marketDetail-comment-single');
                $comment.slideUp(300);
            }
        }
    });
});