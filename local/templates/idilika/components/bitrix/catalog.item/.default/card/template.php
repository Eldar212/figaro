<? if ( ! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

global $gender;

?>

<div class="product-item">
	<? if ( $itemHasDetailUrl ): ?>
    <a class="product-item-image-wrapper" href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $imgTitle ?>"
       data-entity="image-wrapper">
		<? else: ?>
        <span class="product-item-image-wrapper" data-entity="image-wrapper">
	<? endif; ?>
			<? if ( false ): // Слайдер, отключаем ?>
                <span class="product-item-image-slider-slide-container slide" id="<?= $itemIds['PICT_SLIDER'] ?>"
			<?= ( $showSlider ? '' : 'style="display: none;"' ) ?>
			data-slider-interval="<?= $arParams['SLIDER_INTERVAL'] ?>" data-slider-wrap="true">
			<?
			if ( $showSlider ) {
				foreach ( $morePhoto as $key => $photo ) {
					?>
                    <span class="product-item-image-slide item <?= ( $key == 0 ? 'active' : '' ) ?>"
                          style="background-image: url('<?= $photo['SRC'] ?>');">
					</span>
					<?
				}
			}
			?>
		</span>
			<? endif; ?>

			<?
			$preview = CFile::ResizeImageGet( $item['PREVIEW_PICTURE']['ID'], array( "width" => 330, 'height' => 410 ), BX_RESIZE_IMAGE_EXACT, true );

			if ( ! isset( $preview['src'] ) ) {
				$preview = $item['PREVIEW_PICTURE']['SRC'];
			} else {
				$preview = $preview['src'];
			}
			?>
		<div class="product-item-image-original" id="<?= $itemIds['PICT'] ?>"
             style="background-image: url('<?= $preview ?>'); <?= ( $showSlider ? 'display: none;' : '' ) ?>">
		</div>
		<?
		if ( false /*$item['SECOND_PICT']*/ ) // Вторая картинка при наведении (убрал)
		{
			$bgImage = ! empty( $item['PREVIEW_PICTURE_SECOND'] ) ? $item['PREVIEW_PICTURE_SECOND']['SRC'] : $item['PREVIEW_PICTURE']['SRC'];
			?>
            <span class="product-item-image-alternative" id="<?= $itemIds['SECOND_PICT'] ?>"
                  style="background-image: url('<?= $bgImage ?>'); <?= ( $showSlider ? 'display: none;' : '' ) ?>">
			</span>
			<?
		}

		if ( $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' ) {
			?>
            <div class="product-item-label-ring <?= $discountPositionClass ?>" id="<?= $itemIds['DSC_PERC'] ?>"
				<?= ( $price['PERCENT'] > 0 ? '' : 'style="display: none;"' ) ?>>
				<span><?= - $price['PERCENT'] ?>%</span>
			</div>
			<?
		}

		if ( $item['LABEL'] ) {
			?>
            <div class="product-item-label-text <?= $labelPositionClass ?>" id="<?= $itemIds['STICKER_ID'] ?>">
				<?
				if ( ! empty( $item['LABEL_ARRAY_VALUE'] ) ) {
					foreach ( $item['LABEL_ARRAY_VALUE'] as $code => $value ) {
						?>
                        <div<?= ( ! isset( $item['LABEL_PROP_MOBILE'][ $code ] ) ? ' class="hidden-xs"' : '' ) ?>>
							<span title="<?= $value ?>"><?= $value ?></span>
						</div>
						<?
					}
				}
				?>
			</div>
			<?
		}
		?>
			<? if ( false ): // Слайдер, отключаем ?>
                <div class="product-item-image-slider-control-container" id="<?= $itemIds['PICT_SLIDER'] ?>_indicator"
			<?= ( $showSlider ? '' : 'style="display: none;"' ) ?>>
			<?
			if ( $showSlider ) {
				foreach ( $morePhoto as $key => $photo ) {
					?>
                    <div class="product-item-image-slider-control<?= ( $key == 0 ? ' active' : '' ) ?>" data-go-to="<?= $key ?>"></div>
					<?
				}
			}
			?>
		</div>
			<? endif; ?>
			<?
			if ( $arParams['SLIDER_PROGRESS'] === 'Y' ) {
				?>
                <div class="product-item-image-slider-progress-bar-container">
				<div class="product-item-image-slider-progress-bar" id="<?= $itemIds['PICT_SLIDER'] ?>_progress_bar" style="width: 0;"></div>
			</div>
				<?
			}
			?>
			<? if ( $itemHasDetailUrl ): ?>
    </a>
<? else: ?>
    </span>
<? endif; ?>

	<? if ( ! empty( $item['PROPERTIES']['BRAND_REF']['UF_NAME'] ) ): ?>
        <div class="product-item__brand-like">
            <div class="product-item-brand">
                <a href="/catalog/<?=$gender?>/filter/brand_ref-is-<?=$item['PROPERTIES']['BRAND_REF']['VALUE']?>/apply/">
                    <?= $item['PROPERTIES']['BRAND_REF']['UF_NAME'] ?>
                </a>
            </div>

            <div class="add_like">
                <a href="javascript:void(0)" class="wishbtn
                                <? if ( in_array( $arResult["ID"], $arBasketItems ) ) {
                    echo 'in_wishlist ';
                } ?>"
                   onclick="add2wish(
                           '<?= $arResult["ITEM"]["ID"] ?>',
                           '<?= $price["ID"] ?>',
                           '<?= $price["PRICE"] ?>',
                           '<?= $arResult["ITEM"]['NAME'] ?>',
                           '<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>',
                           this)">
                    <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z"
                               stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>


	<? endif; ?>

        <div class="product-item-title">
            <? if ( $itemHasDetailUrl ): ?>
            <a href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $productTitle ?>">
                <? endif; ?>
                <?= $productTitle ?>
                <? if ( $itemHasDetailUrl ): ?>
            </a>
        <? endif; ?>
        </div>




	<?
	if ( ! empty( $arParams['PRODUCT_BLOCKS_ORDER'] ) ) {
		foreach ( $arParams['PRODUCT_BLOCKS_ORDER'] as $blockName ) {
			switch ( $blockName ) {
				case 'price': ?>
                    <div class="product-item-info-container product-item-price-container flex" data-entity="price-block">
                        <div>
							<?
							if ( $arParams['SHOW_OLD_PRICE'] === 'Y' ) {
								?>
                                <span class="product-item-price-old" id="<?= $itemIds['PRICE_OLD'] ?>"
                                    <?= ( $price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '' ) ?>>
                                    <?= $price['PRINT_RATIO_BASE_PRICE'] ?>
                                    &nbsp;</span>
								<?
							}
							?>
                            <span class="product-item-price-current" id="<?= $itemIds['PRICE'] ?>">
                                <?
                                if ( ! empty( $price ) ) {
	                                if ( $arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers ) {
		                                echo Loc::getMessage(
			                                'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
			                                array(
				                                '#PRICE#' => $price['PRINT_RATIO_PRICE'],
				                                '#VALUE#' => $measureRatio,
				                                '#UNIT#'  => $minOffer['ITEM_MEASURE']['TITLE']
			                                )
		                                );
	                                } else {
		                                echo $price['PRINT_RATIO_PRICE'];
	                                }
                                }
                                ?>
                            </span>
                        </div>

                    </div>
					<?
					break;
			}
		}
	}

	/*if (
		$arParams['DISPLAY_COMPARE']
		&& (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
	)
	{
		?>
		<div class="product-item-compare-container">
			<div class="product-item-compare">
				<div class="checkbox">
					<label id="<?=$itemIds['COMPARE_LINK']?>">
						<input type="checkbox" data-entity="compare-checkbox">
						<span data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
					</label>
				</div>
			</div>
		</div>
		<?
	}*/
	?>

</div>