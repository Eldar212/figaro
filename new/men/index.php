<?
define( "HIDE_SIDEBAR", true );
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle( "Новинки" );
$APPLICATION->SetPageProperty( "title", "Новинки" );

$arrFilter = array("PROPERTY_NEWPRODUCT" => 1);

?>
    <div class="catalog_page container">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="bx-sidebar-block bx-smartfilter-wrapper nomobile">
					<? $APPLICATION->IncludeComponent(
						"bitrix:catalog.smart.filter",
						"",
						Array(
							"CACHE_GROUPS"          => "Y",
							"CACHE_TIME"            => "36000000",
							"CACHE_TYPE"            => "A",
							"CONVERT_CURRENCY"      => "N",
							"DISPLAY_ELEMENT_COUNT" => "Y",
							"FILTER_NAME"           => "arrFilter",
							"FILTER_VIEW_MODE"      => "vertical",
							"HIDE_NOT_AVAILABLE"    => "N",
							"IBLOCK_ID"             => 2,
							"IBLOCK_TYPE"           => "catalog",
							"PAGER_PARAMS_NAME"     => "arrPager",
							"PREFILTER_NAME"        => "smartPreFilter",
							"PRICE_CODE"            => array(),
							"SAVE_IN_SESSION"       => "N",
							"SECTION_CODE"          => "",
							"SECTION_DESCRIPTION"   => "-",
							"SECTION_ID"            => 24,
							"SECTION_TITLE"         => "-",
							"SEF_MODE"              => "N",
							"TEMPLATE_THEME"        => "site",
							"XML_EXPORT"            => "N",
							"NEWPRODUCT"            => "Y"
						),
						$component,
						Array(
							'HIDE_ICONS' => 'Y'
						)
					); ?>
                </div>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="row row-catalog-mobile">
					<? $APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						".default",
						array(
							"ACTION_VARIABLE"                 => "action",
							"ADD_PICT_PROP"                   => "-",
							"ADD_PROPERTIES_TO_BASKET"        => "Y",
							"ADD_SECTIONS_CHAIN"              => "N",
							"ADD_TO_BASKET_ACTION"            => "ADD",
							"AJAX_MODE"                       => "N",
							"AJAX_OPTION_ADDITIONAL"          => "",
							"AJAX_OPTION_HISTORY"             => "N",
							"AJAX_OPTION_JUMP"                => "N",
							"AJAX_OPTION_STYLE"               => "Y",
							"BACKGROUND_IMAGE"                => "-",
							"BASKET_URL"                      => "/personal/basket.php",
							"BROWSER_TITLE"                   => "-",
							"CACHE_FILTER"                    => "N",
							"CACHE_GROUPS"                    => "Y",
							"CACHE_TIME"                      => "36000000",
							"CACHE_TYPE"                      => "A",
							"COMPATIBLE_MODE"                 => "Y",
							"COMPONENT_TEMPLATE"              => ".default",
							"CONVERT_CURRENCY"                => "N",
							"CUSTOM_FILTER"                   => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:2:6\",\"DATA\":{\"logic\":\"Equal\",\"value\":1}}]}",
							"DETAIL_URL"                      => "",
							"DISABLE_INIT_JS_IN_COMPONENT"    => "N",
							"DISPLAY_BOTTOM_PAGER"            => "N",
							"DISPLAY_COMPARE"                 => "N",
							"DISPLAY_TOP_PAGER"               => "N",
							"ELEMENT_SORT_FIELD"              => "sort",
							"ELEMENT_SORT_FIELD2"             => "id",
							"ELEMENT_SORT_ORDER"              => "asc",
							"ELEMENT_SORT_ORDER2"             => "desc",
							"ENLARGE_PRODUCT"                 => "STRICT",
							"FILTER_NAME"                     => "arrFilter",
							"HIDE_NOT_AVAILABLE"              => "N",
							"HIDE_NOT_AVAILABLE_OFFERS"       => "N",
							"HIDE_SECTION_DESCRIPTION"        => "Y",
							"IBLOCK_ID"                       => "2",
							"IBLOCK_TYPE"                     => "catalog",
							"INCLUDE_SUBSECTIONS"             => "A",
							"LABEL_PROP"                      => array(),
							"LAZY_LOAD"                       => "Y",
							"LINE_ELEMENT_COUNT"              => "3",
							"LOAD_ON_SCROLL"                  => "N",
							"MESSAGE_404"                     => "",
							"MESS_BTN_ADD_TO_BASKET"          => "В корзину",
							"MESS_BTN_BUY"                    => "Купить",
							"MESS_BTN_DETAIL"                 => "Подробнее",
							"MESS_BTN_SUBSCRIBE"              => "Подписаться",
							"MESS_NOT_AVAILABLE"              => "Нет в наличии",
							"META_DESCRIPTION"                => "-",
							"META_KEYWORDS"                   => "-",
							"OFFERS_FIELD_CODE"               => array(
								0 => "",
								1 => "",
							),
							"OFFERS_LIMIT"                    => "5",
							"OFFERS_SORT_FIELD"               => "sort",
							"OFFERS_SORT_FIELD2"              => "id",
							"OFFERS_SORT_ORDER"               => "asc",
							"OFFERS_SORT_ORDER2"              => "desc",
							"PAGER_BASE_LINK_ENABLE"          => "N",
							"PAGER_DESC_NUMBERING"            => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL"                  => "N",
							"PAGER_SHOW_ALWAYS"               => "N",
							"PAGER_TEMPLATE"                  => ".default",
							"PAGER_TITLE"                     => "Товары",
							"PAGE_ELEMENT_COUNT"              => "18",
							"PARTIAL_PRODUCT_PROPERTIES"      => "N",
							"PRICE_CODE"                      => array(
								0 => "BASE",
							),
							"PRICE_VAT_INCLUDE"               => "N",
							"PRODUCT_BLOCKS_ORDER"            => "price,props,sku,quantityLimit,quantity,buttons",
							"PRODUCT_DISPLAY_MODE"            => "Y",
							"PRODUCT_ID_VARIABLE"             => "id",
							"PRODUCT_PROPS_VARIABLE"          => "prop",
							"PRODUCT_QUANTITY_VARIABLE"       => "quantity",
							"PRODUCT_ROW_VARIANTS"            => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
							"PRODUCT_SUBSCRIPTION"            => "Y",
							"PROPERTY_CODE_MOBILE"            => array(),
							"RCM_PROD_ID"                     => $_REQUEST["PRODUCT_ID"],
							"RCM_TYPE"                        => "personal",
							"SECTION_CODE"                    => "",
							"SECTION_ID"                      => "24",
							"SECTION_ID_VARIABLE"             => "SECTION_ID",
							"SECTION_URL"                     => "",
							"SECTION_USER_FIELDS"             => array(
								0 => "",
								1 => "",
							),
							"SEF_MODE"                        => "N",
							"SET_BROWSER_TITLE"               => "N",
							"SET_LAST_MODIFIED"               => "N",
							"SET_META_DESCRIPTION"            => "N",
							"SET_META_KEYWORDS"               => "N",
							"SET_STATUS_404"                  => "N",
							"SET_TITLE"                       => "Y",
							"SHOW_404"                        => "N",
							"SHOW_ALL_WO_SECTION"             => "N",
							"SHOW_CLOSE_POPUP"                => "N",
							"SHOW_DISCOUNT_PERCENT"           => "N",
							"SHOW_FROM_SECTION"               => "N",
							"SHOW_MAX_QUANTITY"               => "N",
							"SHOW_OLD_PRICE"                  => "N",
							"SHOW_PRICE_COUNT"                => "0",
							"SHOW_SLIDER"                     => "N",
							"SLIDER_INTERVAL"                 => "3000",
							"SLIDER_PROGRESS"                 => "N",
							"TEMPLATE_THEME"                  => "blue",
							"USE_ENHANCED_ECOMMERCE"          => "N",
							"USE_MAIN_ELEMENT_SECTION"        => "N",
							"USE_PRICE_COUNT"                 => "N",
							"USE_PRODUCT_QUANTITY"            => "N",
							"MESS_BTN_LAZY_LOAD"              => "Показать ещё",
							"CURRENCY_ID"                     => "RUB",
							"OFFER_ADD_PICT_PROP"             => "-"
						),
						$component
					); ?>
                </div>
            </div>
        </div>
    </div>
    <br><? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>