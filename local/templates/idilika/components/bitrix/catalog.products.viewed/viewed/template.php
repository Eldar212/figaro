<? if ( ! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode( true );
$this->addExternalCss( '/bitrix/css/main/bootstrap.css' );

$templateLibrary = array( 'popup' );
$currencyList    = '';

if ( ! empty( $arResult['CURRENCIES'] ) ) {
	$templateLibrary[] = 'currency';
	$currencyList      = CUtil::PhpToJSObject( $arResult['CURRENCIES'], false, true, true );
}

$templateData = array(
	'TEMPLATE_THEME'   => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES'       => $currencyList
);
unset( $currencyList, $templateLibrary );

$elementEdit         = CIBlock::GetArrayByID( $arParams['IBLOCK_ID'], 'ELEMENT_EDIT' );
$elementDelete       = CIBlock::GetArrayByID( $arParams['IBLOCK_ID'], 'ELEMENT_DELETE' );
$elementDeleteParams = array( 'CONFIRM' => GetMessage( 'CT_CPV_TPL_ELEMENT_DELETE_CONFIRM' ) );

$positionClassMap = array(
	'left'   => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right'  => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top'    => 'product-item-label-top'
);

$discountPositionClass = '';
if ( $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && ! empty( $arParams['DISCOUNT_PERCENT_POSITION'] ) ) {
	foreach ( explode( '-', $arParams['DISCOUNT_PERCENT_POSITION'] ) as $pos ) {
		$discountPositionClass .= isset( $positionClassMap[ $pos ] ) ? ' ' . $positionClassMap[ $pos ] : '';
	}
}

$labelPositionClass = '';
if ( ! empty( $arParams['LABEL_PROP_POSITION'] ) ) {
	foreach ( explode( '-', $arParams['LABEL_PROP_POSITION'] ) as $pos ) {
		$labelPositionClass .= isset( $positionClassMap[ $pos ] ) ? ' ' . $positionClassMap[ $pos ] : '';
	}
}

$arParams['~MESS_BTN_BUY']                = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_BTN_BUY' );
$arParams['~MESS_BTN_DETAIL']             = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_BTN_DETAIL' );
$arParams['~MESS_BTN_COMPARE']            = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_BTN_COMPARE' );
$arParams['~MESS_BTN_SUBSCRIBE']          = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_BTN_SUBSCRIBE' );
$arParams['~MESS_BTN_ADD_TO_BASKET']      = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_BTN_ADD_TO_BASKET' );
$arParams['~MESS_NOT_AVAILABLE']          = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage( 'CT_CPV_TPL_MESS_PRODUCT_NOT_AVAILABLE' );
$arParams['~MESS_SHOW_MAX_QUANTITY']      = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage( 'CT_CPV_CATALOG_SHOW_MAX_QUANTITY' );
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage( 'CT_CPV_CATALOG_RELATIVE_QUANTITY_MANY' );
$arParams['~MESS_RELATIVE_QUANTITY_FEW']  = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage( 'CT_CPV_CATALOG_RELATIVE_QUANTITY_FEW' );

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT'        => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE'         => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY'            => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR'     => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY'       => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY'  => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW'   => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE'               => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY'         => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE'    => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION'         => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET'     => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE'       => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP'             => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE'              => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH'                 => $arParams['COMPARE_PATH'],
	'COMPARE_NAME'                 => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION'         => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER'         => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS'         => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS'      => $discountPositionClass,
	'SLIDER_INTERVAL'              => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS'              => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL'                  => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE'            => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE'            => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE'        => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME'               => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE'       => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME'              => $arParams['DATA_LAYER_NAME'],
	'MESS_BTN_BUY'                 => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL'              => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE'             => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE'           => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET'       => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE'           => $arParams['~MESS_NOT_AVAILABLE']
);

