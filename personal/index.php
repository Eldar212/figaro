<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Личный кабинет");
$APPLICATION->SetTitle("Персональный раздел");

if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}

?>
<div class="container">
    <div class="row flex head_breadcrumb">
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
    <?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.section",
	"personal",
	array(
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => array(
			0 => "100",
			1 => "200",
			2 => "500",
			3 => "1000",
			4 => "5000",
			5 => "",
		),
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_RIGHTS_PRIVATE" => "N",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"CUSTOM_PAGES" => "[[\"/personal/favorites/\",\"Избранное\",\"fa-heart\"]]",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"NAV_TEMPLATE" => "",
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "N",
			1 => "P",
			2 => "F",
		),
		"PATH_TO_BASKET" => "/personal/cart",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_CONTACT" => "/about/contacts",
		"PATH_TO_PAYMENT" => "/personal/order/payment",
		"PER_PAGE" => "20",
		"PROP_1" => array(
		),
		"PROP_2" => array(
		),
		"SAVE_IN_SESSION" => "Y",
		"SEF_FOLDER" => "/personal/",
		"SEF_MODE" => "Y",
		"SEND_INFO_PRIVATE" => "N",
		"SET_TITLE" => "N",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAGE" => "N",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"SHOW_BASKET_PAGE" => "N",
		"SHOW_CONTACT_PAGE" => "N",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "N",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"SHOW_SUBSCRIBE_PAGE" => "N",
		"USER_PROPERTY_PRIVATE" => "",
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => "personal",
		"ORDER_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"ORDER_DEFAULT_SORT" => "STATUS",
		"ORDER_REFRESH_PRICES" => "N",
		"ORDER_DISALLOW_CANCEL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"PROFILES_PER_PAGE" => "20",
		"MAIN_CHAIN_NAME" => "Мой кабинет",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"orders" => "orders/",
			"account" => "account/",
			"subscribe" => "subscribe/",
			"profile" => "profiles/",
			"profile_detail" => "profiles/#ID#",
			"private" => "private/",
			"order_detail" => "orders/#ID#",
			"order_cancel" => "cancel/#ID#",
		)
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>