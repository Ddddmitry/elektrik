<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="formSolo-workarea js-formSolo-workarea">
    <div class="formSolo-workarea-toggle js-formSolo-workarea-toggle">
        <a href="/register/" class="formSolo-workarea-toggle__button button2">
            Регистрация
        </a>
    </div>
    <h1 class="formSolo-workarea__title left-align custom">
        Войти
    </h1>
    <p >
        Начните это бесплатно
    </p>
    <div class="formSolo-workarea-main">
        <div class="formSolo-workarea-content">
            <div class="formSolo-workarea-content-inner">
                <form id="autorization" autocomplete="off" method="POST" action="/api/user.auth.request/" data-form-auth>
                    <div class="js-form-error"></div>
                    <div class="formGroup required">
                        <div class="formGroup-inner">
                            <label for="autorization-email">Телефон</label>
                            <input type="text" value="" data-phone id="autorization-phone" name="autorization-phone" maxlength="100" required placeholder="+7 (915) 123-45-67">
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
                            <label for="autorization-password">Пароль</label>
                            <input type="password"
                                   value=""
                                   id="autorization-password"
                                   name="autorization-password"
                                   required
                                   class="passwordField"
                                   minlength="6" maxlength="15"
                                   placeholder="Пароль"
                                   autocomplete="NoAutocomplete">
                            <div class="formGroup__error js-errorInput"></div>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formGroup-inner">
                            <a href="/auth/restore/">
                                Забыли пароль?
                            </a>
                        </div>
                    </div>
                    <div class="formGroup formGroup__bottom">
                        <div class="formGroup-inner">
                            <button type="submit" name="autorization-submit">
                                Авторизоваться
                            </button>
                        </div>
                    </div>
                </form>

                <?$APPLICATION->IncludeComponent("electric:auth.soc","",["TITLE"=>"Войдите через социальные сети"],false);?>
            </div>
        </div>
    </div>
</div>