$obName        = 'ob' . preg_replace( '/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId( $this->randString() ) );
$containerName = 'catalog-products-viewed-container';
?>
<? if ( ! empty( $arResult['ITEMS'] ) ): ?>
    <div class="favorites_block">
        <div class="container">
            <div class="flex favorites_head">
                <h2>Вы недавно смотрели</h2>
            </div>

            <div class="row">
                <div class="viewed_slide catalog-products-viewed bx-<?= $arParams['TEMPLATE_THEME'] ?>" data-entity="<?= $containerName ?>">

					<? foreach ( $arResult['ITEMS'] as $item ) {
						$uniqueId               = $item['ID'] . '_' . md5( $this->randString() . $component->getAction() );
						$areaIds[ $item['ID'] ] = $this->GetEditAreaId( $uniqueId );
						$this->AddEditAction( $uniqueId, $item['EDIT_LINK'], $elementEdit );
						$this->AddDeleteAction( $uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams );

						$my_price = "";

						$rsPrices = CPrice::GetList( array(), array( 'PRODUCT_ID' => $item['ID'], 'CATALOG_GROUP_ID' => 1 ) );
						if ( $arPrice = $rsPrices->Fetch() ) {
							$my_price = $arPrice;
						}

						$previewImage = CFile::ResizeImageGet( $item['PREVIEW_PICTURE']['ID'], array( "width" => 330, 'height' => 410 ), BX_RESIZE_IMAGE_EXACT, true );

						if ( ! isset( $previewImage['src'] ) ) {
							$previewImage = $item["PREVIEW_PICTURE"]["SRC"];
						} else {
							$previewImage = $previewImage['src'];
						}
						?>
                        <div class="view-recently col-sm-6 col-md-3">
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="preview_img_wrapper"><div class="preview_img" style="background: url(<?= $previewImage ?>)"></div></a>
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><p><?= $item['NAME'] ?></p></a>
                            <div class="flex">
                                <span class="price"><?= CurrencyFormat($my_price['PRICE'], $my_price['CURRENCY']) ?></span>
                                <div class="add_like">
                                    <a href="javascript:void(0)" class="wishbtn
                        <? if ( in_array( $arResult["ID"], $arBasketItems ) ) {
										echo 'in_wishlist ';
									} ?>"
                                       onclick="add2wish(
                                               '<?= $item["ID"] ?>',
                                               '<?= $arPrice["ID"] ?>',
                                               '<?= $my_price['PRICE'] ?>',
                                               '<?= $item["NAME"] ?>',
                                               '<?= $item["DETAIL_PAGE_URL"] ?>',
                                               this)">
                                        <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z"
                                                  stroke="#A4A4A4" stroke-width="1.5" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
						<?
//        echo "<pre>";
//            print_r($arPrice);
//            print_r($item);
//        echo "</pre>";
						?>
					<? } ?>

					<? /*
	if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
	{
		$areaIds = array();

		foreach ($arResult['ITEMS'] as $item)
		{
			$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
			$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
			$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
		}
		?>
		<!-- items-container -->
		<?
        echo "<pre>";
        print_r($arResult["ITEMS"]);
        echo "</pre>";

        foreach ($arResult['ITEM_ROWS'] as $rowData)
		{
			$rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']);
			?>
			<div class="row <?=$rowData['CLASS']?>" data-entity="items-row">
				<?
				switch ($rowData['VARIANT'])
				{
					case 0:
						?>
						<div class="col-xs-12 product-item-small-card">
							<div class="row">
								<div class="col-xs-12 product-item-big-card">
									<div class="row">
										<div class="col-md-12">
											<?
											$item = reset($rowItems);
											$APPLICATION->IncludeComponent(
												'bitrix:catalog.item',
												'',
												array(
													'RESULT' => array(
														'ITEM' => $item,
														'AREA_ID' => $areaIds[$item['ID']],
														'TYPE' => $rowData['TYPE'],
														'BIG_LABEL' => 'N',
														'BIG_DISCOUNT_PERCENT' => 'N',
														'BIG_BUTTONS' => 'N',
														'SCALABLE' => 'N'
													),
													'PARAMS' => $generalParams
														+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
												),
												$component,
												array('HIDE_ICONS' => 'Y')
											);
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?
						break;

					case 1:
						?>
						<div class="col-xs-12 product-item-small-card">
							<div class="row">
								<?
								foreach ($rowItems as $item)
								{
									?>
									<div class="col-xs-6 product-item-big-card">
										<div class="row">
											<div class="col-md-12">
												<?
												$APPLICATION->IncludeComponent(
													'bitrix:catalog.item',
													'',
													array(
														'RESULT' => array(
															'ITEM' => $item,
															'AREA_ID' => $areaIds[$item['ID']],
															'TYPE' => $rowData['TYPE'],
															'BIG_LABEL' => 'N',
															'BIG_DISCOUNT_PERCENT' => 'N',
															'BIG_BUTTONS' => 'N',
															'SCALABLE' => 'N'
														),
														'PARAMS' => $generalParams
															+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
													),
													$component,
													array('HIDE_ICONS' => 'Y')
												);
												?>
											</div>
										</div>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 2:
						?>
						<div class="col-xs-12 product-item-small-card">
							<div class="row">
								<?
								foreach ($rowItems as $item)
								{
									?>
									<div class="col-sm-4 product-item-big-card">
										<div class="row">
											<div class="col-md-12">
												<?
												$APPLICATION->IncludeComponent(
													'bitrix:catalog.item',
													'',
													array(
														'RESULT' => array(
															'ITEM' => $item,
															'AREA_ID' => $areaIds[$item['ID']],
															'TYPE' => $rowData['TYPE'],
															'BIG_LABEL' => 'N',
															'BIG_DISCOUNT_PERCENT' => 'N',
															'BIG_BUTTONS' => 'Y',
															'SCALABLE' => 'N'
														),
														'PARAMS' => $generalParams
															+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
													),
													$component,
													array('HIDE_ICONS' => 'Y')
												);
												?>
											</div>
										</div>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 3:
						?>
						<div class="col-xs-12 product-item-small-card">
							<div class="row">
								<?
								foreach ($rowItems as $item)
								{
									?>
									<div class="col-xs-6 col-md-3">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $item,
													'AREA_ID' => $areaIds[$item['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 4:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-sm-6 product-item-big-card">
							<div class="row">
								<div class="col-md-12">
									<?
									$item = array_shift($rowItems);
									$APPLICATION->IncludeComponent(
										'bitrix:catalog.item',
										'',
										array(
											'RESULT' => array(
												'ITEM' => $item,
												'AREA_ID' => $areaIds[$item['ID']],
												'TYPE' => $rowData['TYPE'],
												'BIG_LABEL' => 'N',
												'BIG_DISCOUNT_PERCENT' => 'N',
												'BIG_BUTTONS' => 'Y',
												'SCALABLE' => 'Y'
											),
											'PARAMS' => $generalParams
												+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
										),
										$component,
										array('HIDE_ICONS' => 'Y')
									);
									unset($item);
									?>
								</div>
							</div>
						</div>
						<div class="col-sm-6 product-item-small-card">
							<div class="row">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-xs-6">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 5:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-sm-6 product-item-small-card">
							<div class="row">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-xs-6">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<div class="col-sm-6 product-item-big-card">
							<div class="row">
								<div class="col-md-12">
									<?
									$item = end($rowItems);
									$APPLICATION->IncludeComponent(
										'bitrix:catalog.item',
										'',
										array(
											'RESULT' => array(
												'ITEM' => $item,
												'AREA_ID' => $areaIds[$item['ID']],
												'TYPE' => $rowData['TYPE'],
												'BIG_LABEL' => 'N',
												'BIG_DISCOUNT_PERCENT' => 'N',
												'BIG_BUTTONS' => 'Y',
												'SCALABLE' => 'Y'
											),
											'PARAMS' => $generalParams
												+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
										),
										$component,
										array('HIDE_ICONS' => 'Y')
									);
									unset($item);
									?>
								</div>
							</div>
						</div>
						<?
						break;

					case 6:
						?>
						<div class="col-xs-12 product-item-small-card">
							<div class="row">
								<?
								foreach ($rowItems as $item)
								{
									?>
									<div class="col-xs-6 col-sm-4 col-md-2">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $item,
													'AREA_ID' => $areaIds[$item['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 7:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-sm-6 product-item-big-card">
							<div class="row">
								<div class="col-md-12">
									<?
									$item = array_shift($rowItems);
									$APPLICATION->IncludeComponent(
										'bitrix:catalog.item',
										'',
										array(
											'RESULT' => array(
												'ITEM' => $item,
												'AREA_ID' => $areaIds[$item['ID']],
												'TYPE' => $rowData['TYPE'],
												'BIG_LABEL' => 'N',
												'BIG_DISCOUNT_PERCENT' => 'N',
												'BIG_BUTTONS' => 'Y',
												'SCALABLE' => 'Y'
											),
											'PARAMS' => $generalParams
												+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
										),
										$component,
										array('HIDE_ICONS' => 'Y')
									);
									unset($item);
									?>
								</div>
							</div>
						</div>
						<div class="col-sm-6 product-item-small-card">
							<div class="row">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-xs-6 col-md-4">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 8:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-sm-6 product-item-small-card">
							<div class="row">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-xs-6 col-md-4">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N',
													'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<div class="col-sm-6 product-item-big-card">
							<div class="row">
								<div class="col-md-12">
									<?
									$item = end($rowItems);
									$APPLICATION->IncludeComponent(
										'bitrix:catalog.item',
										'',
										array(
											'RESULT' => array(
												'ITEM' => $item,
												'AREA_ID' => $areaIds[$item['ID']],
												'TYPE' => $rowData['TYPE'],
												'BIG_LABEL' => 'N',
												'BIG_DISCOUNT_PERCENT' => 'N',
												'BIG_BUTTONS' => 'Y',
												'SCALABLE' => 'Y'
											),
											'PARAMS' => $generalParams
												+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
										),
										$component,
										array('HIDE_ICONS' => 'Y')
									);
									unset($item);
									?>
								</div>
							</div>
						</div>
						<?
						break;

					case 9:
						?>
						<div class="col-xs-12">
							<div class="row">
								<?
								foreach ($rowItems as $item)
								{
									?>
									<div class="col-xs-12 product-line-item-card">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'',
											array(
												'RESULT' => array(
													'ITEM' => $item,
													'AREA_ID' => $areaIds[$item['ID']],
													'TYPE' => $rowData['TYPE'],
													'BIG_LABEL' => 'N',
													'BIG_DISCOUNT_PERCENT' => 'N',
													'BIG_BUTTONS' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>

							</div>
						</div>
						<?
						break;
				}
				?>
			</div>
			<?
		}
		unset($generalParams, $rowItems);
		?>
		<!-- items-container -->
		<?
	}
	else
	{
		// load css for bigData/deferred load
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.item',
			'',
			array(),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}*/
					?>
                </div>


            </div>
        </div>
    </div>
