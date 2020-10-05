$(document).ready(function () {

    var $educationsSearch = $('[data-educations-search]');

    if ($educationsSearch.length) {
        $educationsSearch.on('submit', function (e) {
            $educationsSearch.find('input')[0].value = $educationsSearch.find('input')[0].value.replace(/</g, "< ");
        });
    }

});