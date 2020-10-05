<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="formSolo-workarea js-formSolo-workarea">
    <div class="formSolo-workarea-toggle js-formSolo-workarea-toggle">
        <a href="/auth/" class="formSolo-workarea-toggle__button button2">
            Авторизация
        </a>
    </div>
<?if(!$arResult["CONTRACTOR_REGISTRATION"]):?>
    <?if($arResult["CONFIRM"]):?>

        <h1 class="formSolo-workarea__title left-align custom">
            Регистрация
        </h1>
        <p>
            <?=$arResult["CONFIRM_MESSAGE"]?>
        </p>
    <?else:?>
        <?
            $registrationTypes = [
                    ["id"=>"client","name"=>"Клиент"],
                    ["id"=>"contractor","name"=>"Исполнитель"],
            ];
            $preselectedType = "contractor";
        ?>

        <h1 class="formSolo-workarea__title left-align js-form-title custom">
            Регистрация
        </h1>
        <div class="js-form-text">
            <p>
                Начните это бесплатно
            </p>
        </div>
        <div class="formSolo-workarea-main js-tabs">
            <div class="formSolo-workarea-toggler js-tabs-buttons">

                <? foreach ($registrationTypes as $registrationType) {?>
                    <div class="formSolo-workarea-toggler__single js-tabs-buttons-single">
                        <a href="#<?=$registrationType["id"]?>" class="<?=($registrationType["id"] == $preselectedType)?"active":""?> formSolo-workarea-toggler__single-inner button2 button2_large js-tabs-buttons-single-inner js-set-cookie">
                            <?=$registrationType["name"]?>
                        </a>
                    </div>
                <?}?>
            </div>

            <div class="formSolo-workarea-content js-slider-tabs js-tabs-content">

                <? foreach ($registrationTypes as $registrationType) {?>

                <div data-id="<?=$registrationType["id"]?>" class="formSolo-workarea-content-inner js-tabs-content-single">
                    <form autocomplete="off" id="<?=$registrationType["id"]?>" method="POST" action="/api/user.register.request/" data-form-register>
                        <div class="js-form-error js-form-error-<?=$registrationType["id"]?>"></div>
                        <input hidden name="registration-type" value="<?=$registrationType["id"]?>">
                        <?if($registrationType["id"] == "client"):?>
                            <div class="formGroup required">
                                <div class="formGroup-inner">
                                    <label for="<?=$registrationType["id"]?>-name">ФИО</label>
                                    <input type="text" value="" id="<?=$registrationType["id"]?>-name"
                                           name="registration-name" maxlength="255" required placeholder="ФИО" pattern=".*\S+.*" autocomplete="NoAutocomplete">
                                    <div class="formGroup__error js-errorInput"></div>
                                </div>
                            </div>
                        <?endif;?>
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <label for="<?=$registrationType["id"]?>-email">E-mail</label>
                                <input type="email" value="" id="<?=$registrationType["id"]?>-email"
                                       name="registration-email" maxlength="100" required placeholder="E-mail" autocomplete="NoAutocomplete">
                                <div class="formGroup__error js-errorInput"></div>
                            </div>
                        </div>
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <div class="showPass js-showPassButton" title="Скрыть/показать пароль">
                                    <img src="/local/templates/.default/img/no-visible.png"
                                         srcset="/local/templates/.default/img/no-visible@2x.png 2x,
                                                             /local/templates/.default/img/no-visible@3x.png 3x"
                                         width="24" height="24" alt="">
                                </div>
                                <label for="<?=$registrationType["id"]?>-password">Пароль</label>
                                <input type="password"
                                       value=""
                                       id="<?=$registrationType["id"]?>-password"
                                       name="registration-password"
                                       minlength="6"
                                       maxlength="15"
                                       required
                                       data-pass-set="yes"
                                       class="passwordField"
                                       placeholder="Пароль"
                                       autocomplete="new-password"
                                >
                                <div class="formGroup__error js-errorInput"></div>
                            </div>
                        </div>
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <div class="custCheckbox">
                                    <input type="checkbox" value="1" id="<?=$registrationType["id"]?>-personalData" name="registration-personalData" required>
                                    <label for="<?=$registrationType["id"]?>-personalData" class="custLabel">
                                        Настоящим я даю согласие на обработку
                                        моих персональных данных на указанных
                                        ниже условиях <a href="/terms/" class="inhLink" target="_blank">обработки персональных данных</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="formGroup formGroup__bottom">
                            <div class="formGroup-inner">
                                <button type="submit" name="registration-submit">
                                    Зарегистрироваться
                                </button>
                            </div>
                        </div>
                    </form>

                    <?$APPLICATION->IncludeComponent("electric:auth.soc","",["TITLE"=>"Зарегистрируйтесь через социальные сети","IGNOR_REG" => "Y"],false);?>

                </div>

                <?}?>

            </div>
        </div>

    <?endif;?>

