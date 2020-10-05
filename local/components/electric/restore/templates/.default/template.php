<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="formSolo-workarea js-formSolo-workarea">

    <div class="formSolo-workarea-toggle formSolo-workarea-toggle_inside js-formSolo-workarea-toggle">
        <a href="/auth/" class="formSolo-workarea-toggle__back">
            <div class="formSolo-workarea-toggle__back-arrow"></div>
            Вернуться на авторизацию
        </a>
        <a href="/register/" class="formSolo-workarea-toggle__button button2">
            Регистрация
        </a>
    </div>

    <div class="formSolo-workarea-response formSolo-workarea-response_form">
        <?if($arResult['params'] == false):?>

            <h1 class="left-align">
                Восстановление пароля
            </h1>
            <div class="js-form-text">
                <p>
                    Введите ваш номер телефона
                </p>
            </div>
            <form action="/api/user.restore.request/" id="forgot_password" data-form-restore>
                <div class="js-form-error"></div>
                <div class="formGroup required">
                    <div class="formGroup-inner">
                        <label for="forgot_password-phone">Телефон</label>
                        <input type="text" value="" id="forgot_password-phone" data-phone name="phone" maxlength="100" required placeholder="+7 (915) 123-45-67">
                        <div class="formGroup__error js-errorInput"></div>
                    </div>
                </div>
                <div class="formGroup formGroup__bottom">
                    <div class="formGroup-inner">
                        <button type="submit" name="forgot_password-submit">
                            Восстановить пароль
                        </button>
                    </div>
                </div>
            </form>

        <?else:?>
            <h1 class="left-align">
                Создание нового пароля
            </h1>
            <div class="js-form-text">
                <p>
                    Введите новый пароль для вашего аккаунта
                </p>
            </div>
            <form action="/api/user.restore.request/" id="change_password" method="POST" data-form-restore-confirm>
                <input type="hidden" name="change" value="yes">
                <input type="hidden" name="checkword" value="<?=$arResult["params"]["checkword"]?>">
                <input type="hidden" name="login" value="<?=$arResult["params"]["login"]?>">
                <div class="js-form-error"></div>
                <div class="formGroup required">
                    <div class="formGroup-inner">
                        <div class="showPass js-showPassButton" title="Скрыть/показать пароль">
                            <img src="/local/templates/.default/img/no-visible.png"
                                 srcset="/local/templates/.default/img/no-visible@2x.png 2x,
                                                         /local/templates/.default/img/no-visible@3x.png 3x"
                                 width="24" height="24" alt="">
                        </div>
                        <label for="password">Новый пароль</label>
                        <input type="password" value="" id="password"  class="passwordField" name="password" data-pass-set="yes" minlength="6" maxlength="15" required>
                        <div class="formGroup__error js-errorInput"></div>
                    </div>
                </div>
                <div class="formGroup formGroup__bottom">
                    <div class="formGroup-inner">
                        <button type="submit" data-form-submit name="change_password-submit">
                            Сменить пароль и войти
                        </button>
                    </div>
                </div>
            </form>

        <?endif;?>
    </div>


</div>
