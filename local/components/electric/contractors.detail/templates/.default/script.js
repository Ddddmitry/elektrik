$(document).ready(function () {
    $('.js-open-more').click(function () {
        $(this).toggleClass('is-active');
        $(this).html() == "Свернуть" ?
            $(this).html('Ещё') : $(this).html('Свернуть');

        $(this).parent().find('.card__body-item.is-hide').slideToggle(400);
        return false;
    });

    const contractorNodes = document.querySelectorAll('[data-contractor]');
    window.ContractorModels = [];

    contractorNodes.forEach(contractor => {
        const contractorId = contractor.dataset.contractor;
        window.ContractorModels.push(new Contractor(contractorId));
    });

});

function Contractor(id) {

    this.id = id;
    this.$container = $(`[data-contractor=${this.id}]`);
    this.actionUrlCall = "/api/call/";

    this.$container.on('click', '[data-make-call]', this.handleMakeCall.bind(this));
}

Contractor.prototype = {



    handleMakeCall: function(){
        console.log("make-call");
        this.sendRequestCall({"contractor":this.id,"action":"makeCallProfile","firstCall":"operator"})
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