<?else:?>

    <?
    $registrationTypes = [
        ["id"=>"individual","name"=>"Физическое лицо"],
        ["id"=>"legal","name"=>"Юридическое лицо"],
    ];
    $preselectedType = "individual";
    ?>

    <h1 class="formSolo-workarea__title left-align js-form-title">Информация о вас</h1>
    <div class="js-form-text">
        <p>
            Заполните свои данные для того, чтобы вас могли найти
        </p>
    </div>
    <div class="formSolo-workarea-main js-tabs">

        <div class="formSolo-workarea-toggler js-tabs-buttons">
            <? foreach ($registrationTypes as $registrationType) {?>
                <div class="formSolo-workarea-toggler__single js-tabs-buttons-single">
                    <a href="#<?=$registrationType["id"]?>" class="<?=($registrationType["id"] == $preselectedType)?"active":""?> formSolo-workarea-toggler__single-inner button2 button2_large js-tabs-buttons-single-inner">
                        <?=$registrationType["name"]?>
                    </a>
                </div>
            <?}?>
        </div>

        <div class="formSolo-workarea-content js-slider-tabs js-tabs-content">

            <? foreach ($registrationTypes as $registrationType) {?>

                <div data-id="<?=$registrationType["id"]?>" class="formSolo-workarea-content-inner js-tabs-content-single">
                    <form autocomplete="off" action="/api/user.register.request/" method="POST" id="<?=$registrationType["id"]?>" data-form-register-info  enctype="multipart/form-data">
                        <input hidden name="registration-info-type" value="<?=$registrationType["id"]?>">
                        <input hidden name="registration-info-user" value="<?=$arResult["USER"]?>">
                        <input hidden name="registration-info-hash" value="<?=$arResult["HASH"]?>">

                        <?if($registrationType["id"] == "individual"):?>
                            <div class="formGroup required">
                                <div class="formGroup-inner">
                                    <label for="<?=$registrationType["id"]?>-name">ФИО</label>
                                    <input type="text" value="" id="<?=$registrationType["id"]?>-name"
                                           name="registration-info-name" maxlength="255" required placeholder="ФИО" pattern=".*\S+.*">
                                </div>
                            </div>
                        <?endif;?>


                        <?if($registrationType["id"] == "legal"):?>
                            <div class="formGroup required">
                                <div class="formGroup-inner">
                                    <label for="<?=$registrationType["id"]?>-name">Название организации</label>
                                    <input type="text" value="" id="<?=$registrationType["id"]?>-name"
                                           name="registration-info-name" maxlength="512" required placeholder="Название организации" pattern=".*\S+.*">
                                </div>
                            </div>
                        <?endif;?>

                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <label for="<?=$registrationType["id"]?>-phone">Телефон</label>
                                <input type="tel" value="" id="<?=$registrationType["id"]?>-phone"
                                       name="registration-info-phone" required placeholder="Телефон">
                            </div>
                        </div>

                        <?if($registrationType["id"] == "legal"):?>
                            <div class="formGroup required">
                                <div class="formGroup-inner">
                                    <label for="<?=$registrationType["id"]?>-address">Адрес</label>
                                    <input type="text" value="" id="<?=$registrationType["id"]?>-address"
                                           name="registration-info-address" maxlength="255" required placeholder="Адрес" pattern=".*\S+.*">
                                </div>
                            </div>

                            <div class="formGroup">
                                <div class="formGroup-inner">
                                    <label for="<?=$registrationType["id"]?>-site">Сайт (если есть)</label>
                                    <input type="text" value="" id="<?=$registrationType["id"]?>-site"
                                           name="registration-info-site" maxlength="255" placeholder="Сайт (если есть)">
                                </div>
                            </div>
                        <?endif;?>

                        <?if($registrationType["id"] == "individual"):?>
                            <div class="formGroup">
                                <div class="formGroup-inner">
                                    <div class="custFile js-custFile">
                                        <label for="<?=$registrationType["id"]?>-photo" class="custLabel button2">Загрузить фото</label>
                                        <input type="file"
                                               value=""
                                               id="<?=$registrationType["id"]?>-photo"
                                               name="registration-info-photo"
                                               >
                                        <div class="custFile-list js-custFile-list">
                                            <div class="custFile-list-single js-custFile-list-single"></div>
                                        </div>
                                        <div class="custFile-response js-custFile-response">
                                            Размер файла превышает 10Мб
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>

                        <?if($registrationType["id"] == "legal"):?>
                            <div class="formGroup">
                                <div class="formGroup-inner">
                                    <div class="custFile js-custFile">
                                        <label for="<?=$registrationType["id"]?>-photo" class="custLabel button2">Загрузить логотип компании</label>
                                        <input type="file"
                                               value=""
                                               id="<?=$registrationType["id"]?>-photo"
                                               name="registration-info-photo"
                                               accept="image/jpg,image/jpeg,image/png,image/bmp"
                                               data-max-size="10485760">
                                        <div class="custFile-list js-custFile-list">
                                            <div class="custFile-list-single js-custFile-list-single"></div>
                                        </div>
                                        <div class="custFile-response js-custFile-response">
                                            Размер файла превышает 10Мб
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>

                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <div class="custCheckbox">
                                    <input type="checkbox" value="1" id="<?=$registrationType["id"]?>-personalData" name="registration-info-personalData" required>
                                    <label for="<?=$registrationType["id"]?>-personalData" class="custLabel">
                                        Настоящим я даю согласие на обработку
                                        моих персональных данных на указанных
                                        ниже условиях <a href="/terms/" class="inhLink" target="_blank">обработки персональных данных</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="formGroup formGroup__bottom">
                            <div class="formGroup-inner">
                                <button type="submit" name="<?=$registrationType["id"]?>-submit">
                                    Сохранить
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            <?}?>

        </div>
    </div>
    <script>
        setCookie('social-register', "Y");
    </script>
<?endif;?>
</div>
