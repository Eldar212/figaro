<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 *
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 *
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

if ( $arResult["SHOW_SMS_FIELD"] == true ) {
	CJSCore::Init( 'phone_auth' );
}
?>
<div class="container">
    <div class="bx-auth-reg">

		<? if ( $USER->IsAuthorized() ): ?>

            <p><? echo GetMessage( "MAIN_REGISTER_AUTH" ) ?></p>

		<? else: ?>
		<?
		if ( count( $arResult["ERRORS"] ) > 0 ):
			foreach ( $arResult["ERRORS"] as $key => $error ) {
				if ( intval( $key ) == 0 && $key !== 0 ) {
					$arResult["ERRORS"][ $key ] = str_replace( "#FIELD_NAME#", "&quot;" . GetMessage( "REGISTER_FIELD_" . $key ) . "&quot;", $error );
				}
			}

			ShowError( implode( "<br />", $arResult["ERRORS"] ) );

        elseif ( $arResult["USE_EMAIL_CONFIRMATION"] === "Y" ):
		?>
            <p><? echo GetMessage( "REGISTER_EMAIL_WILL_BE_SENT" ) ?></p>
		<? endif ?>

		<? if ( $arResult["SHOW_SMS_FIELD"] == true ): ?>

            <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform">
				<?
				if ( $arResult["BACKURL"] <> '' ):
					?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
				<?
				endif;
				?>
                <input type="hidden" name="SIGNED_DATA" value="<?= htmlspecialcharsbx( $arResult["SIGNED_DATA"] ) ?>"/>
                <table>
                    <tbody>
                    <tr>
                        <td><? echo GetMessage( "main_register_sms" ) ?><span class="starrequired">*</span></td>
                        <td><input size="30" type="text" name="SMS_CODE" value="<?= htmlspecialcharsbx( $arResult["SMS_CODE"] ) ?>" autocomplete="off"/></td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="code_submit_button" value="<? echo GetMessage( "main_register_sms_send" ) ?>"/></td>
                    </tr>
                    </tfoot>
                </table>
            </form>

            <script>
                new BX.PhoneAuth({
                    containerId: 'bx_register_resend',
                    errorContainerId: 'bx_register_error',
                    interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
                    data:
					<?=CUtil::PhpToJSObject( [
						'signedData' => $arResult["SIGNED_DATA"],
					] )?>,
                    onError:
                        function (response) {
                            var errorDiv = BX('bx_register_error');
                            var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                            errorNode.innerHTML = '';
                            for (var i = 0; i < response.errors.length; i++) {
                                errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                            }
                            errorDiv.style.display = '';
                        }
                });
            </script>

            <div id="bx_register_error" style="display:none"><? ShowError( "error" ) ?></div>

            <div id="bx_register_resend"></div>

		<? else: ?>

            <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform" enctype="multipart/form-data">
                <div class="flex">
					<?
					if ( $arResult["BACKURL"] <> '' ) {

						echo '<input type="hidden" name="backurl" value="' . $arResult["BACKURL"] . '"/>';

					}

					foreach ( $arResult["SHOW_FIELDS"] as $FIELD ) {
						if ( $FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true ) {

							$Y = $arResult["VALUES"][ $FIELD ] == "Y" ? 'selected="selected"' : '';
							$N = $arResult["VALUES"][ $FIELD ] == "N" ? 'selected="selected"' : '';

							echo '<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements[\'REGISTER[TIME_ZONE]\'].disabled=(this.value != \'N\')">';
							echo '<option value="">' . GetMessage( "main_profile_time_zones_auto_def" ) . '</option>';
							echo '<option value="Y" ' . $Y . '>' . GetMessage( "main_profile_time_zones_auto_yes" ) . '</option>';
							echo '<option value="N" ' . $N . '>' . GetMessage( "main_profile_time_zones_auto_no" ) . '</option>';
							echo '</select>';

							if ( ! isset( $_REQUEST["REGISTER"]["TIME_ZONE"] ) ) {
								$time_zone_dis = 'disabled="disabled"';
							} else {
								$time_zone_dis = '';
							}

							echo '<select name="REGISTER[TIME_ZONE]" ' . $time_zone_dis . '>';
							foreach ( $arResult["TIME_ZONE_LIST"] as $tz => $tz_name ) {
								$tz_selected = $arResult["VALUES"]["TIME_ZONE"] == $tz ? 'selected="selected"' : '';
								echo '<option value="' . htmlspecialcharsbx( $tz ) . '" ' . $tz_selected . '>' . htmlspecialcharsbx( $tz_name ) . '</option>';
							}
							echo '</select>';

						} else {

							switch ( $FIELD ) {

								case "NAME":

									echo '<div class="reg-field"><input size="30" type="text" name="REGISTER[' . $FIELD . ']" placeholder="Имя и фамилия"></div>';

									break;

								case "PERSONAL_PHONE":

									echo '<div class="reg-field"><input size="30" type="text" name="REGISTER[' . $FIELD . ']" placeholder="Номер телефона"></div>';

									break;

								case "EMAIL":

									echo '<div class="reg-field"><input size="30" type="text" name="REGISTER[' . $FIELD . ']" placeholder="Адрес электронной почты"></div>';

									break;

								case "PERSONAL_BIRTHDAY":

									echo '<div class="reg-field"><input size="30" type="text" name="REGISTER[' . $FIELD . ']" placeholder="День рождения"></div>';

									break;

								case "PASSWORD":

									echo '<div class="reg-field password"><input size="30" type="password" name="REGISTER[' . $FIELD . ']" value="' . $arResult["VALUES"][ $FIELD ] . '" placeholder="Новый пароль" autocomplete="off" class="bx-auth-input"/></div>';

									if ( $arResult["SECURE_AUTH"] ) {

										echo '<span class="bx-auth-secure" id="bx_auth_secure" title="' . GetMessage( "AUTH_SECURE_NOTE" ) . '" style="display:none"><div class="bx-auth-secure-icon"></div></span>';
										echo '<noscript><span class="bx-auth-secure" title="' . GetMessage( "AUTH_NONSECURE_NOTE" ) . '"><div class="bx-auth-secure-icon bx-auth-secure-unlock"></div></span></noscript>';
										echo '<script type="text/javascript">document.getElementById(\'bx_auth_secure\').style.display = \'inline-block\';</script>';

									}

									break;

								case "CONFIRM_PASSWORD":

									echo '<div class="reg-field password"><input size="30" type="password" name="REGISTER[' . $FIELD . ']" value="' . $arResult["VALUES"][ $FIELD ] . '" placeholder="Повторите пароль" autocomplete="off" /></div>';

									break;

								case "PERSONAL_GENDER":

									echo '<span class="head_change_pass">Выберите пол</span>';

									echo '<div class="reg-genders flex flex-gender"><div class="gender_input flex radio">
                                        <input type="radio" id="gender_male" name="REGISTER[' . $FIELD . ']" value="M" checked>
                                        <label for="gender_male">Мужской пол</label>
                                    </div>
                                    <div class="gender_input flex radio">
                                        <input type="radio" id="gender_female" name="REGISTER[' . $FIELD . ']" value="F">
                                        <label for="gender_female">Женский пол</label>
                                    </div></div>';

									break;

								case "PERSONAL_COUNTRY":

								case "WORK_COUNTRY":

									echo '<select name="REGISTER[' . $FIELD . ']">';

									foreach ( $arResult["COUNTRIES"]["reference_id"] as $key => $value ) {

										$refselected = $value == $arResult["VALUES"][ $FIELD ] ? 'selected="selected"' : '';

										echo '<option value="' . $value . '">' . $arResult["COUNTRIES"]["reference"][ $key ] . '</option>';

									}

									echo '</select>';

									break;

								case "PERSONAL_PHOTO":

								case "WORK_LOGO":

									echo '<input size="30" type="file" name="REGISTER_FILES_' . $FIELD . '" />';

									break;

								case "PERSONAL_NOTES":

								case "WORK_NOTES":

									echo '<textarea cols="30" rows="5" name="REGISTER[' . $FIELD . ']">' . $arResult["VALUES"][ $FIELD ] . '</textarea>';

									break;

								default:

									echo '<input size="30" type="text" name="REGISTER[' . $FIELD . ']" value="' . $arResult["VALUES"][ $FIELD ] . '" />';

									break;

							}

						}
					}


					?>
                </div>

				<? // ********************* User properties ***************************************************?>
				<? if ( $arResult["USER_PROPERTIES"]["SHOW"] == "Y" ): ?>
                    <tr>
                        <td colspan="2"><?= strlen( trim( $arParams["USER_PROPERTY_NAME"] ) ) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage( "USER_TYPE_EDIT_TAB" ) ?></td>
                    </tr>
					<? foreach ( $arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField ): ?>
                        <tr>
                            <td><?= $arUserField["EDIT_FORM_LABEL"] ?>:<? if ( $arUserField["MANDATORY"] == "Y" ): ?><span class="starrequired">*</span><? endif; ?></td>
                            <td>
								<? $APPLICATION->IncludeComponent(
									"bitrix:system.field.edit",
									$arUserField["USER_TYPE"]["USER_TYPE_ID"],
									array( "bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform" ), null, array( "HIDE_ICONS" => "Y" ) ); ?></td>
                        </tr>
					<? endforeach; ?>
				<? endif; ?>
				<? // ******************** /User properties ***************************************************?>
				<?
				/* CAPTCHA */
				if ( $arResult["USE_CAPTCHA"] == "Y" ) {
					?>
                    <tr>
                        <td colspan="2"><b><?= GetMessage( "REGISTER_CAPTCHA_TITLE" ) ?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?= GetMessage( "REGISTER_CAPTCHA_PROMT" ) ?>:<span class="starrequired">*</span></td>
                        <td><input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/></td>
                    </tr>
					<?
				}
				/* !CAPTCHA */
				?>

                <input type="submit" class="btn-black register_submit_button" name="register_submit_button" style="padding: 12px 22px;margin-bottom: 100px;" value="Зарегистрироваться">

            </form>

		<? endif //$arResult["SHOW_SMS_FIELD"] == true ?>

		<? endif ?>
    </div>
</div>