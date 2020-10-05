$(document).ready(function () {
/*
    $(".js-marketDetail-reviews-list__answer-bottom").on("click",".js-marketDetail-reviews-list__answer-toggler",function (e) {
        e.preventDefault();
        $(this).hide();
        $(this).closest(".js-marketDetail-reviews-list__answer-form").show();
    });

    $(".js-marketDetail-reviews-list__answer-form").on("click",".js-marketDetail-reviews-list__answer-cancel",function (e) {
        $(this).closest(".js-marketDetail-reviews-list__answer-form").hide();
        $(".js-marketDetail-reviews-list__answer-bottom").find(".js-marketDetail-reviews-list__answer-toggler").show();
        e.preventDefault();
    });



    let $formReviewAnswer = $('[data-back-review-answer]');
    if ($formReviewAnswer.length) {
        $formReviewAnswer.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let reviewID = $currentOrderForm.find('[name="reviewID"]').val();
            let owner = $currentOrderForm.find('[name="owner"]').val();
            let answerText = $currentOrderForm.find('[name="answerText"]').val();
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

                            let answer = '<div class="feedback__answer"><div class="main__text">'+owner+'</div><div class="main__text">'+answerText+'</div></div>';
                            $('[data-review-'+reviewID+']').append(answer);
                            $(".js-marketDetail-reviews-list__answer-form").remove();
                            $(".js-marketDetail-reviews-list__answer-toggler").remove();
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }
*/

    const reviewsNodes = document.querySelectorAll('[data-review]');
    window.ReviewsModels = [];

    reviewsNodes.forEach(review => {
        const reviewItem = review.dataset.review;
        window.ReviewsModels.push(new Review(reviewItem))
    });


});

function Review(id) {
    this.id = id;
    this.$container = $(`[data-review=${this.id}]`);
    this.actionUrl = "/api/backoffice.reviews.update.answer/";
    this.form = $(`[data-review-form="${this.id}"]`);
    this.author = this.form.find("[name='owner']").val();
    this.answerBlock = this.$container.find('.js-feedback__answer');
    this.answer = "";
    this.btnAnswer = this.$container.find('.js-marketDetail-reviews-list__answer-toggler');
    this.$container.on('click', '.js-marketDetail-reviews-list__answer-toggler', this.handleShowForm.bind(this) );
    this.$container.on('click', '.js-marketDetail-reviews-list__answer-edit', this.handleShowEditForm.bind(this) );
    this.$container.on('click', '.js-marketDetail-reviews-list__answer-cancel', this.handleCloseForm.bind(this) );
    this.$container.on('click', '[data-submit-'+this.id+']', this.handleSubmitForm.bind(this) );

}

Review.prototype = {

    handleShowForm: function () {

        this.$container.find('.js-marketDetail-reviews-list__answer-toggler').hide();
        this.$container.find('.js-marketDetail-reviews-list__answer-form').show();

    },
    handleShowEditForm: function () {

        this.$container.find('.js-marketDetail-reviews-list__answer-edit').hide();
        this.$container.find('.js-marketDetail-reviews-list__answer-form').show();
        this.answerBlock.hide();

    },
    handleCloseForm: function () {
        this.answerBlock.show();
        this.$container.find('.js-marketDetail-reviews-list__answer-edit').show();
        this.$container.find('.js-marketDetail-reviews-list__answer-toggler').show();
        this.$container.find('.js-marketDetail-reviews-list__answer-form').hide();
    },
    handleSubmitForm: function (e){
        e.preventDefault();

        this.answer = this.form.find('[name="answerText"]').val();
        let fields = new FormData(this.form[0]);
        this.form.find('[data-form-submit]').attr('disabled', true);
        $.ajax({
            url: this.actionUrl,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: fields,
            type: 'POST',
            success: (data) => {
                if (data.result) {
                    this.answerBlock.remove();
                    this.showNewReview();
                } else if (data.error) {

                }
                this.form.find('[data-form-submit]').attr('disabled', false);
            },
        });

    },
    showNewReview: function () {
        this.btnAnswer.hide();
        //this.form.hide();
        let answer = '<div class="feedback__answer js-feedback__answer"><div class="main__text main__text-name-author">'+this.author+'</div><div class="main__text">'+this.answer+'</div><a href="#" class="marketDetail-reviews-list__answer-edit js-marketDetail-reviews-list__answer-edit"><span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-linejoin="round" stroke-width="2"><path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"></path><path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"></path><path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"></path></g></svg></span></a></div>';
        this.$container.append(answer);
    }

};