<? endif; ?>

<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS( 'CT_CPV_CATALOG_BTN_MESSAGE_BASKET_REDIRECT' )?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS( 'ADD_TO_BASKET_OK' )?>',
        TITLE_ERROR: '<?=GetMessageJS( 'CT_CPV_CATALOG_TITLE_ERROR' )?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS( 'CT_CPV_CATALOG_TITLE_BASKET_PROPS' )?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS( 'ADD_TO_BASKET_OK' )?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS( 'CT_CPV_CATALOG_BASKET_UNKNOWN_ERROR' )?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS( 'CT_CPV_CATALOG_BTN_MESSAGE_SEND_PROPS' )?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS( 'CT_CPV_CATALOG_BTN_MESSAGE_CLOSE' )?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS( 'CT_CPV_CATALOG_BTN_MESSAGE_CLOSE_POPUP' )?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS( 'CT_CPV_CATALOG_MESS_COMPARE_OK' )?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS( 'CT_CPV_CATALOG_MESS_COMPARE_UNKNOWN_ERROR' )?>',
        COMPARE_TITLE: '<?=GetMessageJS( 'CT_CPV_CATALOG_MESS_COMPARE_TITLE' )?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS( 'CT_CPV_CATALOG_PRICE_TOTAL_PREFIX' )?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape( $arParams['MESS_RELATIVE_QUANTITY_MANY'] )?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape( $arParams['MESS_RELATIVE_QUANTITY_FEW'] )?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS( 'CT_CPV_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT' )?>',
        SITE_ID: '<?=CUtil::JSEscape( $component->getSiteId() )?>'
    });
    var <?=$obName?> =
    new JCCatalogProductsViewedComponent({
        initiallyShowHeader: '<?=! empty( $arResult['ITEM_ROWS'] )?>',
        container: '<?=$containerName?>'
    });
</script>