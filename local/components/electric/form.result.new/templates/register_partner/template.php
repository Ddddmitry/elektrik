<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?
if (strlen($_POST['web_form_submit']) && $_POST['web_form_submit'] == "Y" && $_POST['web_form_type'] == "about__form") {
	$APPLICATION->RestartBuffer();
	if (!defined('PUBLIC_AJAX_MODE')) {
		define('PUBLIC_AJAX_MODE', true);
	}
	header('Content-type: application/json');
	if ($arResult['FORM_ERRORS_TEXT']) {
		echo json_encode([
			'type' => 'error',
			'message' => $arResult['FORM_ERRORS_TEXT'],
		]);
	} else {
		echo json_encode([
			'type' => 'ok',
			'message' => 'Ваше сообщение успешно отправлено!'
		]);
	}
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
	die();
}
?>

<?$arResult["FORM_HEADER"] = str_replace('REGISTER_PARTNER"','REGISTER_PARTNER" class="about__form" ',$arResult["FORM_HEADER"]);?>



<?=$arResult["FORM_HEADER"]?>
<?
foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
    if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
        echo $arQuestion["HTML_CODE"];
    }
}
?>
        <div class="about__form-item about__form-item--sm">
            <label for="field-name" class="field-label">Название компании</label>
            <input type="text" name="form_text_3" id="field-name" placeholder="ООО Электромен" class="field-input field-input--light">
        </div>
        <div class="about__form-item about__form-item--sm">
            <label for="field-site" class="field-label">Сайт компании</label>
            <input type="text" name="form_text_4" id="field-site" placeholder="company.ru" class="field-input field-input--light">
        </div>
        <div class="about__form-item about__form-item--sm">
            <label for="field-phone" class="field-label">Телефон для связи</label>
            <input type="text" name="form_text_5" id="field-phone" placeholder="+7 999 999-99-99" class="field-input field-input--light" data-phone>
        </div>
        <div class="about__form-item about__form-item--lg">
            <label for="field-name" class="field-label">Сообщение</label>
            <textarea type="text" name="form_textarea_6" id="field-massage" placeholder="" class="field-textarea field-textarea--light"></textarea>
        </div>
        <div class="about__form-item about__form-item--action">

            <input type="hidden" name="web_form_submit" value="Y" />
            <input type="hidden" name="web_form_type" value="about__form" />
            <button class="btn btn--red">Отправить заявку</button>
            <div class="main__text main__text--xs">Отправляя форму я соглашаюсь на обработку моих персональных
                данных, а также
                с политикой конфиденциальности
            </div>
        </div>
        <div class="about__form-item about__form-item--action message"></div>
<?=$arResult["FORM_FOOTER"]?>





<script>
	$(document).on('submit', 'form.about__form', function(e){
	    e.preventDefault();
	    var $form = $(this);
	    $.post($form.attr('action'), $form.serialize(), function(data){
	        $('input', $form).removeAttr('disabled');

	        if (data.type == 'error') {
                $form.find(".message").removeClass("success-message");
	        	$form.find(".message").addClass("error-message");
	            $form.find(".message").html(data.message);
	        } else {
                $form.find(".message").html(data.message);
                $form.find("input:not([type='hidden'])").val('');
                $form.find("textarea").val('');
                ym(56492371,'reachGoal','order-connect');
            }
	    }, 'json');
	    return false;
	});


</script>