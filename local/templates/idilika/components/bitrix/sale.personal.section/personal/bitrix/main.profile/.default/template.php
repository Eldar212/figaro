<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

?>
<div class="flex">
<?/*<pre><?print_r($arResult)?></pre>*/?>
    <? $APPLICATION->IncludeFile(
        SITE_DIR."personal/left_menu.php",
        array(
                'SHOW_MENU' => $show_menu
        ),
        array()
    ); ?>

    <div class="bx_profile right_personal">
        <?
        ShowError($arResult["strProfileError"]);

        if ($arResult['DATA_SAVED'] == 'Y')
        {
            ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
        }

        ?>
        <form method="post" name="form1" action="<?=$APPLICATION->GetCurUri()?>" enctype="multipart/form-data" role="form">
            <?=$arResult["BX_SESSION_CHECK"]?>
            <input type="hidden" name="lang" value="<?=LANG?>" />
            <input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
            <input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>" />
            <div class="main-profile-block-shown" id="user_div_reg">
                <?/*<div class="main-profile-block-date-info">
                    <?
                    if($arResult["ID"]>0)
                    {
                        if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
                        {
                            ?>
                            <div class="col-sm-9 col-md-offset-3 small">
                                <strong><?=Loc::getMessage('LAST_UPDATE')?></strong>
                                <strong><?=$arResult["arUser"]["TIMESTAMP_X"]?></strong>
                            </div>
                            <?
                        }

                        if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
                        {
                            ?>
                            <div class="col-sm-9 col-md-offset-3 small">
                                <strong><?=Loc::getMessage('LAST_LOGIN')?></strong>
                                <strong><?=$arResult["arUser"]["LAST_LOGIN"]?></strong>
                            </div>
                            <?
                        }
                    }
                    ?>
                </div>*/?>
                <?/*
                if (!in_array(LANGUAGE_ID,array('ru', 'ua')))
                {
                    ?>
                    <div class="form-group">
                        <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-title"><?=Loc::getMessage('main_profile_title')?></label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="TITLE" maxlength="50" id="main-profile-title" value="<?=$arResult["arUser"]["TITLE"]?>" />
                        </div>
                    </div>
                    <?
                }*/
                ?>
                <div class="flex">

                    <div class="form-group min_form">
    <!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-name">--><?//=Loc::getMessage('NAME')?><!--</label>-->
                        <div class="">
                            <input class="form-control" type="text" name="NAME" maxlength="50" id="main-profile-name" value="<?=$arResult["arUser"]["NAME"]?>" />
                        </div>
                    </div>
