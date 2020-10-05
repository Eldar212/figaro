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
<div class="bx-top-nav bx-<?= $arParams["MENU_THEME"] ?>" id="<?= $menuBlockId ?>">
    <nav class="bx-top-nav-container" id="cont_<?= $menuBlockId ?>">
        <ul class="bx-nav-list-1-lvl" id="ul_<?= $menuBlockId ?>">
			<? foreach ( $arResult["MENU_STRUCTURE"] as $itemID => $arColumns ): ?>     <!-- first level-->
				<? $existPictureDescColomn = ( $arResult["ALL_ITEMS"][ $itemID ]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][ $itemID ]["PARAMS"]["description"] ) ? true : false; ?>
                <li
                        class="bx-nav-1-lvl bx-nav-list-<?= ( $existPictureDescColomn ) ? count( $arColumns ) + 1 : count( $arColumns ) ?>-col <? if ( $arResult["ALL_ITEMS"][ $itemID ]["SELECTED"] ): ?>bx-active<? endif ?><? if ( is_array( $arColumns ) && count( $arColumns ) > 0 ): ?> bx-nav-parent<? endif ?>"
                        onmouseover="BX.CatalogMenu.itemOver(this);"
                        onmouseout="BX.CatalogMenu.itemOut(this)"
					<? if ( is_array( $arColumns ) && count( $arColumns ) > 0 ): ?>
                        data-role="bx-menu-item"
					<? endif ?>
                        onclick="if (BX.hasClass(document.documentElement, 'bx-touch')) obj_<?= $menuBlockId ?>.clickInMobile(this, event);">
                    <a
                            href="<?= $arResult["ALL_ITEMS"][ $itemID ]["LINK"] ?>"
						<? if ( is_array( $arColumns ) && count( $arColumns ) > 0 && $existPictureDescColomn ): ?>
                            onmouseover="window.obj_<?= $menuBlockId ?> && obj_<?= $menuBlockId ?>.changeSectionPicure(this, '<?= $itemID ?>');"
						<? endif ?>>
					<span>
						<?= htmlspecialcharsbx( $arResult["ALL_ITEMS"][ $itemID ]["TEXT"] ) ?>
						<? if ( is_array( $arColumns ) && count( $arColumns ) > 0 ): ?><i
                                class="fa fa-angle-down"></i><? endif ?>
					</span>
                    </a>
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