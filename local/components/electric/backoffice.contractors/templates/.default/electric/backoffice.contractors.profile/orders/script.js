$(document).ready(function () {

    $(".js-cabinet__sorting-item").on("click","[data-sort]",function () {
        let type = $(this).data("sort");
        $("[data-all]").hide();
        $("[data-"+type+"]").show();
    });


    const orderNodes = document.querySelectorAll('[data-order]');
    window.OrdersModels = [];

    orderNodes.forEach(order => {
        const orderId = order.dataset.order;
        window.OrdersModels.push(new Order(orderId))
    });
});





function Order(id) {
    this.id = id;
    this.$container = $(`[data-order=${this.id}]`);
    this.actionUrl = "/api/backoffice.order.update/";
    this.actionUrlCall = "/api/call/";
    this.$container.on('click', '.is-open-show-contacts', this.handleOpenContacts.bind(this) );
    this.$container.on('click', '.is-btn-apply', this.handleBtnApply.bind(this) );
    this.$container.on('click', '.is-close-order', this.handleStayReview.bind(this) );
    this.$container.on('click', '.is-btn-complete', this.handleCompleteOrder.bind(this) );
    this.$container.on('click', '.is-btn-renouncement', this.handleRenouncementOrder.bind(this) );
    this.$container.on('click', '.is-btn-back', this.handleBackOrder.bind(this) );
    this.$container.on('click', '.is-btn-cancel', this.handleCancelOrder.bind(this) );
    this.$container.on('click', '[data-make-call]', this.handleMakeCall.bind(this));
}

Order.prototype = {

    handleOpenContacts: function () {
        console.log("handleOpenContacts");
        this.$container.find('.is-open-show-contacts').hide();
        this.$container.find('.is-step-first').addClass('is-show-contacts');

    },
    handleBtnApply: function(){
        console.log("handleBtnApply");
        this.sendRequest({"action":"applyOrder","order":this.id});
        this.$container.find('.is-step-first').hide();
        this.$container.find('.is-step-in-progress').show();
        //this.$container.find('.is-step-review').show();

    },
    handleCompleteOrder: function () {
        console.log("handleCompleteOrder");
        let reviewText = this.$container.find("[data-review]").val();
        let mark = this.$container.find("[data-mark]").val() ?? 1;

        this.sendRequest({"action":"completeOrder","order":this.id,"review":reviewText,"mark": mark});

        this.$container.find('.is-step-review').hide();
        this.$container.find('.is-step-complete').show();
        this.$container.addClass('is-order-complete');

    },
    handleStayReview: function(){
        console.log("handleStayReview");

        this.sendRequest({"action":"stayReviewOrder","order":this.id});

        this.$container.find('.is-step-in-progress').hide();
        this.$container.find('.is-step-review').show();

    },
    handleRenouncementOrder: function(){
        console.log("handleRenouncementOrder");
        this.$container.find('.is-step-first').hide();
        this.$container.find('.is-step-cancel').show();
    },
    handleBackOrder: function(){
        console.log("handleBackOrder");
        this.$container.find('.is-step-first').show();
        this.$container.find('.is-step-cancel').hide();
    },
    handleCancelOrder: function(){
        console.log("handleCancelOrder");
        let reasonText = this.$container.find("[data-reason-description]").val();
        let reason = this.$container.find('[data-reason]:checked').val();
        this.sendRequest({"action":"cancelOrder","order":this.id,"reason":reason,"reasonText": reasonText});

        this.$container.find('.is-step-renouncement').show();
        this.$container.find('.is-step-cancel').hide();
        this.$container.addClass('is-order-renouncement');
    },

    handleMakeCall: function(){
        console.log("make-call");
        this.sendRequestCall({"order":this.id,"action":"makeCall","firstCall":"contact"})
    },

    sendRequest: function ( fields ) {

        let action = this.actionUrl;
        if (!action) {
            alert('Не указан обработчик запроса');
            return false;
        } else {
            $.ajax({
                url: action,
                dataType: 'json',
                cache: false,
                data: fields,
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        return true;
                    } else if (data.error) {
                        return false;
                    }
                },
            })
        }
    },

    sendRequestCall: function ( fields ) {

        let action = this.actionUrlCall;
        if (!action) {
            alert('Не указан обработчик запроса');
            return false;
        } else {
            $.ajax({
                url: action,
                dataType: 'json',
                cache: false,
                data: fields,
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        $('#successMakeCallModal').fadeIn();
                        return true;
                    } else if (data.error) {
                        return false;
                    }
                },
            })
        }
    }

};
