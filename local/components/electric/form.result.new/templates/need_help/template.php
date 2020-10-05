<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?
if (strlen($_POST['web_form_submit']) && $_POST['web_form_submit'] == "Y" && $_POST['web_form_type'] == "about__form2") {
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

<?$arResult["FORM_HEADER"] = str_replace('NEED_HELP"','NEED_HELP" class="about__form js-about__form" ',$arResult["FORM_HEADER"]);?>

<?=$arResult["FORM_HEADER"]?>
<?
foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
    if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
        echo $arQuestion["HTML_CODE"];
    }
}

?>

<div class="about__form-item about__form-item--sm">
    <label for="field-name" class="field-label">Ваше имя</label>
    <input type="text" name="form_text_7" id="field-name" placeholder="Константин"
           class="field-input field-input--light">
</div>
<div class="about__form-item about__form-item--sm">
    <label for="field-site" class="field-label">Номер телефона</label>
    <input type="tel" name="form_text_8" id="field-site" placeholder=""
           class="field-input field-input--light" data-phone>
</div>
<div class="about__form-item about__form-item--sm">
    <label for="field-phone" class="field-label">Электронная почта</label>
    <input type="text" name="form_text_9" id="field-phone" placeholder="kostya@mail.ru"
           class="field-input field-input--light">
</div>
<div class="about__form-item about__form-item--lg">
    <label for="field-name" class="field-label">Сообщение</label>
    <?=$arResult["QUESTIONS"]["TYPE_MESSAGE"]["HTML_CODE"]?>

</div>
<div class="about__form-item about__form-item--lg">
    <label for="field-name" class="field-label">Опишите проблему</label>
    <textarea type="text" name="form_textarea_13" id="field-massage" placeholder=""
              class="field-textarea field-textarea--light"></textarea>
</div>
<div class="about__form-item about__form-item--action">
    <input type="hidden" name="web_form_submit" value="Y" />
    <input type="hidden" name="web_form_type" value="about__form2" />
    <button class="btn btn--red" type="submit">Отправить заявку</button>
    <div class="main__text main__text--xs light">Отправляя форму я соглашаюсь на обработку моих персональных
        данных, а также
        с <a href="<?=PATH_TERMS?>" class="main__text link-xs">политикой конфиденциальности</a>
    </div>
</div>
<div class="about__form-item about__form-item--action message"></div>


<?=$arResult["FORM_FOOTER"]?>

<script>
	$(document).on('submit', 'form.js-about__form', function(e){
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
                $form.find(".message").addClass("success-message");
                $form.find("input:not([type='hidden'])").val('');
                $form.find("textarea").val('');
                ym(56492371,'reachGoal','order-help');
            }
	    }, 'json');
	    return false;
	});


</script>