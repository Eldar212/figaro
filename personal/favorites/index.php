<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle( "Избранное" );

$dbBasketItems = CSaleBasket::GetList(
	array(
		"NAME" => "ASC",
		"ID"   => "ASC"
	),
	array(
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID"      => SITE_ID,
		"ORDER_ID" => "NULL",
		"DELAY"    => "Y"
	),
	false,
	false,
	array( "ID", "DELAY", "PRODUCT_ID", "PRICE" )
);

if ($USER->IsAuthorized()) {
    $APPLICATION->AddChainItem('Личный кабинет');
} else {
	$APPLICATION->AddChainItem($APPLICATION->GetTitle());
}

?>

<? if ( !empty( $dbBasketItems->arResult ) ) { ?>
    <div class="container">
        <div class="row flex head_breadcrumb hb_fav">
            <h1 class="col-lg-7 col-md-7" id="pagetitle"><a href="/personal/" class="back_link"></a><? $APPLICATION->ShowTitle(); ?></h1>
            <div class="col-lg-5 col-md-5" id="navigation">
				<? $APPLICATION->IncludeComponent( "bitrix:breadcrumb", "", array(
					"START_FROM" => "0",
					"PATH"       => "",
					"SITE_ID"    => "-"
				),
					false,
					Array( 'HIDE_ICONS' => 'Y' )
				); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">


			<? if ($USER->IsAuthorized()) $APPLICATION->IncludeFile(
				SITE_DIR . "personal/left_menu.php",
				array(),
				array()
			); ?>

            <div class="wishlist_list right_personal <?if (!$USER->IsAuthorized()) echo 'not_auth' ?>">
                <table class="fav_table">
					<? if ( \Bitrix\Main\Loader::includeModule( "sale" ) ) {
						$img_link = "";

						while ( $arItems = $dbBasketItems->Fetch() ) {
							$arBasketItems[] = $arItems["PRODUCT_ID"];
							if ( $arItems['DELAY'] == 'Y' ) {
								$res = CIBlockElement::GetList( Array(), Array(
									"LOGIC" => "OR",
									array( "IBLOCK_ID" => 2, "ID" => $arItems['PRODUCT_ID'], "ACTIVE" => "Y" ),
									array( "IBLOCK_ID" => 3, "ID" => $arItems['PRODUCT_ID'], "ACTIVE" => "Y" ),
								), false, Array(), Array( '*', 'CATALOG_GROUP_1' ) ); ?>
								<? if ( $ar_res = $res->GetNext() ): ?>
									<?

									if (!empty($ar_res['PREVIEW_PICTURE'])) {

										$preview = CFile::ResizeImageGet( $ar_res['PREVIEW_PICTURE'], array( "width" => 101, 'height' => 129 ), BX_RESIZE_IMAGE_EXACT, true );

										if ( !isset( $preview['src'] ) ) {
											$img_link = CFile::GetPath( $ar_res['PREVIEW_PICTURE'] );
										} else {
											$img_link = $preview['src'];
										}
									} elseif (!empty($ar_res['DETAIL_PICTURE'])) {
										$preview = CFile::ResizeImageGet( $ar_res['DETAIL_PICTURE'], array( "width" => 101, 'height' => 129 ), BX_RESIZE_IMAGE_EXACT, true );

										$hideDetailPicture = true;
										if ( !isset( $preview['src'] ) ) {
											$img_link = CFile::GetPath( $ar_res['DETAIL_PICTURE'] );
										} else {
											$img_link = $preview['src'];
										}
									}
									?>
                                    <tr class="del_basket_tr basket-items-list-item-container" id="basket-item-<?= $ar_res["ID"] ?>" data-entity="basket-item" data-id="<?= $ar_res["ID"] ?>">
                                        <td class="basket-items-list-item-descriptions">
                                            <div class="basket-items-list-item-descriptions-inner flex fav_img_name" id="basket-item-height-aligner-<?= $ar_res["ID"] ?>">
                                                <div class="basket-item-block-image">
                                                    <a href="<?= $ar_res['DETAIL_PAGE_URL'] ?>" class="basket-item-image-link">
                                                        <div class="imgcontainer" style="background-image: url(<?= $img_link ?>);background-image: -webkit-image-set(url(<?= $img_link ?>) 1x, url(<?= $img_link ?>) 2x)"></div>
                                                    </a>
                                                </div>
                                                <div class="basket-item-block-info">
                                                    <span class="basket-item-actions-remove visible-xs" data-entity="basket-item-delete"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.313439 1.02055L5.64677 6.35388L6.35388 5.64677L1.02055 0.313439L0.313439 1.02055ZM5.64677 6.35388L10.9801 11.6872L11.6872 10.9801L6.35388 5.64677L5.64677 6.35388ZM10.9801 0.313439L5.64677 5.64677L6.35388 6.35388L11.6872 1.02055L10.9801 0.313439ZM5.64677 5.64677L0.313439 10.9801L1.02055 11.6872L6.35388 6.35388L5.64677 5.64677Z" fill="#F14336"></path></svg></span>
                                                    <h2 class="basket-item-info-name">
                                                        <a href="<?= $ar_res['DETAIL_PAGE_URL'] ?>" class="basket-item-info-name-link">
                                                            <span data-entity="basket-item-name"><?= $ar_res['NAME'] ?></span>
                                                        </a>
                                                    </h2>
                                                    <div class="basket-item-detail-info flex inmobile">
                                                        <div><?= FormatCurrency( $ar_res['CATALOG_PRICE_1'], 'RUB' ) ?></div>
                                                        <div><div class="add_basket" data-bask="<?= $arItems['ID'] ?>">В корзину</div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nomobile">
											<?= FormatCurrency( $ar_res['CATALOG_PRICE_1'], 'RUB' ) ?>
                                        </td>
                                        <td class="nomobile">
                                            <div class="add_basket" data-bask="<?= $arItems['ID'] ?>">В корзину</div>
                                        </td>
                                        <td class="basket-items-list-item-remove hidden-xs">
                                            <div class="basket-item-block-actions del_basket" data-bask="<?= $arItems['ID'] ?>">
                                    <span class="basket-item-actions-remove" data-entity="basket-item-delete">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.313439 1.02055L5.64677 6.35388L6.35388 5.64677L1.02055 0.313439L0.313439 1.02055ZM5.64677 6.35388L10.9801 11.6872L11.6872 10.9801L6.35388 5.64677L5.64677 6.35388ZM10.9801 0.313439L5.64677 5.64677L6.35388 6.35388L11.6872 1.02055L10.9801 0.313439ZM5.64677 5.64677L0.313439 10.9801L1.02055 11.6872L6.35388 6.35388L5.64677 5.64677Z"
                                                  fill="#F14336"/>
                                        </svg>
                                    </span>
                                            </div>
                                        </td>
                                    </tr>
								<?endif; ?>
							<?
							}
						}
						$inwished = count( $arBasketItems );
//                echo $inwished;
					}
					?>
					<? if ( $inwished == 0 ): ?>
                        <p class="empty_wishlist">Ничего не найдено.</p>
					<? endif; ?>
                </table>

                <script>
                    $(".add_basket").click(function () {
                        var this_el = $(this);
                        $.ajax({
                            type: "POST",
                            url: "add_basket.php",
                            data: {
                                basket_id: $(this).data("bask")
                            },
                            success: function (msg) {
                                this_el.parents(".del_basket_tr").detach();
                                console.log(msg);
                            }
                        });
                    });

                    $(".del_basket").click(function () {
                        var this_el = $(this);
                        $.ajax({
                            type: "POST",
                            url: "del_basket.php",
                            data: {
                                basket_id: $(this).data("bask")
                            },
                            success: function (msg) {
                                this_el.parents(".del_basket_tr").detach();
                                console.log(msg);
                            }
                        });
                    });
                </script>

				<? /*$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"favorites", 
	array(
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PRICE",
			3 => "QUANTITY",
			4 => "SUM",
			5 => "PROPS",
			6 => "DELETE",
			7 => "DELAY",
		),
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"PATH_TO_ORDER" => "",
		"HIDE_COUPON" => "Y",
		"QUANTITY_FLOAT" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"TEMPLATE_THEME" => "site",
		"SET_TITLE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"OFFERS_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"COMPONENT_TEMPLATE" => "favorites",
		"DEFERRED_REFRESH" => "N",
		"USE_DYNAMIC_SCROLL" => "Y",
		"SHOW_FILTER" => "N",
		"SHOW_RESTORE" => "N",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "TYPE",
			5 => "SUM",
		),
		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "TYPE",
			4 => "SUM",
		),
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "bottom",
		),
		"DISPLAY_MODE" => "compact",
		"PRICE_DISPLAY_MODE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"USE_PRICE_ANIMATION" => "Y",
		"LABEL_PROP" => array(
		),
		"USE_PREPAYMENT" => "N",
		"CORRECT_RATIO" => "N",
		"AUTO_CALCULATION" => "N",
		"ACTION_VARIABLE" => "basketAction",
		"COMPATIBLE_MODE" => "Y",
		"EMPTY_BASKET_HINT_PATH" => "/",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ADDITIONAL_PICT_PROP_3" => "-",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"USE_GIFTS" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N"
	),
	false
);*/ ?>
            </div>

        </div>
    </div>

<? } else { ?>

    <div class="container empty_page">
        <img src="/local/templates/idilika/images/favorites-empty.svg" alt="">
        <p class="empty_title">Пока пусто!</p>
        <span class="empty_subtext">Сохраняй понравившиеся вещи в «Избранное», чтобы потом легко их найти</span>
        <a href="/">
            <button class="btn-black">Начать покупки</button>
        </a>
    </div>

<? } ?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>