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
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode( true );
$this->addExternalCss( '/bitrix/css/main/bootstrap.css' );
$this->addExternalCss( SITE_TEMPLATE_PATH . '/assets/libs/fancybox/fancybox.min.css' );
$this->addExternalJs( SITE_TEMPLATE_PATH . '/assets/libs/fancybox/fancybox.min.js' );

//$templateLibrary = array( 'popup', 'fx' );
$currencyList    = '';

if ( ! empty( $arResult['CURRENCIES'] ) ) {

	foreach ( $arResult['CURRENCIES'] as &$currency ) {
		if ( $currency['CURRENCY'] == 'RUB' ) {
			$currency['FORMAT']['FORMAT_STRING'] = '# ₽';
		}
	}

	$templateLibrary[] = 'currency';
	$currencyList      = CUtil::PhpToJSObject( $arResult['CURRENCIES'], false, true, true );
}

$templateData = array(
	'TEMPLATE_THEME'   => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES'       => $currencyList,
	'ITEM'             => array(
		'ID'              => $arResult['ID'],
		'IBLOCK_ID'       => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS'       => $arResult['JS_OFFERS']
	)
);
unset( $currencyList, $templateLibrary );

$mainId  = $this->GetEditAreaId( $arResult['ID'] );
$itemIds = array(
	'ID'                    => $mainId,
	'DISCOUNT_PERCENT_ID'   => $mainId . '_dsc_pict',
	'STICKER_ID'            => $mainId . '_sticker',
	'BIG_SLIDER_ID'         => $mainId . '_big_slider',
	'BIG_IMG_CONT_ID'       => $mainId . '_bigimg_cont',
	'SLIDER_CONT_ID'        => $mainId . '_slider_cont',
	'OLD_PRICE_ID'          => $mainId . '_old_price',
	'PRICE_ID'              => $mainId . '_price',
	'DISCOUNT_PRICE_ID'     => $mainId . '_price_discount',
	'PRICE_TOTAL'           => $mainId . '_price_total',
	'SLIDER_CONT_OF_ID'     => $mainId . '_slider_cont_',
	'QUANTITY_ID'           => $mainId . '_quantity',
	'QUANTITY_DOWN_ID'      => $mainId . '_quant_down',
	'QUANTITY_UP_ID'        => $mainId . '_quant_up',
	'QUANTITY_MEASURE'      => $mainId . '_quant_measure',
	'QUANTITY_LIMIT'        => $mainId . '_quant_limit',
	'BUY_LINK'              => $mainId . '_buy_link',
	'ADD_BASKET_LINK'       => $mainId . '_add_basket_link',
	'BASKET_ACTIONS_ID'     => $mainId . '_basket_actions',
	'NOT_AVAILABLE_MESS'    => $mainId . '_not_avail',
	'COMPARE_LINK'          => $mainId . '_compare_link',
	'TREE_ID'               => $mainId . '_skudiv',
	'DISPLAY_PROP_DIV'      => $mainId . '_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
	'OFFER_GROUP'           => $mainId . '_set_group_',
	'BASKET_PROP_DIV'       => $mainId . '_basket_prop',
	'SUBSCRIBE_LINK'        => $mainId . '_subscribe',
	'TABS_ID'               => $mainId . '_tabs',
	'TAB_CONTAINERS_ID'     => $mainId . '_tab_containers',
	'SMALL_CARD_PANEL_ID'   => $mainId . '_small_card_panel',
	'TABS_PANEL_ID'         => $mainId . '_tabs_panel'
);
$obName  = $templateData['JS_OBJ'] = 'ob' . preg_replace( '/[^a-zA-Z0-9_]/', 'x', $mainId );
$name    = ! empty( $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] )
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title   = ! empty( $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] )
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt     = ! empty( $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] )
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = ! empty( $arResult['OFFERS'] );
if ( $haveOffers ) {
	$actualItem         = isset( $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ] )
		? $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ]
		: reset( $arResult['OFFERS'] );
	$showSliderControls = false;

	foreach ( $arResult['OFFERS'] as $offer ) {
		if ( $offer['MORE_PHOTO_COUNT'] > 1 ) {
			$showSliderControls = true;
			break;
		}
	}
} else {
	$actualItem         = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps     = array();
$price        = $actualItem['ITEM_PRICES'][ $actualItem['ITEM_PRICE_SELECTED'] ];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][ $actualItem['ITEM_MEASURE_RATIO_SELECTED'] ]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription     = ! empty( $arResult['PREVIEW_TEXT'] ) || ! empty( $arResult['DETAIL_TEXT'] );
$showBuyBtn          = in_array( 'BUY', $arParams['ADD_TO_BASKET_ACTION'] );
$buyButtonClassName  = in_array( 'BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY'] ) ? 'btn-default' : 'btn-link';
$showAddBtn          = in_array( 'ADD', $arParams['ADD_TO_BASKET_ACTION'] );
$showButtonClassName = in_array( 'ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY'] ) ? 'btn-default' : 'btn-link';
$showSubscribe       = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ( $arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers );

$arParams['MESS_BTN_BUY']                = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage( 'CT_BCE_CATALOG_BUY' );
$arParams['MESS_BTN_ADD_TO_BASKET']      = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage( 'CT_BCE_CATALOG_ADD' );
$arParams['MESS_NOT_AVAILABLE']          = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage( 'CT_BCE_CATALOG_NOT_AVAILABLE' );
$arParams['MESS_BTN_COMPARE']            = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage( 'CT_BCE_CATALOG_COMPARE' );
$arParams['MESS_PRICE_RANGES_TITLE']     = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage( 'CT_BCE_CATALOG_PRICE_RANGES_TITLE' );
$arParams['MESS_DESCRIPTION_TAB']        = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage( 'CT_BCE_CATALOG_DESCRIPTION_TAB' );
$arParams['MESS_PROPERTIES_TAB']         = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage( 'CT_BCE_CATALOG_PROPERTIES_TAB' );
$arParams['MESS_COMMENTS_TAB']           = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage( 'CT_BCE_CATALOG_COMMENTS_TAB' );
$arParams['MESS_SHOW_MAX_QUANTITY']      = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage( 'CT_BCE_CATALOG_SHOW_MAX_QUANTITY' );
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage( 'CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY' );
$arParams['MESS_RELATIVE_QUANTITY_FEW']  = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage( 'CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW' );

