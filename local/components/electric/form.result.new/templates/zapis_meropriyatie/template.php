<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?
if (strlen($_POST['web_form_submit']) && $_POST['web_form_submit'] == "Y" && $_POST['web_form_type'] == "zapis_meropriyatie") {
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
<?
//var_dump($arResult);die();
?>
<?$arResult["FORM_HEADER"] = str_replace('ZAPIS_MEROPRIYATIE"','ZAPIS_MEROPRIYATIE" class="event__form" ',$arResult["FORM_HEADER"]);?>


<div class="event__bottom-form">
    <div class="main__text main__text--md red">Записаться на мероприятие</div>

<?=$arResult["FORM_HEADER"]?>
    <?
    foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
            echo $arQuestion["HTML_CODE"];
        }
    }
    ?>
        <div class="event__form-title main__text">Контактная информация</div>
        <div class="event__form-error message"></div>
        <div class="event__form-items">
            <div class="event__form-item event__form-item--sm">
                <input type="text" name="form_text_1" id="" placeholder="ФИО" class="field-input field-input--white">
            </div>
            <div class="event__form-item event__form-item--sm">
                <input type="text" name="form_text_2" id="" placeholder="Номер телефона" data-phone
                       class="field-input field-input--white">
            </div>
            <div class="event__form-item event__form-item--sm">
                <input type="hidden" name="web_form_submit" value="Y" />
                <input type="hidden" name="web_form_type" value="zapis_meropriyatie" />
                <button class="btn btn--red js-event-button" type="submit">Записаться</button>
            </div>
        </div>
        <div class="event__form-bottom main__text main__text--xs light">Отправляя форму я соглашаюсь на
            обработку моих персональных данных, а также с <a href="<?=PATH_TERMS?>" class="main__text link-xs">политикой конфиденциальности</a>
        </div>
    <?=$arResult["FORM_FOOTER"]?>
</div>
<div class="event__bottom-success">
    <div class="event__bottom-middle">
        <img src="<?=SITE_TEMPLATE_PATH?>/images/logo-check-form.png" class="event__bottom-logo" alt="">
        <div class="event__bottom-description">
            <div class="main__text main__text--md">Отлично!</div>
            <div class="main__text">В ближайшее время мы обработаем ваши данные
                и специалист центра обучения свяжется в вами
            </div>
            <a href="#" class="main__text link js-event-more">Отправить еще одну заявку</a>
        </div>
    </div>
</div>


<script>
	$(document).on('submit', 'form.event__form', function(e){
	    e.preventDefault();
	    var $form = $(this);
	    $.post($form.attr('action'), $form.serialize(), function(data){
	        $('input', $form).removeAttr('disabled');

	        if (data.type == 'error') {
                $form.find(".message").removeClass("success-message");
	        	$form.find(".message").addClass("error-message");
	            $form.find(".message").html(data.message);
	        } else {
                $form.find(".message").html('');
                $form.find("input:not([type='hidden'])").val('');
                $form.find("textarea").val('');

                $('.event__bottom').addClass('is-success');
	        }
	    }, 'json');
	    return false;
	});


</script>