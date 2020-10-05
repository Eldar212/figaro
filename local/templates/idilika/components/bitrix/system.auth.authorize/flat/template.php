<?
if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS( "/bitrix/css/main/system.auth/flat/style.css" );
?>

<div id="default-layout">
    <div class="bx-authform">

		<?
		if ( ! empty( $arParams["~AUTH_RESULT"] ) ):
			$text = str_replace( array( "<br>", "<br />" ), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"] );
			?>
            <div class="alert alert-danger"><?= nl2br( htmlspecialcharsbx( $text ) ) ?></div>
		<? endif ?>

		<?
		if ( $arResult['ERROR_MESSAGE'] <> '' ):
			$text = str_replace( array( "<br>", "<br />" ), "\n", $arResult['ERROR_MESSAGE'] );
			?>
            <div class="alert alert-danger"><?= nl2br( htmlspecialcharsbx( $text ) ) ?></div>
		<? endif ?>

        <h3 class="bx-title">Авторизация</h3>

		<? if ( $arResult["AUTH_SERVICES"] ): ?>
			<?
			$APPLICATION->IncludeComponent( "bitrix:socserv.auth.form",
				"flat",
				array(
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"AUTH_URL"      => $arResult["AUTH_URL"],
					"POST"          => $arResult["POST"],
				),
				$component,
				array( "HIDE_ICONS" => "Y" )
			);
			?>


            <div class="or-line">
                <hr class="bxe-light">
                <span>ИЛИ</span>
            </div>
		<? endif ?>

        <div class="auth-buttons">
            <button class="btn-black auth-toggle" data-auth-toggle="phone-layout">Войти по номеру телефона</button>
            <button class="btn-white auth-toggle" data-auth-toggle="email-layout">Войти по Email</button>
        </div>

        <div class="auth-links">
            <a href="/registration/">Зарегистрироваться?</a>
        </div>

    </div>

</div>

<div id="phone-layout">

    <div class="bx-authform">

        <h3 class="bx-title">Вход по коду</h3>
        <div class="bx-authform-social-message">Введите номер вашего телефона, чтобы получить код по СМС</div>

        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-input-container">
                <input type="text" class="phone-mask" name="PHONE_NUMBER" maxlength="255" />
            </div>
        </div>

        <div class="auth-buttons" style="margin-top: 16px;">
            <button class="btn-black get-code">Получить код</button>
        </div>

        <div class="auth-links">
            <div><a class="auth-toggle" data-auth-toggle="default-layout">Авторизация</a></div>
            <div><a href="/registration/">Зарегистрироваться?</a></div>
        </div>

    </div>

</div>

<div id="entrycode-layout">

    <div class="bx-authform">

        <h3 class="bx-title"></h3>
        <div class="bx-authform-social-message">Введите код из SMS-сообщения</div>

        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-input-container">
                <input type="text" class="phone-auth-code" name="PHONE_AUTH_CODE" maxlength="255" />
            </div>
        </div>

        <div class="auth-buttons" style="margin-top: 16px;">
            <button class="btn-black auth-by-phone-code">Войти</button>
        </div>

        <div class="auth-timer">Запросить код повторно можно черз 12 секунды</div>

        <div class="auth-links">
            <div><a class="auth-toggle" data-auth-toggle="default-layout">Авторизация</a></div>
        </div>

    </div>

</div>

<div id="email-layout">

    <div class="bx-authform">
        <h3 class="bx-title">Вход</h3>

        <div class="bx-authform-social-message">Воидите с помощью электронной почты</div>

        <form name="form_auth" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">

            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>
			<? if ( strlen( $arResult["BACKURL"] ) > 0 ): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
			<? endif ?>
			<? foreach ( $arResult["POST"] as $key => $value ): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
			<? endforeach ?>

            <div class="bx-authform-formgroup-container">
                <div class="bx-authform-input-container">
                    <input type="text" name="USER_LOGIN" maxlength="255" placeholder="Имя пользователя или E-mail"/>
                </div>
            </div>
            <div class="bx-authform-formgroup-container">
                <div class="bx-authform-input-container">
                    <input type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" placeholder="Пароль"/>
                </div>
            </div>

			<? if ( $arResult["CAPTCHA_CODE"] ): ?>
                <input type="hidden" name="captcha_sid" value="<? echo $arResult["CAPTCHA_CODE"] ?>"/>

                <div class="bx-authform-formgroup-container dbg_captha">
                    <div class="bx-authform-label-container">
						<? echo GetMessage( "AUTH_CAPTCHA_PROMT" ) ?>
                    </div>
                    <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<? echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/></div>
                    <div class="bx-authform-input-container">
                        <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                    </div>
                </div>
			<? endif; ?>

            <div class="auth-buttons" style="margin-top: 16px;">
                <button type="submit" class="btn-black" name="Login">Войти</button>
            </div>
        </form>

        <div class="auth-links">
            <div><a class="auth-toggle" data-auth-toggle="default-layout">Авторизация</a></div>
            <div><a href="/registration/">Зарегистрироваться?</a></div>
        </div>

    </div>

</div>

<script type="text/javascript">
	<?if (strlen( $arResult["LAST_LOGIN"] ) > 0):?>
    try {
        document.form_auth.USER_PASSWORD.focus();
    } catch (e) {
    }
	<?else:?>
    try {
        document.form_auth.USER_LOGIN.focus();
    } catch (e) {
    }
	<?endif?>
</script>