$positionClassMap = array(
	'left'   => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right'  => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top'    => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ( $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && ! empty( $arParams['DISCOUNT_PERCENT_POSITION'] ) ) {
	foreach ( explode( '-', $arParams['DISCOUNT_PERCENT_POSITION'] ) as $pos ) {
		$discountPositionClass .= isset( $positionClassMap[ $pos ] ) ? ' ' . $positionClassMap[ $pos ] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if ( ! empty( $arParams['LABEL_PROP_POSITION'] ) ) {
	foreach ( explode( '-', $arParams['LABEL_PROP_POSITION'] ) as $pos ) {
		$labelPositionClass .= isset( $positionClassMap[ $pos ] ) ? ' ' . $positionClassMap[ $pos ] : '';
	}
}

?>

    <div class="bx-catalog-element bx-<?= $arParams['TEMPLATE_THEME'] ?>" id="<?= $itemIds['ID'] ?>"
         itemscope itemtype="http://schema.org/Product">
        <div class="container-fluid name_slide_block">
			<?
			if ( $arParams['DISPLAY_NAME'] === 'Y' ) {
				?>
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="bx-title"><?= $name ?></h1>
                    </div>
                </div>
				<?
			}
			?>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="product-item-detail-slider-container flex" id="<?= $itemIds['BIG_SLIDER_ID'] ?>">
						<?
						if ( $showSliderControls ) {
							if ( $haveOffers ) {
								foreach ( $arResult['OFFERS'] as $keyOffer => $offer ) {
									if ( ! isset( $offer['MORE_PHOTO_COUNT'] ) || $offer['MORE_PHOTO_COUNT'] <= 1 ) {
										continue;
									}

									$offer_hide = $actualItem['ID'] != $offer['ID'] ? 'style="display:none"' : '';
									?>
                                    <div class="product-item-detail-slider-controls-block" id="<?= $itemIds['SLIDER_CONT_OF_ID'] . $offer['ID'] ?>" <?= $offer_hide ?>>
                                        <div class="flex">
											<?
											foreach ( $offer['MORE_PHOTO'] as $keyPhoto => $photo ) {
												?>
                                                <div class="product-item-detail-slider-controls-image<?= ( $keyPhoto == 0 ? ' active' : '' ) ?>"
                                                     data-entity="slider-control" data-value="<?= $offer['ID'] . '_' . $photo['ID'] ?>">
                                                    <img src="<?= $photo['SRC'] ?>">
                                                </div>
												<?
											}
											?>
                                        </div>
                                    </div>
									<?
								}
							} else {
								?>
                                <div class="product-item-detail-slider-controls-block" id="<?= $itemIds['SLIDER_CONT_ID'] ?>">
                                    <div class="flex">
										<?
										if ( ! empty( $actualItem['MORE_PHOTO'] ) ) {
											foreach ( $actualItem['MORE_PHOTO'] as $key => $photo ) {
												?>
                                                <div class="product-item-detail-slider-controls-image<?= ( $key == 0 ? ' active' : '' ) ?>"
                                                     data-entity="slider-control" data-value="<?= $photo['ID'] ?>">
                                                    <img src="<?= $photo['SRC'] ?>">
                                                </div>
												<?
											}
										}
										?>
                                    </div>
                                </div>
								<?
							}
						}
						?>
                        <div class="product-item-detail-slider-block <?= ( $arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '' ) ?>"
                             data-entity="images-slider-block">
                            <span class="product-item-detail-slider-left" data-entity="slider-control-left" style="display: none;"></span>
                            <span class="product-item-detail-slider-right" data-entity="slider-control-right" style="display: none;"></span>
                            <div class="product-item-label-text <?= $labelPositionClass ?>" id="<?= $itemIds['STICKER_ID'] ?>"
								<?= ( ! $arResult['LABEL'] ? 'style="display: none;"' : '' ) ?>>
								<?
								if ( $arResult['LABEL'] && ! empty( $arResult['LABEL_ARRAY_VALUE'] ) ) {
									foreach ( $arResult['LABEL_ARRAY_VALUE'] as $code => $value ) {
										?>
                                        <div<?= ( ! isset( $arParams['LABEL_PROP_MOBILE'][ $code ] ) ? ' class="hidden-xs"' : '' ) ?>>
                                            <span title="<?= $value ?>"><?= $value ?></span>
                                        </div>
										<?
									}
								}
								?>
                            </div>
							<?
							if ( $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' ) {
								if ( $haveOffers ) {
									?>
                                    <div class="product-item-label-ring <?= $discountPositionClass ?>" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                         style="display: none;">
                                    </div>
									<?
								} else {
									if ( $price['DISCOUNT'] > 0 ) {
										?>
                                        <div class="product-item-label-ring <?= $discountPositionClass ?>" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                             title="<?= - $price['PERCENT'] ?>%">
                                            <span><?= - $price['PERCENT'] ?>%</span>
                                        </div>
										<?
									}
								}
							}
							?>
                            <div class="product-item-detail-slider-images-container" data-entity="images-container">
								<?
								if ( ! empty( $actualItem['MORE_PHOTO'] ) ) {
									foreach ( $actualItem['MORE_PHOTO'] as $key => $photo ) {
										?>
                                        <div class="product-item-detail-slider-image<?= ( $key == 0 ? ' active' : '' ) ?>" data-entity="image" data-id="<?= $photo['ID'] ?>">
	                                        <a href="<?= $photo['SRC'] ?>" data-fancybox="product-slider"><img src="<?= $photo['SRC'] ?>" alt="<?= $alt ?>" title="<?= $title ?>"<?= ( $key == 0 ? ' itemprop="image"' : '' ) ?>></a>
                                        </div>
										<?
									}
								}

								if ( $arParams['SLIDER_PROGRESS'] === 'Y' ) {
									?>
                                    <div class="product-item-detail-slider-progress-bar" data-entity="slider-progress-bar" style="width: 0;"></div>
									<?
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div>
                        <div class=""> <? // col-sm-6 ?>
                            <div class="product-item-detail-info-section">
								<? /*
							foreach ($arParams['PRODUCT_INFO_BLOCK_ORDER'] as $blockName)
							{
								switch ($blockName)
								{
									case 'sku':
										if ($haveOffers && !empty($arResult['OFFERS_PROP']))
										{
											?>
											<div id="<?=$itemIds['TREE_ID']?>">
												<?
												foreach ($arResult['SKU_PROPS'] as $skuProperty)
												{
													if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
														continue;

													$propertyId = $skuProperty['ID'];
													$skuProps[] = array(
														'ID' => $propertyId,
														'SHOW_MODE' => $skuProperty['SHOW_MODE'],
														'VALUES' => $skuProperty['VALUES'],
														'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
													);
													?>
													<div class="product-item-detail-info-container" data-entity="sku-line-block">
														<div class="product-item-detail-info-container-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?></div>
														<div class="product-item-scu-container">
															<div class="product-item-scu-block">
																<div class="product-item-scu-list">
																	<ul class="product-item-scu-item-list">
																		<?
																		foreach ($skuProperty['VALUES'] as &$value)
																		{
																			$value['NAME'] = htmlspecialcharsbx($value['NAME']);

																			if ($skuProperty['SHOW_MODE'] === 'PICT')
																			{
																				?>
																				<li class="product-item-scu-item-color-container" title="<?=$value['NAME']?>"
																					data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
																					data-onevalue="<?=$value['ID']?>">
																					<div class="product-item-scu-item-color-block">
																						<div class="product-item-scu-item-color" title="<?=$value['NAME']?>"
																							style="background-image: url('<?=$value['PICT']['SRC']?>');">
																						</div>
																					</div>
																				</li>
																				<?
																			}
																			else
																			{
																				?>
																				<li class="product-item-scu-item-text-container" title="<?=$value['NAME']?>"
																					data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
																					data-onevalue="<?=$value['ID']?>">
																					<div class="product-item-scu-item-text-block">
																						<div class="product-item-scu-item-text"><?=$value['NAME']?></div>
																					</div>
																				</li>
																				<?
																			}
																		}
																		?>
																	</ul>
																	<div style="clear: both;"></div>
																</div>
															</div>
														</div>
													</div>
													<?
												}
												?>
											</div>
											<?
										}

										break;

									case 'props':
										if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
										{
											?>
											<div class="product-item-detail-info-container">
												<?
												if (!empty($arResult['DISPLAY_PROPERTIES']))
												{
													?>
													<dl class="product-item-detail-properties">
														<?
														foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
														{
															if (isset($arParams['MAIN_BLOCK_PROPERTY_CODE'][$property['CODE']]))
															{
																?>
																<dt><?=$property['NAME']?></dt>
																<dd><?=(is_array($property['DISPLAY_VALUE'])
																		? implode(' / ', $property['DISPLAY_VALUE'])
																		: $property['DISPLAY_VALUE'])?>
																</dd>
																<?
															}
														}
														unset($property);
														?>
													</dl>
													<?
												}

												if ($arResult['SHOW_OFFERS_PROPS'])
												{
													?>
													<dl class="product-item-detail-properties" id="<?=$itemIds['DISPLAY_MAIN_PROP_DIV']?>"></dl>
													<?
												}
												?>
											</div>
											<?
										}

										break;
								}
							}*/
								?>
                            </div>
                        </div>
                        <div class=""> <? // col-sm-6 ?>
                            <div class="product-item-detail-pay-block">

                                <span class="artnumber">Код товара: <span><span <?
                                                                                if ( $arResult['SHOW_OFFERS_PROPS'] ) { ?>id="<?= $itemIds['DISPLAY_PROP_DIV'] ?>"<?
		                                } ?>><?= $arResult['PROPERTIES']['ARTNUMBER']['VALUE'] ?></span></span></span>
                                <h1 class="product-title"><?= $arResult['NAME'] ?></h1>
								<?

								foreach ( $arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName ) {
									switch ( $blockName ) {
										/*case 'rating':
											if ($arParams['USE_VOTE_RATING'] === 'Y')
											{
												?>
												<div class="product-item-detail-info-container">
													<?
													$APPLICATION->IncludeComponent(
														'bitrix:iblock.vote',
														'stars',
														array(
															'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
															'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
															'IBLOCK_ID' => $arParams['IBLOCK_ID'],
															'ELEMENT_ID' => $arResult['ID'],
															'ELEMENT_CODE' => '',
															'MAX_VOTE' => '5',
															'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
															'SET_STATUS_404' => 'N',
															'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
															'CACHE_TYPE' => $arParams['CACHE_TYPE'],
															'CACHE_TIME' => $arParams['CACHE_TIME']
														),
														$component,
														array('HIDE_ICONS' => 'Y')
													);
													?>
												</div>
												<?
											}

											break;*/

										case 'price':
											?>
                                            <div class="product-item-detail-info-container">
												<?
												if ( $arParams['SHOW_OLD_PRICE'] === 'Y' ) {
													?>
                                                    <div class="product-item-detail-price-old" id="<?= $itemIds['OLD_PRICE_ID'] ?>"
                                                         style="display: <?= ( $showDiscount ? '' : 'none' ) ?>;">
														<?= ( $showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '' ) ?>
                                                    </div>
													<?
												}
												?>
                                                <div class="product-item-detail-price-current" id="<?= $itemIds['PRICE_ID'] ?>">
													<?= $price['PRINT_RATIO_PRICE'] ?>
                                                </div>
												<?
												if ( $arParams['SHOW_OLD_PRICE'] === 'Y' ) {
													?>
                                                    <div class="item_economy_price" id="<?= $itemIds['DISCOUNT_PRICE_ID'] ?>"
                                                         style="display: <?= ( $showDiscount ? '' : 'none' ) ?>;">
														<?
														if ( $showDiscount ) {
															echo Loc::getMessage( 'CT_BCE_CATALOG_ECONOMY_INFO2', array( '#ECONOMY#' => $price['PRINT_RATIO_DISCOUNT'] ) );
														}
														?>
                                                    </div>
													<?
												}
												?>
                                            </div>
											<?
											break;

										case 'priceRanges':
											if ( $arParams['USE_PRICE_COUNT'] ) {
												$showRanges = ! $haveOffers && count( $actualItem['ITEM_QUANTITY_RANGES'] ) > 1;
												$useRatio   = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
												?>
                                                <div class="product-item-detail-info-container"
													<?= $showRanges ? '' : 'style="display: none;"' ?>
                                                     data-entity="price-ranges-block">
                                                    <div class="product-item-detail-info-container-title">
														<?= $arParams['MESS_PRICE_RANGES_TITLE'] ?>
                                                        <span data-entity="price-ranges-ratio-header">
														(<?= ( Loc::getMessage(
																'CT_BCE_CATALOG_RATIO_PRICE',
																array( '#RATIO#' => ( $useRatio ? $measureRatio : '1' ) . ' ' . $actualItem['ITEM_MEASURE']['TITLE'] )
															) ) ?>)
													</span>
                                                    </div>
                                                    <dl class="product-item-detail-properties" data-entity="price-ranges-body">
														<?
														if ( $showRanges ) {
															foreach ( $actualItem['ITEM_QUANTITY_RANGES'] as $range ) {
																if ( $range['HASH'] !== 'ZERO-INF' ) {
																	$itemPrice = false;

																	foreach ( $arResult['ITEM_PRICES'] as $itemPrice ) {
																		if ( $itemPrice['QUANTITY_HASH'] === $range['HASH'] ) {
																			break;
																		}
																	}

																	if ( $itemPrice ) {
																		?>
                                                                        <dt>
																			<?
																			echo Loc::getMessage(
																					'CT_BCE_CATALOG_RANGE_FROM',
																					array( '#FROM#' => $range['SORT_FROM'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'] )
																				) . ' ';

																			if ( is_infinite( $range['SORT_TO'] ) ) {
																				echo Loc::getMessage( 'CT_BCE_CATALOG_RANGE_MORE' );
																			} else {
																				echo Loc::getMessage(
																					'CT_BCE_CATALOG_RANGE_TO',
																					array( '#TO#' => $range['SORT_TO'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'] )
																				);
																			}
																			?>
                                                                        </dt>
                                                                        <dd><?= ( $useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'] ) ?></dd>
																		<?
																	}
																}
															}
														}
														?>
                                                    </dl>
                                                </div>
												<?
												unset( $showRanges, $useRatio, $itemPrice, $range );
											}

											break;

										/*case 'quantityLimit':
											if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
											{
												if ($haveOffers)
												{
													?>
													<div class="product-item-detail-info-container" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
														<div class="product-item-detail-info-container-title">
															<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
															<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
														</div>
													</div>
													<?
												}
												else
												{
													if (
														$measureRatio
														&& (float)$actualItem['CATALOG_QUANTITY'] > 0
														&& $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
														&& $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
													)
													{
														?>
														<div class="product-item-detail-info-container" id="<?=$itemIds['QUANTITY_LIMIT']?>">
															<div class="product-item-detail-info-container-title">
																<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
																<span class="product-item-quantity" data-entity="quantity-limit-value">
																	<?
																	if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
																	{
																		if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
																		{
																			echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
																		}
																		else
																		{
																			echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
																		}
																	}
																	else
																	{
																		echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
																	}
																	?>
																</span>
															</div>
														</div>
														<?
													}
												}
											}

											break;*/

										case 'quantity':
											if ( $arParams['USE_PRODUCT_QUANTITY'] ) {
												?>
                                                <div style="display: none;">
                                                    <div class="product-item-detail-info-container" style="<?= ( ! $actualItem['CAN_BUY'] ? 'display: none;' : '' ) ?>"
                                                         data-entity="quantity-block">
                                                        <div class="product-item-detail-info-container-title"><?= Loc::getMessage( 'CATALOG_QUANTITY' ) ?></div>
                                                        <div class="product-item-amount">
                                                            <div class="product-item-amount-field-container">
                                                                <span class="product-item-amount-field-btn-minus no-select" id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>"></span>
                                                                <input class="product-item-amount-field" id="<?= $itemIds['QUANTITY_ID'] ?>" type="number"
                                                                       value="<?= $price['MIN_QUANTITY'] ?>">
                                                                <span class="product-item-amount-field-btn-plus no-select" id="<?= $itemIds['QUANTITY_UP_ID'] ?>"></span>
                                                                <span class="product-item-amount-description-container">
                                                                <span id="<?= $itemIds['QUANTITY_MEASURE'] ?>">
                                                                    <?= $actualItem['ITEM_MEASURE']['TITLE'] ?>
                                                                </span>
                                                                <span id="<?= $itemIds['PRICE_TOTAL'] ?>"></span>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												<?
											}

											break;
									}
								}
								?>

								<?
								foreach ( $arParams['PRODUCT_INFO_BLOCK_ORDER'] as $blockName ) {
									switch ( $blockName ) {
										case 'sku':
											if ( $haveOffers && ! empty( $arResult['OFFERS_PROP'] ) ) {
												?>
                                                <div id="<?= $itemIds['TREE_ID'] ?>">
													<?
													foreach ( $arResult['SKU_PROPS'] as $skuProperty ) {
														if ( ! isset( $arResult['OFFERS_PROP'][ $skuProperty['CODE'] ] ) ) {
															continue;
														}

														$propertyId = $skuProperty['ID'];
														$skuProps[] = array(
															'ID'           => $propertyId,
															'SHOW_MODE'    => $skuProperty['SHOW_MODE'],
															'VALUES'       => $skuProperty['VALUES'],
															'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
														);
														?>
                                                        <div class="product-item-detail-info-container" data-entity="sku-line-block">
                                                            <div class="product-item-scu-container">
                                                                <div class="product-item-scu-block">
                                                                    <div class="product-item-scu-list selected_li_block">
                                                                        <button class="open-product-item-list"><span class="selected_li"></span></button>
                                                                        <ul class="product-item-scu-item-list">
																			<?
																			foreach ( $skuProperty['VALUES'] as &$value ) {
																				$value['NAME'] = htmlspecialcharsbx( $value['NAME'] );

																				if ( $skuProperty['SHOW_MODE'] === 'PICT' ) {
																					?>
                                                                                    <li class="product-item-scu-item-color-container my_li_select" title="<?= $value['NAME'] ?>"
                                                                                        data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                                        data-onevalue="<?= $value['ID'] ?>">
                                                                                        <div class="product-item-scu-item-color-block">
                                                                                            <div class="my_inner_select"
                                                                                                 title="<?= $value['NAME'] ?>"><?= $value['NAME'] ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
																					<?
																				} else {
																					?>
                                                                                    <li class="product-item-scu-item-text-container my_li_select" title="<?= $value['NAME'] ?>"
                                                                                        data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                                        data-onevalue="<?= $value['ID'] ?>">
                                                                                        <div class="product-item-scu-item-text-block">
                                                                                            <div class="my_inner_select"><?= $value['NAME'] ?></div> <? // product-item-scu-item-text
																							?>
                                                                                        </div>
                                                                                    </li>
																					<?
																				}
																			}
																			?>
                                                                        </ul>
                                                                        <div style="clear: both;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
														<?
													}
													?>

                                                    <script>
                                                        $(document).ready(function () {

                                                            function eachSelect() {
                                                                $(".my_li_select").each(function () {
                                                                    if ($(this).hasClass("selected")) {
                                                                        var nameSel = $(this).find(".my_inner_select").text();
                                                                        $(this).parents(".product-item-scu-list").find(".selected_li").text(nameSel);
                                                                    }
                                                                });
                                                            }

                                                            eachSelect();

                                                            $(".open-product-item-list").click(function () {
                                                                let el = $(this).closest(".selected_li_block"),
                                                                    list = el.find('.product-item-scu-item-list');

                                                                if (!el.hasClass('open'))
                                                                    el.addClass('open');
                                                                else
                                                                    el.removeClass('open');


                                                                $(document).click(function (e) {
                                                                    if (!el.is(e.target)
                                                                        && el.has(e.target).length === 0) {
                                                                        el.removeClass('open');
                                                                    }
                                                                });

                                                            });

                                                            $(".my_li_select").click(function () {

                                                                if (!$(this).closest('notallowed')) {
                                                                    $(this).closest(".selected_li_block").removeClass('open');

                                                                    eachSelect();
                                                                }
                                                            });
                                                        });
                                                    </script>

                                                </div>
												<?
											}

											break;

										/*case 'props':
											if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
											{
												?>
												<div class="product-item-detail-info-container">
													<?
													if (!empty($arResult['DISPLAY_PROPERTIES']))
													{
														?>
														<dl class="product-item-detail-properties">
															<?
															foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
															{
																if (isset($arParams['MAIN_BLOCK_PROPERTY_CODE'][$property['CODE']]))
																{
																	?>
																	<dt><?=$property['NAME']?></dt>
																	<dd><?=(is_array($property['DISPLAY_VALUE'])
																			? implode(' / ', $property['DISPLAY_VALUE'])
																			: $property['DISPLAY_VALUE'])?>
																	</dd>
																	<?
																}
															}
															unset($property);
															?>
														</dl>
														<?
													}

													if ($arResult['SHOW_OFFERS_PROPS'])
													{
														?>
														<dl class="product-item-detail-properties" id="<?=$itemIds['DISPLAY_MAIN_PROP_DIV']?>"></dl>
														<?
													}
													?>
												</div>
												<?
											}

											break;*/
									}
								}
								?>

								<?
								foreach ( $arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName ) {
									switch ( $blockName ) {
										case 'buttons':
											?>
                                            <div data-entity="main-button-container">
                                                <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>" style="display: <?= ( $actualItem['CAN_BUY'] ? '' : 'none' ) ?>;">
													<?
													if ( $showAddBtn ) {
														?>
                                                        <div class="product-item-detail-info-container flex det_cat_btns">
                                                            <a class="btn <?= $showButtonClassName ?> product-item-detail-buy-button" id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                                               href="javascript:void(0);">
                                                                <span>Добавить в корзину<? // =$arParams['MESS_BTN_ADD_TO_BASKET']
	                                                                ?></span>
                                                            </a>
                                                            <div class="add_like">
                                                                <a href="javascript:void(0)" class="wishbtn
                                                                <? if ( in_array( $arResult["ID"], $arBasketItems ) ) {
																	echo 'in_wishlist ';
																} ?>"
                                                                   onclick="add2wish(
                                                                           '<?= $arResult["ID"] ?>',
                                                                           '',
                                                                           '<?= $price["PRICE"] ?>',
                                                                           '<?= $arResult['NAME'] ?>',
                                                                           '<?= $arResult['ORIGINAL_PARAMETERS']['CURRENT_BASE_PAGE'] ?>',
                                                                           this)">
                                                                    <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z"
                                                                              stroke="#A4A4A4" stroke-width="1.5" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
														<?
													}

													if ( $showBuyBtn ) {
														?>
                                                        <div class="product-item-detail-info-container flex det_cat_btns">
                                                            <a class="btn <?= $buyButtonClassName ?> product-item-detail-buy-button" id="<?= $itemIds['BUY_LINK'] ?>"
                                                               href="javascript:void(0);">
                                                                <span>Добавить в корзину<? // =$arParams['MESS_BTN_BUY']
	                                                                ?></span>
                                                            </a>
                                                            <div class="add_like">
                                                                <a href="javascript:void(0)" class="wishbtn
                                                                <? if ( in_array( $arResult["ID"], $arBasketItems ) ) {
																	echo 'in_wishlist ';
																} ?>"
                                                                   onclick="add2wish(
                                                                           '<?= $arResult["ID"] ?>',
                                                                           '',
                                                                           '<?= $price["PRICE"] ?>',
                                                                           '<?= $arResult['NAME'] ?>',
                                                                           '<?= $arResult['ORIGINAL_PARAMETERS']['CURRENT_BASE_PAGE'] ?>',
                                                                           this)">
                                                                    <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z"
                                                                              stroke="#A4A4A4" stroke-width="1.5" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
														<?
													}
													?>
                                                </div>
												<?
												if ( $showSubscribe ) {
													?>
                                                    <div class="product-item-detail-info-container">
														<?
														$APPLICATION->IncludeComponent(
															'bitrix:catalog.product.subscribe',
															'',
															array(
																'CUSTOM_SITE_ID'     => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
																'PRODUCT_ID'         => $arResult['ID'],
																'BUTTON_ID'          => $itemIds['SUBSCRIBE_LINK'],
																'BUTTON_CLASS'       => 'btn btn-default product-item-detail-buy-button',
																'DEFAULT_DISPLAY'    => ! $actualItem['CAN_BUY'],
																'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
															),
															$component,
															array( 'HIDE_ICONS' => 'Y' )
														);
														?>
                                                    </div>
													<?
												}
												?>
                                                <div class="product-item-detail-info-container">
                                                    <a class="btn btn-link product-item-detail-buy-button" id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>"
                                                       href="javascript:void(0)"
                                                       rel="nofollow" style="display: <?= ( ! $actualItem['CAN_BUY'] ? '' : 'none' ) ?>;">
														<?= $arParams['MESS_NOT_AVAILABLE'] ?>
                                                    </a>
                                                </div>
                                            </div>
											<?
											break;
									}
								}

								if ( $arParams['DISPLAY_COMPARE'] ) {
									?>
                                    <div class="product-item-detail-compare-container">
                                        <div class="product-item-detail-compare">
                                            <div class="checkbox">
                                                <label id="<?= $itemIds['COMPARE_LINK'] ?>">
                                                    <input type="checkbox" data-entity="compare-checkbox">
                                                    <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
									<?
								}
								?>
                            </div>
                        </div>
                        <div class="size_info">
                            <span class="head_size_info">Информация о размерах</span>
                            <span>Не уверены, какой размер выбрать? <span class="popup_size">Найдите ваш рекомендованный размер</span> либо ознакомьтесь с нашей <span class="popup_size">таблицей размеров.</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div class="row info_text_det">
                <div class="col-md-4 col-sm-12">
                    <div class="text_head">Информация о товаре</div>
					<?
					if (
						$arResult['PREVIEW_TEXT'] != ''
						&& (
							$arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
							|| ( $arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '' )
						)
					) {
						echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>';
					}

					if ( $arResult['DETAIL_TEXT'] != '' ) {
						echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>' . $arResult['DETAIL_TEXT'] . '</p>';
					}
					?>
                </div>
                <div class="col-md-4 col-sm-12">
					<?
					if ( $arResult['PROPERTIES']['CARE']['VALUE']['TEXT'] ) : ?>
                        <div class="text_head">Уход</div>
                        <div><?= $arResult['PROPERTIES']['CARE']['~VALUE']['TEXT'] ?></div>
					<? endif; ?>
                    <div class="text_head">Характеристики</div>
					<?
					if ( ! empty( $arResult['DISPLAY_PROPERTIES'] ) ) {
						?>
                        <dl class="product-item-detail-properties">
							<?
							foreach ( $arResult['DISPLAY_PROPERTIES'] as $property ) {
								?>
                                <dt><?= $property['NAME'] ?></dt>
                                <dd><?= (
									is_array( $property['DISPLAY_VALUE'] )
										? implode( ' / ', $property['DISPLAY_VALUE'] )
										: $property['DISPLAY_VALUE']
									) ?>
                                </dd>
								<?
							}
							unset( $property );

							foreach ($arResult['PROPERTIES']['PRODUCT_ATTRIBUTES']['VALUE'] as $value) {
                                ?>
                                <dt><?= $value['NAME'] ?></dt>
                                <dd><?= $value['VALUE'] ?>
                                </dd>
                                <?
                            }
							?>
                        </dl>
						<?
					}
					?>
                </div>
                <div class="col-md-4 col-sm-12">
					<?
					if ( !empty($arResult['PROPERTIES']['BRAND_REF']['VALUE_DESCRIPTION'])) : ?>
                        <div class="text_head">Бренд</div>
                        <div><?= $arResult['PROPERTIES']['BRAND_REF']['VALUE_DESCRIPTION'] ?></div>
					<? endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
					<?
					if ( $haveOffers ) {
						if ( $arResult['OFFER_GROUP'] ) {
							foreach ( $arResult['OFFER_GROUP_VALUES'] as $offerId ) {
								?>
                                <span id="<?= $itemIds['OFFER_GROUP'] . $offerId ?>" style="display: none;">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.set.constructor',
									'.default',
									array(
										'CUSTOM_SITE_ID'         => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
										'IBLOCK_ID'              => $arResult['OFFERS_IBLOCK'],
										'ELEMENT_ID'             => $offerId,
										'PRICE_CODE'             => $arParams['PRICE_CODE'],
										'BASKET_URL'             => $arParams['BASKET_URL'],
										'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
										'CACHE_TYPE'             => $arParams['CACHE_TYPE'],
										'CACHE_TIME'             => $arParams['CACHE_TIME'],
										'CACHE_GROUPS'           => $arParams['CACHE_GROUPS'],
										'TEMPLATE_THEME'         => $arParams['~TEMPLATE_THEME'],
										'CONVERT_CURRENCY'       => $arParams['CONVERT_CURRENCY'],
										'CURRENCY_ID'            => $arParams['CURRENCY_ID']
									),
									$component,
									array( 'HIDE_ICONS' => 'Y' )
								);
								?>
							</span>
								<?
							}
						}
					} else {
						if ( $arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'] ) {
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.set.constructor',
								'.default',
								array(
									'CUSTOM_SITE_ID'   => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
									'IBLOCK_ID'        => $arParams['IBLOCK_ID'],
									'ELEMENT_ID'       => $arResult['ID'],
									'PRICE_CODE'       => $arParams['PRICE_CODE'],
									'BASKET_URL'       => $arParams['BASKET_URL'],
									'CACHE_TYPE'       => $arParams['CACHE_TYPE'],
									'CACHE_TIME'       => $arParams['CACHE_TIME'],
									'CACHE_GROUPS'     => $arParams['CACHE_GROUPS'],
									'TEMPLATE_THEME'   => $arParams['~TEMPLATE_THEME'],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID'      => $arParams['CURRENCY_ID']
								),
								$component,
								array( 'HIDE_ICONS' => 'Y' )
							);
						}
					}
					?>
                </div>
            </div>

			<? /*<div class="row">
			<div class="col-sm-8 col-md-9">
				<div class="row" id="<?=$itemIds['TABS_ID']?>">
					<div class="col-xs-12">
						<div class="product-item-detail-tabs-container">
							<ul class="product-item-detail-tabs-list">
								<?
								if ($showDescription)
								{
									?>
									<li class="product-item-detail-tab active" data-entity="tab" data-value="description">
										<a href="javascript:void(0);" class="product-item-detail-tab-link">
											<span><?=$arParams['MESS_DESCRIPTION_TAB']?></span>
										</a>
									</li>
									<?
								}

								if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
								{
									?>
									<li class="product-item-detail-tab" data-entity="tab" data-value="properties">
										<a href="javascript:void(0);" class="product-item-detail-tab-link">
											<span><?=$arParams['MESS_PROPERTIES_TAB']?></span>
										</a>
									</li>
									<?
								}

								if ($arParams['USE_COMMENTS'] === 'Y')
								{
									?>
									<li class="product-item-detail-tab" data-entity="tab" data-value="comments">
										<a href="javascript:void(0);" class="product-item-detail-tab-link">
											<span><?=$arParams['MESS_COMMENTS_TAB']?></span>
										</a>
									</li>
									<?
								}
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="row" id="<?=$itemIds['TAB_CONTAINERS_ID']?>">
					<div class="col-xs-12">
						<?
						if ($showDescription)
						{
							?>
							<div class="product-item-detail-tab-content active" data-entity="tab-container" data-value="description"
								itemprop="description">
								<?
								if (
									$arResult['PREVIEW_TEXT'] != ''
									&& (
										$arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
										|| ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
									)
								)
								{
									echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>';
								}

								if ($arResult['DETAIL_TEXT'] != '')
								{
									echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
								}
								?>
							</div>
							<?
						}

						if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
						{
							?>
							<div class="product-item-detail-tab-content" data-entity="tab-container" data-value="properties">
								<?
								if (!empty($arResult['DISPLAY_PROPERTIES']))
								{
									?>
									<dl class="product-item-detail-properties">
										<?
										foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
										{
											?>
											<dt><?=$property['NAME']?></dt>
											<dd><?=(
												is_array($property['DISPLAY_VALUE'])
													? implode(' / ', $property['DISPLAY_VALUE'])
													: $property['DISPLAY_VALUE']
												)?>
											</dd>
											<?
										}
										unset($property);
										?>
									</dl>
									<?
								}

								if ($arResult['SHOW_OFFERS_PROPS'])
								{
									?>
									<dl class="product-item-detail-properties" id="<?=$itemIds['DISPLAY_PROP_DIV']?>"></dl>
									<?
								}
								?>
							</div>
							<?
						}

						if ($arParams['USE_COMMENTS'] === 'Y')
						{
							?>
							<div class="product-item-detail-tab-content" data-entity="tab-container" data-value="comments" style="display: none;">
								<?
								$componentCommentsParams = array(
									'ELEMENT_ID' => $arResult['ID'],
									'ELEMENT_CODE' => '',
									'IBLOCK_ID' => $arParams['IBLOCK_ID'],
									'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
									'URL_TO_COMMENT' => '',
									'WIDTH' => '',
									'COMMENTS_COUNT' => '5',
									'BLOG_USE' => $arParams['BLOG_USE'],
									'FB_USE' => $arParams['FB_USE'],
									'FB_APP_ID' => $arParams['FB_APP_ID'],
									'VK_USE' => $arParams['VK_USE'],
									'VK_API_ID' => $arParams['VK_API_ID'],
									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],
									'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
									'BLOG_TITLE' => '',
									'BLOG_URL' => $arParams['BLOG_URL'],
									'PATH_TO_SMILE' => '',
									'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
									'AJAX_POST' => 'Y',
									'SHOW_SPAM' => 'Y',
									'SHOW_RATING' => 'N',
									'FB_TITLE' => '',
									'FB_USER_ADMIN_ID' => '',
									'FB_COLORSCHEME' => 'light',
									'FB_ORDER_BY' => 'reverse_time',
									'VK_TITLE' => '',
									'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME']
								);
								if(isset($arParams["USER_CONSENT"]))
									$componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
								if(isset($arParams["USER_CONSENT_ID"]))
									$componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
								if(isset($arParams["USER_CONSENT_IS_CHECKED"]))
									$componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
								if(isset($arParams["USER_CONSENT_IS_LOADED"]))
									$componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.comments',
									'',
									$componentCommentsParams,
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
			</div>
			<div class="col-sm-4 col-md-3">
				<div>
					<?
					if ($arParams['BRAND_USE'] === 'Y')
					{
						$APPLICATION->IncludeComponent(
							'bitrix:catalog.brandblock',
							'.default',
							array(
								'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
								'IBLOCK_ID' => $arParams['IBLOCK_ID'],
								'ELEMENT_ID' => $arResult['ID'],
								'ELEMENT_CODE' => '',
								'PROP_CODE' => $arParams['BRAND_PROP_CODE'],
								'CACHE_TYPE' => $arParams['CACHE_TYPE'],
								'CACHE_TIME' => $arParams['CACHE_TIME'],
								'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
								'WIDTH' => '',
								'HEIGHT' => ''
							),
							$component,
							array('HIDE_ICONS' => 'Y')
						);
					}
					?>
				</div>
			</div>
		</div>*/ ?>
            <div class="row">
                <div class="col-xs-12">
					<?
					if ( $arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled( 'sale' ) ) {
						$APPLICATION->IncludeComponent(
							'bitrix:sale.prediction.product.detail',
							'.default',
							array(
								'BUTTON_ID'                => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
								'CUSTOM_SITE_ID'           => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
								'POTENTIAL_PRODUCT_TO_BUY' => array(
									'ID'                     => isset( $arResult['ID'] ) ? $arResult['ID'] : null,
									'MODULE'                 => isset( $arResult['MODULE'] ) ? $arResult['MODULE'] : 'catalog',
									'PRODUCT_PROVIDER_CLASS' => isset( $arResult['~PRODUCT_PROVIDER_CLASS'] ) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
									'QUANTITY'               => isset( $arResult['QUANTITY'] ) ? $arResult['QUANTITY'] : null,
									'IBLOCK_ID'              => isset( $arResult['IBLOCK_ID'] ) ? $arResult['IBLOCK_ID'] : null,

									'PRIMARY_OFFER_ID' => isset( $arResult['OFFERS'][0]['ID'] ) ? $arResult['OFFERS'][0]['ID'] : null,
									'SECTION'          => array(
										'ID'           => isset( $arResult['SECTION']['ID'] ) ? $arResult['SECTION']['ID'] : null,
										'IBLOCK_ID'    => isset( $arResult['SECTION']['IBLOCK_ID'] ) ? $arResult['SECTION']['IBLOCK_ID'] : null,
										'LEFT_MARGIN'  => isset( $arResult['SECTION']['LEFT_MARGIN'] ) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
										'RIGHT_MARGIN' => isset( $arResult['SECTION']['RIGHT_MARGIN'] ) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
									),
								)
							),
							$component,
							array( 'HIDE_ICONS' => 'Y' )
						);
					}

					if ( $arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled( 'sale' ) ) {
						?>
                        <div data-entity="parent-container">
							<?
							if ( ! isset( $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'] ) || $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y' ) {
								?>
                                <div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
									<?= ( $arParams['GIFTS_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage( 'CT_BCE_CATALOG_GIFT_BLOCK_TITLE_DEFAULT' ) ) ?>
                                </div>
								<?
							}

							CBitrixComponent::includeComponentClass( 'bitrix:sale.products.gift' );
							$APPLICATION->IncludeComponent(
								'bitrix:sale.products.gift',
								'.default',
								array(
									'CUSTOM_SITE_ID'      => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
									'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
									'ACTION_VARIABLE'     => $arParams['ACTION_VARIABLE'],

									'PRODUCT_ROW_VARIANTS'          => "",
									'PAGE_ELEMENT_COUNT'            => 0,
									'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
										SaleProductsGiftComponent::predictRowVariants(
											$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
											$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']
										)
									),
									'DEFERRED_PAGE_ELEMENT_COUNT'   => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],

									'SHOW_DISCOUNT_PERCENT'     => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
									'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
									'SHOW_OLD_PRICE'            => $arParams['GIFTS_SHOW_OLD_PRICE'],
									'PRODUCT_DISPLAY_MODE'      => 'Y',
									'PRODUCT_BLOCKS_ORDER'      => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],
									'SHOW_SLIDER'               => $arParams['GIFTS_SHOW_SLIDER'],
									'SLIDER_INTERVAL'           => isset( $arParams['GIFTS_SLIDER_INTERVAL'] ) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
									'SLIDER_PROGRESS'           => isset( $arParams['GIFTS_SLIDER_PROGRESS'] ) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

									'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

									'LABEL_PROP_' . $arParams['IBLOCK_ID']        => array(),
									'LABEL_PROP_MOBILE_' . $arParams['IBLOCK_ID'] => array(),
									'LABEL_PROP_POSITION'                         => $arParams['LABEL_PROP_POSITION'],

									'ADD_TO_BASKET_ACTION'   => ( isset( $arParams['ADD_TO_BASKET_ACTION'] ) ? $arParams['ADD_TO_BASKET_ACTION'] : '' ),
									'MESS_BTN_BUY'           => $arParams['~GIFTS_MESS_BTN_BUY'],
									'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
									'MESS_BTN_DETAIL'        => $arParams['~MESS_BTN_DETAIL'],
									'MESS_BTN_SUBSCRIBE'     => $arParams['~MESS_BTN_SUBSCRIBE'],

									'SHOW_PRODUCTS_' . $arParams['IBLOCK_ID']            => 'Y',
									'PROPERTY_CODE_' . $arParams['IBLOCK_ID']            => $arParams['LIST_PROPERTY_CODE'],
									'PROPERTY_CODE_MOBILE' . $arParams['IBLOCK_ID']      => $arParams['LIST_PROPERTY_CODE_MOBILE'],
									'PROPERTY_CODE_' . $arResult['OFFERS_IBLOCK']        => $arParams['OFFER_TREE_PROPS'],
									'OFFER_TREE_PROPS_' . $arResult['OFFERS_IBLOCK']     => $arParams['OFFER_TREE_PROPS'],
									'CART_PROPERTIES_' . $arResult['OFFERS_IBLOCK']      => $arParams['OFFERS_CART_PROPERTIES'],
									'ADDITIONAL_PICT_PROP_' . $arParams['IBLOCK_ID']     => ( isset( $arParams['ADD_PICT_PROP'] ) ? $arParams['ADD_PICT_PROP'] : '' ),
									'ADDITIONAL_PICT_PROP_' . $arResult['OFFERS_IBLOCK'] => ( isset( $arParams['OFFER_ADD_PICT_PROP'] ) ? $arParams['OFFER_ADD_PICT_PROP'] : '' ),

									'HIDE_NOT_AVAILABLE'         => 'Y',
									'HIDE_NOT_AVAILABLE_OFFERS'  => 'Y',
									'PRODUCT_SUBSCRIPTION'       => $arParams['PRODUCT_SUBSCRIPTION'],
									'TEMPLATE_THEME'             => $arParams['TEMPLATE_THEME'],
									'PRICE_CODE'                 => $arParams['PRICE_CODE'],
									'SHOW_PRICE_COUNT'           => $arParams['SHOW_PRICE_COUNT'],
									'PRICE_VAT_INCLUDE'          => $arParams['PRICE_VAT_INCLUDE'],
									'CONVERT_CURRENCY'           => $arParams['CONVERT_CURRENCY'],
									'BASKET_URL'                 => $arParams['BASKET_URL'],
									'ADD_PROPERTIES_TO_BASKET'   => $arParams['ADD_PROPERTIES_TO_BASKET'],
									'PRODUCT_PROPS_VARIABLE'     => $arParams['PRODUCT_PROPS_VARIABLE'],
									'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
									'USE_PRODUCT_QUANTITY'       => 'N',
									'PRODUCT_QUANTITY_VARIABLE'  => $arParams['PRODUCT_QUANTITY_VARIABLE'],
									'CACHE_GROUPS'               => $arParams['CACHE_GROUPS'],
									'POTENTIAL_PRODUCT_TO_BUY'   => array(
										'ID'                     => isset( $arResult['ID'] ) ? $arResult['ID'] : null,
										'MODULE'                 => isset( $arResult['MODULE'] ) ? $arResult['MODULE'] : 'catalog',
										'PRODUCT_PROVIDER_CLASS' => isset( $arResult['~PRODUCT_PROVIDER_CLASS'] ) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
										'QUANTITY'               => isset( $arResult['QUANTITY'] ) ? $arResult['QUANTITY'] : null,
										'IBLOCK_ID'              => isset( $arResult['IBLOCK_ID'] ) ? $arResult['IBLOCK_ID'] : null,

										'PRIMARY_OFFER_ID' => isset( $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ]['ID'] )
											? $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ]['ID']
											: null,
										'SECTION'          => array(
											'ID'           => isset( $arResult['SECTION']['ID'] ) ? $arResult['SECTION']['ID'] : null,
											'IBLOCK_ID'    => isset( $arResult['SECTION']['IBLOCK_ID'] ) ? $arResult['SECTION']['IBLOCK_ID'] : null,
											'LEFT_MARGIN'  => isset( $arResult['SECTION']['LEFT_MARGIN'] ) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
											'RIGHT_MARGIN' => isset( $arResult['SECTION']['RIGHT_MARGIN'] ) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
										),
									),

									'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
									'DATA_LAYER_NAME'        => $arParams['DATA_LAYER_NAME'],
									'BRAND_PROPERTY'         => $arParams['BRAND_PROPERTY']
								),
								$component,
								array( 'HIDE_ICONS' => 'Y' )
							);
							?>
                        </div>
						<?
					}

					if ( $arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled( 'sale' ) ) {
						?>
                        <div data-entity="parent-container">
							<?
							if ( ! isset( $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] ) || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y' ) {
								?>
                                <div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
									<?= ( $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage( 'CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT' ) ) ?>
                                </div>
								<?
							}

							$APPLICATION->IncludeComponent(
								'bitrix:sale.gift.main.products',
								'.default',
								array(
									'CUSTOM_SITE_ID'     => isset( $arParams['CUSTOM_SITE_ID'] ) ? $arParams['CUSTOM_SITE_ID'] : null,
									'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
									'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
									'HIDE_BLOCK_TITLE'   => 'Y',
									'BLOCK_TITLE'        => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

									'OFFERS_FIELD_CODE'    => $arParams['OFFERS_FIELD_CODE'],
									'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

									'AJAX_MODE'   => $arParams['AJAX_MODE'],
									'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
									'IBLOCK_ID'   => $arParams['IBLOCK_ID'],

									'ELEMENT_SORT_FIELD'  => 'ID',
									'ELEMENT_SORT_ORDER'  => 'DESC',
									//'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
									//'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
									'FILTER_NAME'         => 'searchFilter',
									'SECTION_URL'         => $arParams['SECTION_URL'],
									'DETAIL_URL'          => $arParams['DETAIL_URL'],
									'BASKET_URL'          => $arParams['BASKET_URL'],
									'ACTION_VARIABLE'     => $arParams['ACTION_VARIABLE'],
									'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
									'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],

									'CACHE_GROUPS'     => $arParams['CACHE_GROUPS'],
									'SET_TITLE'        => $arParams['SET_TITLE'],
									'PROPERTY_CODE'    => $arParams['PROPERTY_CODE'],
									'PRICE_CODE'       => $arParams['PRICE_CODE'],
									'USE_PRICE_COUNT'  => $arParams['USE_PRICE_COUNT'],
									'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

									'PRICE_VAT_INCLUDE'         => $arParams['PRICE_VAT_INCLUDE'],
									'CONVERT_CURRENCY'          => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID'               => $arParams['CURRENCY_ID'],
									'HIDE_NOT_AVAILABLE'        => 'Y',
									'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
									'TEMPLATE_THEME'            => ( isset( $arParams['TEMPLATE_THEME'] ) ? $arParams['TEMPLATE_THEME'] : '' ),
									'PRODUCT_BLOCKS_ORDER'      => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

									'SHOW_SLIDER'     => $arParams['GIFTS_SHOW_SLIDER'],
									'SLIDER_INTERVAL' => isset( $arParams['GIFTS_SLIDER_INTERVAL'] ) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
									'SLIDER_PROGRESS' => isset( $arParams['GIFTS_SLIDER_PROGRESS'] ) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

									'ADD_PICT_PROP'             => ( isset( $arParams['ADD_PICT_PROP'] ) ? $arParams['ADD_PICT_PROP'] : '' ),
									'LABEL_PROP'                => ( isset( $arParams['LABEL_PROP'] ) ? $arParams['LABEL_PROP'] : '' ),
									'LABEL_PROP_MOBILE'         => ( isset( $arParams['LABEL_PROP_MOBILE'] ) ? $arParams['LABEL_PROP_MOBILE'] : '' ),
									'LABEL_PROP_POSITION'       => ( isset( $arParams['LABEL_PROP_POSITION'] ) ? $arParams['LABEL_PROP_POSITION'] : '' ),
									'OFFER_ADD_PICT_PROP'       => ( isset( $arParams['OFFER_ADD_PICT_PROP'] ) ? $arParams['OFFER_ADD_PICT_PROP'] : '' ),
									'OFFER_TREE_PROPS'          => ( isset( $arParams['OFFER_TREE_PROPS'] ) ? $arParams['OFFER_TREE_PROPS'] : '' ),
									'SHOW_DISCOUNT_PERCENT'     => ( isset( $arParams['SHOW_DISCOUNT_PERCENT'] ) ? $arParams['SHOW_DISCOUNT_PERCENT'] : '' ),
									'DISCOUNT_PERCENT_POSITION' => ( isset( $arParams['DISCOUNT_PERCENT_POSITION'] ) ? $arParams['DISCOUNT_PERCENT_POSITION'] : '' ),
									'SHOW_OLD_PRICE'            => ( isset( $arParams['SHOW_OLD_PRICE'] ) ? $arParams['SHOW_OLD_PRICE'] : '' ),
									'MESS_BTN_BUY'              => ( isset( $arParams['~MESS_BTN_BUY'] ) ? $arParams['~MESS_BTN_BUY'] : '' ),
									'MESS_BTN_ADD_TO_BASKET'    => ( isset( $arParams['~MESS_BTN_ADD_TO_BASKET'] ) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : '' ),
									'MESS_BTN_DETAIL'           => ( isset( $arParams['~MESS_BTN_DETAIL'] ) ? $arParams['~MESS_BTN_DETAIL'] : '' ),
									'MESS_NOT_AVAILABLE'        => ( isset( $arParams['~MESS_NOT_AVAILABLE'] ) ? $arParams['~MESS_NOT_AVAILABLE'] : '' ),
									'ADD_TO_BASKET_ACTION'      => ( isset( $arParams['ADD_TO_BASKET_ACTION'] ) ? $arParams['ADD_TO_BASKET_ACTION'] : '' ),
									'SHOW_CLOSE_POPUP'          => ( isset( $arParams['SHOW_CLOSE_POPUP'] ) ? $arParams['SHOW_CLOSE_POPUP'] : '' ),
									'DISPLAY_COMPARE'           => ( isset( $arParams['DISPLAY_COMPARE'] ) ? $arParams['DISPLAY_COMPARE'] : '' ),
									'COMPARE_PATH'              => ( isset( $arParams['COMPARE_PATH'] ) ? $arParams['COMPARE_PATH'] : '' ),
								)
								+ array(
									'OFFER_ID'   => empty( $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ]['ID'] )
										? $arResult['ID']
										: $arResult['OFFERS'][ $arResult['OFFERS_SELECTED'] ]['ID'],
									'SECTION_ID' => $arResult['SECTION']['ID'],
									'ELEMENT_ID' => $arResult['ID'],

									'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
									'DATA_LAYER_NAME'        => $arParams['DATA_LAYER_NAME'],
									'BRAND_PROPERTY'         => $arParams['BRAND_PROPERTY']
								),
								$component,
								array( 'HIDE_ICONS' => 'Y' )
							);
							?>
                        </div>
						<?
					}
					?>
                </div>
            </div>

        </div>

        <!--Top tabs-->
        <div class="product-item-detail-tabs-container-fixed hidden-xs" id="<?= $itemIds['TABS_PANEL_ID'] ?>">
            <ul class="product-item-detail-tabs-list">
				<?
				if ( $showDescription ) {
					?>
                    <li class="product-item-detail-tab active" data-entity="tab" data-value="description">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_DESCRIPTION_TAB'] ?></span>
                        </a>
                    </li>
					<?
				}

				if ( ! empty( $arResult['DISPLAY_PROPERTIES'] ) || $arResult['SHOW_OFFERS_PROPS'] ) {
					?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="properties">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_PROPERTIES_TAB'] ?></span>
                        </a>
                    </li>
					<?
				}

				if ( $arParams['USE_COMMENTS'] === 'Y' ) {
					?>
                    <li class="product-item-detail-tab" data-entity="tab" data-value="comments">
                        <a href="javascript:void(0);" class="product-item-detail-tab-link">
                            <span><?= $arParams['MESS_COMMENTS_TAB'] ?></span>
                        </a>
                    </li>
					<?
				}
				?>
            </ul>
        </div>

        <meta itemprop="name" content="<?= $name ?>"/>
        <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
		<?
		if ( $haveOffers ) {
			foreach ( $arResult['JS_OFFERS'] as $offer ) {
				$currentOffersList = array();

				if ( ! empty( $offer['TREE'] ) && is_array( $offer['TREE'] ) ) {
					foreach ( $offer['TREE'] as $propName => $skuId ) {
						$propId = (int) substr( $propName, 5 );

						foreach ( $skuProps as $prop ) {
							if ( $prop['ID'] == $propId ) {
								foreach ( $prop['VALUES'] as $propId => $propValue ) {
									if ( $propId == $skuId ) {
										$currentOffersList[] = $propValue['NAME'];
										break;
									}
								}
							}
						}
					}
				}

				$offerPrice = $offer['ITEM_PRICES'][ $offer['ITEM_PRICE_SELECTED'] ];
				?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?= htmlspecialcharsbx( implode( '/', $currentOffersList ) ) ?>"/>
				<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
				<meta itemprop="priceCurrency" content="<?= $offerPrice['CURRENCY'] ?>"/>
				<link itemprop="availability" href="http://schema.org/<?= ( $offer['CAN_BUY'] ? 'InStock' : 'OutOfStock' ) ?>"/>
			</span>
				<?
			}

			unset( $offerPrice, $currentOffersList );
		} else {
			?>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
			<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
			<link itemprop="availability" href="http://schema.org/<?= ( $actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock' ) ?>"/>
		</span>
			<?
		}
		?>
    </div>
<?
if ( $haveOffers ) {
	$offerIds   = array();
	$offerCodes = array();

	$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

	foreach ( $arResult['JS_OFFERS'] as $ind => &$jsOffer ) {
		$offerIds[]   = (int) $jsOffer['ID'];
		$offerCodes[] = $jsOffer['CODE'];

		$fullOffer   = $arResult['OFFERS'][ $ind ];
		$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

		$strAllProps         = '';
		$strMainProps        = '';
		$strPriceRangesRatio = '';
		$strPriceRanges      = '';

		if ( $arResult['SHOW_OFFERS_PROPS'] ) {
			if ( ! empty( $jsOffer['DISPLAY_PROPERTIES'] ) ) {
				foreach ( $jsOffer['DISPLAY_PROPERTIES'] as $property ) {
					$current     = (
					is_array( $property['VALUE'] )
						? implode( ' / ', $property['VALUE'] )
						: $property['VALUE']
					);
					$strAllProps .= $current;

					if ( isset( $arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][ $property['CODE'] ] ) ) {
						$strMainProps .= $current;
					}
				}

				unset( $current );
			}
		}

		if ( $arParams['USE_PRICE_COUNT'] && count( $jsOffer['ITEM_QUANTITY_RANGES'] ) > 1 ) {
			$strPriceRangesRatio = '(' . Loc::getMessage(
					'CT_BCE_CATALOG_RATIO_PRICE',
					array(
						'#RATIO#' => ( $useRatio
								? $fullOffer['ITEM_MEASURE_RATIOS'][ $fullOffer['ITEM_MEASURE_RATIO_SELECTED'] ]['RATIO']
								: '1'
						             ) . ' ' . $measureName
					)
				) . ')';

			foreach ( $jsOffer['ITEM_QUANTITY_RANGES'] as $range ) {
				if ( $range['HASH'] !== 'ZERO-INF' ) {
					$itemPrice = false;

					foreach ( $jsOffer['ITEM_PRICES'] as $itemPrice ) {
						if ( $itemPrice['QUANTITY_HASH'] === $range['HASH'] ) {
							break;
						}
					}

					if ( $itemPrice ) {
						$strPriceRanges .= '<dt>' . Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_FROM',
								array( '#FROM#' => $range['SORT_FROM'] . ' ' . $measureName )
							) . ' ';

						if ( is_infinite( $range['SORT_TO'] ) ) {
							$strPriceRanges .= Loc::getMessage( 'CT_BCE_CATALOG_RANGE_MORE' );
						} else {
							$strPriceRanges .= Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_TO',
								array( '#TO#' => $range['SORT_TO'] . ' ' . $measureName )
							);
						}

						$strPriceRanges .= '</dt><dd>' . ( $useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'] ) . '</dd>';
					}
				}
			}

			unset( $range, $itemPrice );
		}

		$jsOffer['DISPLAY_PROPERTIES']            = $strAllProps;
		$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
		$jsOffer['PRICE_RANGES_RATIO_HTML']       = $strPriceRangesRatio;
		$jsOffer['PRICE_RANGES_HTML']             = $strPriceRanges;
	}

	$templateData['OFFER_IDS']   = $offerIds;
	$templateData['OFFER_CODES'] = $offerCodes;
	unset( $jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio );

	$jsParams = array(
		'CONFIG'          => array(
			'USE_CATALOG'              => $arResult['CATALOG'],
			'SHOW_QUANTITY'            => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE'               => true,
			'SHOW_DISCOUNT_PERCENT'    => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE'           => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT'          => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE'          => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS'           => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP'              => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE'        => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION'     => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP'         => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY'        => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS'             => true,
			'USE_SUBSCRIBE'            => $showSubscribe,
			'SHOW_SLIDER'              => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL'          => $arParams['SLIDER_INTERVAL'],
			'ALT'                      => $alt,
			'TITLE'                    => $title,
			'MAGNIFIER_ZOOM_PERCENT'   => 200,
			'USE_ENHANCED_ECOMMERCE'   => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME'          => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY'           => ! empty( $arResult['DISPLAY_PROPERTIES'][ $arParams['BRAND_PROPERTY'] ] )
				? $arResult['DISPLAY_PROPERTIES'][ $arParams['BRAND_PROPERTY'] ]['DISPLAY_VALUE']
				: null
		),
		'PRODUCT_TYPE'    => $arResult['PRODUCT']['TYPE'],
		'VISUAL'          => $itemIds,
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE'  => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT'         => array(
			'ID'       => $arResult['ID'],
			'ACTIVE'   => $arResult['ACTIVE'],
			'NAME'     => $arResult['~NAME'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET'          => array(
			'QUANTITY'         => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL'       => $arParams['BASKET_URL'],
			'SKU_PROPS'        => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS'          => $arResult['JS_OFFERS'],
		'OFFER_SELECTED'  => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS'      => $skuProps
	);
} else {
	$emptyProductProperties = empty( $arResult['PRODUCT_PROPERTIES'] );
	if ( $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && ! $emptyProductProperties ) {
		?>
        <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
			<?
			if ( ! empty( $arResult['PRODUCT_PROPERTIES_FILL'] ) ) {
				foreach ( $arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo ) {
					?>
                    <input type="hidden" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]" value="<?= htmlspecialcharsbx( $propInfo['ID'] ) ?>">
					<?
					unset( $arResult['PRODUCT_PROPERTIES'][ $propId ] );
				}
			}

			$emptyProductProperties = empty( $arResult['PRODUCT_PROPERTIES'] );
			if ( ! $emptyProductProperties ) {
				?>
                <table>
					<?
					foreach ( $arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo ) {
						?>
                        <tr>
                            <td><?= $arResult['PROPERTIES'][ $propId ]['NAME'] ?></td>
                            <td>
								<?
								if (
									$arResult['PROPERTIES'][ $propId ]['PROPERTY_TYPE'] === 'L'
									&& $arResult['PROPERTIES'][ $propId ]['LIST_TYPE'] === 'C'
								) {
									foreach ( $propInfo['VALUES'] as $valueId => $value ) {
										?>
                                        <label>
                                            <input type="radio" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                                   value="<?= $valueId ?>" <?= ( $valueId == $propInfo['SELECTED'] ? '"checked"' : '' ) ?>>
											<?= $value ?>
                                        </label>
                                        <br>
										<?
									}
								} else {
									?>
                                    <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
										<?
										foreach ( $propInfo['VALUES'] as $valueId => $value ) {
											?>
                                            <option value="<?= $valueId ?>" <?= ( $valueId == $propInfo['SELECTED'] ? '"selected"' : '' ) ?>>
												<?= $value ?>
                                            </option>
											<?
										}
										?>
                                    </select>
									<?
								}
								?>
                            </td>
                        </tr>
						<?
					}
					?>
                </table>
				<?
			}
			?>
        </div>
		<?
	}

	$jsParams = array(
		'CONFIG'       => array(
			'USE_CATALOG'              => $arResult['CATALOG'],
			'SHOW_QUANTITY'            => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE'               => ! empty( $arResult['ITEM_PRICES'] ),
			'SHOW_DISCOUNT_PERCENT'    => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE'           => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT'          => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE'          => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE'        => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION'     => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP'         => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY'        => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS'             => true,
			'USE_SUBSCRIBE'            => $showSubscribe,
			'SHOW_SLIDER'              => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL'          => $arParams['SLIDER_INTERVAL'],
			'ALT'                      => $alt,
			'TITLE'                    => $title,
			'MAGNIFIER_ZOOM_PERCENT'   => 200,
			'USE_ENHANCED_ECOMMERCE'   => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME'          => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY'           => ! empty( $arResult['DISPLAY_PROPERTIES'][ $arParams['BRAND_PROPERTY'] ] )
				? $arResult['DISPLAY_PROPERTIES'][ $arParams['BRAND_PROPERTY'] ]['DISPLAY_VALUE']
				: null
		),
		'VISUAL'       => $itemIds,
		'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
		'PRODUCT'      => array(
			'ID'                           => $arResult['ID'],
			'ACTIVE'                       => $arResult['ACTIVE'],
			'PICT'                         => reset( $arResult['MORE_PHOTO'] ),
			'NAME'                         => $arResult['~NAME'],
			'SUBSCRIPTION'                 => true,
			'ITEM_PRICE_MODE'              => $arResult['ITEM_PRICE_MODE'],
			'ITEM_PRICES'                  => $arResult['ITEM_PRICES'],
			'ITEM_PRICE_SELECTED'          => $arResult['ITEM_PRICE_SELECTED'],
			'ITEM_QUANTITY_RANGES'         => $arResult['ITEM_QUANTITY_RANGES'],
			'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
			'ITEM_MEASURE_RATIOS'          => $arResult['ITEM_MEASURE_RATIOS'],
			'ITEM_MEASURE_RATIO_SELECTED'  => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
			'SLIDER_COUNT'                 => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER'                       => $arResult['MORE_PHOTO'],
			'CAN_BUY'                      => $arResult['CAN_BUY'],
			'CHECK_QUANTITY'               => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT'               => is_float( $arResult['ITEM_MEASURE_RATIOS'][ $arResult['ITEM_MEASURE_RATIO_SELECTED'] ]['RATIO'] ),
			'MAX_QUANTITY'                 => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY'                => $arResult['ITEM_MEASURE_RATIOS'][ $arResult['ITEM_MEASURE_RATIO_SELECTED'] ]['RATIO'],
			'CATEGORY'                     => $arResult['CATEGORY_PATH']
		),
		'BASKET'       => array(
			'ADD_PROPS'        => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
			'QUANTITY'         => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS'            => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS'      => $emptyProductProperties,
			'BASKET_URL'       => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	unset( $emptyProductProperties );
}

if ( $arParams['DISPLAY_COMPARE'] ) {
	$jsParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE'        => $arResult['~COMPARE_URL_TEMPLATE'],
		'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
		'COMPARE_PATH'                => $arParams['COMPARE_PATH']
	);
}
?>


    <div class="table_sizes">
        <div class="table_popup">
            <div class="close_table_sz">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.292818 1.70711L8.29282 9.70711L9.70703 8.29289L1.70703 0.292893L0.292818 1.70711ZM8.29282 9.70711L16.2928 17.7071L17.707 16.2929L9.70703 8.29289L8.29282 9.70711ZM16.2928 0.292893L8.29282 8.29289L9.70703 9.70711L17.707 1.70711L16.2928 0.292893ZM8.29282 8.29289L0.292817 16.2929L1.70703 17.7071L9.70703 9.70711L8.29282 8.29289Z"
                          fill="#111111"/>
                </svg>
            </div>
            <h3>Таблица размеров</h3>
            <p>Одежда других брендов может немного отличаться от приведенных ниже замеров. Тем не менее вы можете пользоваться таблицей в качестве ориентира.</p>
            <table>
                <tr>
                    <th>Размер</th>
                    <th>Обхват груди</th>
                    <th>Шея</th>
                </tr>
                <tr>
                    <td>XXXS</td>
                    <td>76-81</td>
                    <td>36</td>
                </tr>
                <tr>
                    <td>XXS</td>
                    <td>81-86</td>
                    <td>37.5</td>
                </tr>
                <tr>
                    <td>XS</td>
                    <td>86-91</td>
                    <td>38.5</td>
                </tr>
                <tr>
                    <td>S</td>
                    <td>91-96</td>
                    <td>39.5</td>
                </tr>
                <tr>
                    <td>M</td>
                    <td>96-102</td>
                    <td>41.5</td>
                </tr>
                <tr>
                    <td>L</td>
                    <td>102-107</td>
                    <td>43.5</td>
                </tr>
                <tr>
                    <td>XL</td>
                    <td>107-112</td>
                    <td>45.5</td>
                </tr>
                <tr>
                    <td>XXL</td>
                    <td>112-117</td>
                    <td>47.5</td>
                </tr>
                <tr>
                    <td>3XL</td>
                    <td>117-122</td>
                    <td>49.5</td>
                </tr>
                <tr>
                    <td>4XL</td>
                    <td>122-127</td>
                    <td>51.5</td>
                </tr>
                <tr>
                    <td>5XL</td>
                    <td>127-132</td>
                    <td>53.5</td>
                </tr>
                <tr>
                    <td>6XL</td>
                    <td>132-137</td>
                    <td>55.5</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS( 'CT_BCE_CATALOG_ECONOMY_INFO2' )?>',
            TITLE_ERROR: '<?=GetMessageJS( 'CT_BCE_CATALOG_TITLE_ERROR' )?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS( 'CT_BCE_CATALOG_TITLE_BASKET_PROPS' )?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS( 'CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR' )?>',
            BTN_SEND_PROPS: '<?=GetMessageJS( 'CT_BCE_CATALOG_BTN_SEND_PROPS' )?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS( 'CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT' )?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS( 'CT_BCE_CATALOG_BTN_MESSAGE_CLOSE' )?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS( 'CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP' )?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS( 'CT_BCE_CATALOG_ADD_TO_BASKET_OK' )?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS( 'CT_BCE_CATALOG_MESS_COMPARE_OK' )?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS( 'CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR' )?>',
            COMPARE_TITLE: '<?=GetMessageJS( 'CT_BCE_CATALOG_MESS_COMPARE_TITLE' )?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS( 'CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT' )?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS( 'CT_BCE_CATALOG_PRODUCT_GIFT_LABEL' )?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS( 'CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX' )?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape( $arParams['MESS_RELATIVE_QUANTITY_MANY'] )?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape( $arParams['MESS_RELATIVE_QUANTITY_FEW'] )?>',
            SITE_ID: '<?=CUtil::JSEscape( $component->getSiteId() )?>'
        });

        var <?=$obName?> =
        new JCCatalogElement(<?=CUtil::PhpToJSObject( $jsParams, false, true )?>);

        $(document).ready(function () {
            $('[data-fancybox="product-slider"]').fancybox({
                toolbar: false,
                animationEffect: false,
                loop: true,
                btnTpl: {
                    arrowLeft:
                        '<button data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" title="{{PREV}}">' +
                        '<div><svg width="17" height="32" viewBox="0 0 17 32" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="M1.00031 1.71436L15.286 16.0001L1.00031 30.2858" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>\n' +
                        '</svg>\n</div>' +
                        "</button>",

                    arrowRight:
                        '<button data-fancybox-next class="fancybox-button fancybox-button--arrow_right" title="{{NEXT}}">' +
                        '<div><svg width="17" height="32" viewBox="0 0 17 32" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="M1.00031 1.71436L15.286 16.0001L1.00031 30.2858" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>\n' +
                        '</svg>\n</div>' +
                        "</button>",

                    close:
                        '<button data-fancybox-close class="fancybox-button fancybox-button--close" title="{{CLOSE}}">' +
                        '<div><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="M0.646447 1.35355L8.64645 9.35355L9.35355 8.64645L1.35355 0.646447L0.646447 1.35355ZM8.64645 9.35355L16.6464 17.3536L17.3536 16.6464L9.35355 8.64645L8.64645 9.35355ZM16.6464 0.646447L8.64645 8.64645L9.35355 9.35355L17.3536 1.35355L16.6464 0.646447ZM8.64645 8.64645L0.646447 16.6464L1.35355 17.3535L9.35355 9.35355L8.64645 8.64645Z" fill="#111111"/>\n' +
                        '</svg>\n' +
                        "</div></button>"
                },
                buttons : [
                    'close'
                ]
            });
        });
    </script>

<?
//unset($actualItem, $itemIds, $jsParams);