<!--                    <div class="form-group min_form">-->
<!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-last-name">--><?//=Loc::getMessage('LAST_NAME')?><!--</label>-->
<!--                        <div class="">-->
<!--                            <input class="form-control" type="text" name="LAST_NAME" maxlength="50" id="main-profile-last-name" value="--><?//=$arResult["arUser"]["LAST_NAME"]?><!--" />-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group min_form">-->
<!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-second-name">--><?//=Loc::getMessage('SECOND_NAME')?><!--</label>-->
<!--                        <div class="">-->
<!--                            <input class="form-control" type="text" name="SECOND_NAME" maxlength="50" id="main-profile-second-name" value="--><?//=$arResult["arUser"]["SECOND_NAME"]?><!--" />-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="form-group min_form">
    <!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-email">--><?//=Loc::getMessage('EMAIL')?><!--</label>-->
                        <div class="">
                            <input class="form-control" type="text" name="EMAIL" maxlength="50" id="main-profile-email" value="<?=$arResult["arUser"]["EMAIL"]?>" />
                        </div>
                    </div>
                    <div class="form-group min_form">
    <!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-phone">--><?//=Loc::getMessage('PERSONAL_PHONE')?><!--</label>-->
                        <div class="">
                            <input class="form-control" type="text" name="PERSONAL_PHONE" maxlength="50" id="main-profile-phone" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
                        </div>
                    </div>
                    <?
                    $origDate = $arResult["arUser"]["PERSONAL_BIRTHDAY"];
                    $date = str_replace('.', '-', $origDate );
                    $newDate = date("Y-m-d", strtotime($date));
                    ?>
                    <div class="form-group min_form">
    <!--                    <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-phone">--><?//=Loc::getMessage('PERSONAL_BIRTHDAY')?><!--</label>-->
                        <div class="">
                            <input class="form-control" type="date" maxlength="50" id="main-profile-date" value="<?=$newDate?>" />
                            <input type="hidden" name="PERSONAL_BIRTHDAY" id="newdate" value='<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>'>
                        </div>
                    </div>
                    <script>
                        $('#main-profile-date').change(function () {
                            // console.log($(this).val());
                            var dateAr = $(this).val().split('-');
                            var newDate = dateAr[2] + '.' + dateAr[1] + '.' + dateAr[0];
                            $('#newdate').val(newDate);
                        });
                    </script>
                </div>
                <span class="head_change_pass">Выберите пол</span>
                <div class="flex flex-gender">
                    <div class="form-group min_form_gender">
                        <div class="gender_input flex radio">
                            <input class="form-control" type="radio" name="PERSONAL_GENDER" maxlength="50" id="gender_m" value="M" <?= $arResult["arUser"]["PERSONAL_GENDER"] == 'M' ? 'checked' : ''?>/>
                            <label for="gender_m">Мужской пол</label>
                        </div>
                    </div>
                    <div class="form-group min_form_gender">
                        <div class="gender_input flex radio">
                            <input class="form-control" type="radio" name="PERSONAL_GENDER" maxlength="50" id="gender_f" value="F" <?= $arResult["arUser"]["PERSONAL_GENDER"] == 'F' ? 'checked' : ''?>/>
                            <label for="gender_f">Женский пол</label>
                        </div>
                    </div>
                </div>
                <?
                if ($arResult['CAN_EDIT_PASSWORD'])
                {
                    ?>
                    <?/*<div class="form-group">
                        <p class="main-profile-form-password-annotation col-sm-9 col-sm-offset-3 small">
                            <?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
                        </p>
                    </div>*/?>
                    <span class="head_change_pass">Смена пароля</span>
                    <div class="flex">
                        <div class="form-group min_form">
                            <!--                        <label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-password">--><?//=Loc::getMessage('NEW_PASSWORD_REQ')?><!--</label>-->
                            <div class="">
                                <input class="form-control bx-auth-input main-profile-password" type="password" name="NEW_PASSWORD" maxlength="50" id="main-profile-password" value="" placeholder="Новый пароль" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="form-group min_form">
                            <!--                        <label class="main-profile-form-label main-profile-password col-sm-12 col-md-3 text-md-right" for="main-profile-password-confirm">-->
                            <!--                            --><?//=Loc::getMessage('NEW_PASSWORD_CONFIRM')?>
                            <!--                        </label>-->
                            <div class="">
                                <input class="form-control" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" id="main-profile-password-confirm" placeholder="Повторите пароль" autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <?
                }
                ?>
            </div>
<!--            <p class="main-profile-form-buttons-block col-sm-9 col-md-offset-3">-->
                <input type="submit" name="save" class="btn btn-themes btn-default btn-md main-profile-submit" value="<?=(($arResult["ID"]>0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>">
                &nbsp;
<!--                <input type="submit" class="btn btn-themes btn-default btn-md"  name="reset" value="--><?//echo GetMessage("MAIN_RESET")?><!--">-->
<!--            </p>-->
        </form>
        <?/*<div class="col-sm-12 main-profile-social-block">
            <?
            if ($arResult["SOCSERV_ENABLED"])
            {
                $APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
                    "SHOW_PROFILES" => "Y",
                    "ALLOW_DELETE" => "Y"
                ),
                    false
                );
            }
            ?>
        </div>*/?>
        <div class="clearfix"></div>
    </div>
</div>