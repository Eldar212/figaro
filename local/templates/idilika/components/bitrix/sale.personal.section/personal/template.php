<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);

$availablePages = array();

?>
<style>.back_link{display: none}</style>
<div class="row">
    <div class="col-md-12 sale-personal-section-index">
        <div class="row personal-menu-mobile sale-personal-section-row-flex">
            <?
            include( Main\Application::getDocumentRoot() . $templateFolder . '/private.php' );
            ?>
        </div>
    </div>
</div>

