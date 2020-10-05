$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function collect (that) {
    let fields = that.serializeObject();
    let data = {
        fields:  fields,
        formId: that.attr('id'),
    };
    return data;
}

function errorInput (response) {
    let errorBlock = $('[name='+response['error']['field']+']').closest('.formGroup').find('.js-errorInput');
    let inputWr = $('[name='+response['error']['field']+']').closest('.formGroup');
    if (response['error'].length != 0 && errorBlock.length != 0) {
        errorBlock.text(response['error']['text']);
        inputWr.addClass('errorInput');
    }
}
