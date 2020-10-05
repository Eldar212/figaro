<? if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode( true );

if ( empty( $arResult["ALL_ITEMS"] ) ) {
	return;
}

CUtil::InitJSCore();

$menuBlockId = "catalog_menu_" . $this->randString();

?>
<div class="bx-top-nav" id="<?= $menuBlockId ?>">
    <nav class="bx-top-nav-container" id="cont_<?= $menuBlockId ?>">
        <ul class="bx-nav-list-1-lvl" id="ul_<?= $menuBlockId ?>">

            <li class="bx-nav-1-lvl bx-nav-list highlighted"
                onmouseover="BX.CatalogMenu.itemOver(this);"
                onmouseout="BX.CatalogMenu.itemOut(this)"
                data-role="bx-menu-item"
                onclick="if (BX.hasClass(document.documentElement, 'bx-touch')) obj_<?= $menuBlockId ?>.clickInMobile(this, event);">

                <a href="/new/<?= !empty($_GET['GENDER']) ? $_GET['GENDER'] : 'woman' ?>/">Новинки</a>
            </li>

			<? foreach ( $arResult["ALL_ITEMS"] as $itemID => $itemLink ): ?>     <!-- first level-->
                <li class="bx-nav-1-lvl bx-nav-list"
                    onmouseover="BX.CatalogMenu.itemOver(this);"
                    onmouseout="BX.CatalogMenu.itemOut(this)"
                    data-role="bx-menu-item"
                    onclick="if (BX.hasClass(document.documentElement, 'bx-touch')) obj_<?= $menuBlockId ?>.clickInMobile(this, event);">

                    <a href="<?= $itemLink["LINK"] ?>"><?= $itemLink["NAME"] ?></a>
                </li>
			<? endforeach; ?>
        </ul>
        <div style="clear: both;"></div>
    </nav>
</div>

<script>
    BX.ready(function () {
        window.obj_<?=$menuBlockId?> = new BX.Main.Menu.CatalogHorizontal('<?=CUtil::JSEscape( $menuBlockId )?>', <?=CUtil::PhpToJSObject( $arResult["ITEMS_IMG_DESC"] )?>);
    });
</script>