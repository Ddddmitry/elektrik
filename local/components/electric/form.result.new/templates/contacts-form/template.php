<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?
if (strlen($_POST['web_form_submit']) && $_POST['web_form_submit'] == "Y" && $_POST['web_form_type'] == "contacts-form") {
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
			'message' => 'Ваше сообщение успешно отправлено! contacts-form'
		]);
	}
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
	die();
}
?>

<?$arResult["FORM_HEADER"] = str_replace('CALLBACK_FORM"','CALLBACK_FORM" class="contacts-form" ',$arResult["FORM_HEADER"]);?>

<?=$arResult["FORM_HEADER"]?>

<h2 class="contacts-form__title">Заказать обратный звонок</h2>
<p class="contacts-form__descr">Заполните форму заказа звонка и наш менеджер Вам перезвонит.</p>

<p class="message"></p>


    <div class="contacts-form__inputs">
        <?
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
            if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                echo $arQuestion["HTML_CODE"];
            }
        }
        ?>
        <div class="field-group">
            <p class="field-label">Ваше имя *</p>
            <input type="text" name="form_text_3" placeholder="Константин">
        </div>
        <div class="field-group">
            <p class="field-label">Номер телефона *</p>
            <input type="tel" name="form_text_4" placeholder="+7 (___) ___ - __ - __" data-num-min="11">
        </div>
        <div class="field-group">
            <p class="field-label">Город</p>
            <input type="text" name="form_text_5" placeholder="Для учёта разницы во времени">
        </div>
    </div>
    <div class="contacts-form__textarea">
        <p class="field-label">Комментарий</p>
        <textarea name="form_textarea_6"></textarea>
    </div>
    <div class="contacts-form__controls">
        <p class="contacts-form__agree">Нажимая на кнопку, вы даете согласие на <a href="<?=OBRABOTKA_PER_DATA?>">обработку своих персональных данных</a></p>
        <input type="hidden" name="web_form_submit" value="Y" />
        <input type="hidden" name="web_form_type" value="contacts-form" />
        <button class="contacts-form__submit btn_medium btn-white zakaz_zvonka" type="submit">Заказать</button>
    </div>


<?=$arResult["FORM_FOOTER"]?>


<script>
	$(document).on('submit', 'form.contacts-form', function(){
	    var $form = $(this);
	    $.post($form.attr('action'), $form.serialize(), function(data){
	        $('input', $form).removeAttr('disabled');

	        if (data.type == 'error') {
                $form.find(".message").removeClass("success-message");
	        	$form.find(".message").addClass("error-message");
	            $form.find(".message").html(data.message);
	        } else {
                callbackClose();
                showModal("modal-thx");
                $form.find(".message").html('');
                $form.find("input").val('');
                $form.find("textarea").val('');
	        }
	    }, 'json');
	    return false;
	});
</script>