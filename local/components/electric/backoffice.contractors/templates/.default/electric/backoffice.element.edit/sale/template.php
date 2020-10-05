<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a href="<?=$arResult["SEF_FOLDER"]?>sales/" class="btn-back">Вернуться назад</a>
<div class="cabinet__top">
    <div class="main__text main__text--lg">Редактирование "<?=$arResult["ELEMENT"]["NAME"]?>"</div>
</div>

<form autocomplete="off" method="POST" action="/api/backoffice.sale.update/" data-back-form-sale-update enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="saleID" value="<?=$arResult["ELEMENT"]["ID"]?>">
    <div class="cabinet__box">
        <div class="cabinet__block">
            <div class="cabinet__form">
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Название</div>
                    <div class="cabinet__form-field">
                        <input type="text" name="NAME" placeholder=""  autocomplete="off" class="field-input field-input--light" required="required" value="<?=$arResult["ELEMENT"]["NAME"]?>">
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Описание</div>
                    <div class="cabinet__form-field">
                        <textarea name="PREVIEW_TEXT" id="" cols="30" rows="10" class="field-textarea field-textarea--lg field-textarea--light" required="required"><?=$arResult["ELEMENT"]["PREVIEW_TEXT"]?></textarea>
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Изображение</div>
                    <div class="cabinet__form-field cabinet__form-field--inline">
                        <div class="cabinet__form-photo cabinet__form-photo--empty cabinet__form-photo--sm" data-logo-block
                             style="background-image: url('<?=$arResult["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                        <div class="cabinet__form-rside">
                            <label for="field-add-photo" class="field-add-photo">
                                <input type="file" name="PREVIEW_PICTURE" id="field-add-photo">
                                <span class="field-add-photo__title">Загрузить афишу</span>
                            </label>
                            <div class="main__text main__text--xs light">Вы можете загрузить изображение в формате
                                JPG, GIF или PNG. Размер фото не больше 10 Мб
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cabinet__actions">
        <button class="btn btn--red" data-form-submit>Обновить</button>
        <a href="<?=$arResult["SEF_FOLDER"]?>sales/" class="main__text link">Отменить</a>
    </div>
</form>
