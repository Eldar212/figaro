<? if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

CModule::IncludeModule( "iblock" );

use Bitrix\Main\Page\Asset;

include $_SERVER['DOCUMENT_ROOT'] . '/include/telephone.php';

// Переменные с id верхних категорий каталога и раздела баннеров
$gender_catalog_ids = array( 'men' => 24, 'woman' => 25, 'kids' => 26 );
$gender_banner_ids  = array( 'men' => 34, 'woman' => 35, 'kids' => 36 );

$curPage = $APPLICATION->GetCurPage( true );

// Записываем текущий гендер в переменную $gender
// Информацию берем из url (настроено в параметрах обработки адресов), или, если есть, из cookie
if ( isset( $_GET['GENDER'] ) ) {

	$gender = $_GET['GENDER'];

} else {

	if ( isset( $_COOKIE['GENDER'] ) ) {
		$gender = $_COOKIE['GENDER'];
	} else {
		$gender = 'woman';
	}

}

// Добавляем гендер в cookie, если отсутствует
if ( $gender != '' ) {

	if ( ! isset( $_COOKIE['GENDER'] ) ) {
		setcookie( "GENDER", $gender, strtotime( '+1 month' ), '/', $_SERVER['HTTP_HOST'] );
	} else {
		if ( $gender != $_COOKIE['GENDER'] ) {
			setcookie( "GENDER", $gender, strtotime( '+1 month' ), '/', $_SERVER['HTTP_HOST'] );
		}
	}

}

// Получаем гендер из текущего каталога или товара
// Информация из url (настроено в параметрах обработки адресов)
if ( isset( $_GET['CATALOG_SECTION_CODE'] ) ) {

	$section_id = CIBlockFindTools::GetSectionID(
		0,
		$_GET['CATALOG_SECTION_CODE'],
		array( "IBLOCK_ID" => 2 )
	);

	$sResult         = CIBlockSection::GetByID( $section_id );
	$catalog_section = $sResult->GetNext();

	if ( $catalog_section['IBLOCK_SECTION_ID'] ) {
		foreach ( $gender_catalog_ids as $key => $value ) {
			if ( $value == $catalog_section['IBLOCK_SECTION_ID'] ) {
				$gender = $key;
			}
		}
	}

}

if ($gender == '') {
    $gender = 'women';
}

$without_h1_breadcrumb = $without_h1_breadcrumb == true ? $without_h1_breadcrumb : false;

CJSCore::Init( array( "fx" ) );
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= htmlspecialcharsbx( SITE_DIR ) ?>favicon.ico"/>
	<?

	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/colors.css", true );
	Asset::getInstance()->addCss( "/bitrix/css/bootstrap/bootstrap.css" );
	Asset::getInstance()->addCss( "/bitrix/css/main/font-awesome.css" );
    Asset::getInstance()->addCss("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap");
	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/assets/libs/flickity/flickity.min.css" );
	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/assets/css/styles.css" );
	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/assets/css/mobile-styles.css" );
	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/assets/fonts/opensans/opensans.css" );
	Asset::getInstance()->addCss( SITE_TEMPLATE_PATH . "/assets/fonts/cormorantgaramond/cormorantgaramond.css" );
    Asset::getInstance()->addCss("https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap");

	CJSCore::Init( array( 'jquery2' ) );
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/libs/flickity/flickity.pkgd.min.js' );
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/js/popper.js' );
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/libs/bootstrap/bootstrap.min.js' );
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/js/scripts.js' );
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/js/imask.min.js' );

	// Before login
	Asset::getInstance()->addJs( SITE_TEMPLATE_PATH . '/assets/js/auth.js' );

	$APPLICATION->ShowHead();

	?>

</head>
<body class="bx-background-image <? if ( $USER->IsAdmin() )
	echo 'admin-panel' ?>">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div class="menu-mobile-wrapper">
    <div class="menu-mobile">
        <? $APPLICATION->IncludeComponent( "bitrix:menu", "catalog_idilika", array(
            "ROOT_MENU_TYPE"        => "left",    // Тип меню для первого уровня
            "MENU_CACHE_TYPE"       => "A",    // Тип кеширования
            "MENU_CACHE_TIME"       => "36000000",    // Время кеширования (сек.)
            "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
            "MENU_THEME"            => "site",    // Тема меню
            "CACHE_SELECTED_ITEMS"  => "N",
            "MENU_CACHE_GET_VARS"   => "",    // Значимые переменные запроса
            "MAX_LEVEL"             => "3",    // Уровень вложенности меню
            "CHILD_MENU_TYPE"       => "left",    // Тип меню для остальных уровней
            "USE_EXT"               => "Y",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
            "DELAY"                 => "N",    // Откладывать выполнение шаблона меню
            "ALLOW_MULTI_SELECT"    => "N",    // Разрешить несколько активных пунктов одновременно
        ),
            false
        ); ?>

            <? $APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket.line",
                "login_out",
                array(
                    "PATH_TO_BASKET"       => SITE_DIR . "personal/cart/",
                    "PATH_TO_PERSONAL"     => SITE_DIR . "personal/",
                    "SHOW_PERSONAL_LINK"   => "N",
                    "SHOW_NUM_PRODUCTS"    => "Y",
                    "SHOW_TOTAL_PRICE"     => "Y",
                    "SHOW_PRODUCTS"        => "N",
                    "POSITION_FIXED"       => "N",
                    "SHOW_AUTHOR"          => "Y",
                    "PATH_TO_REGISTER"     => SITE_DIR . "registration/",
                    "PATH_TO_PROFILE"      => SITE_DIR . "personal/",
                    "COMPONENT_TEMPLATE"   => "login_out",
                    "PATH_TO_ORDER"        => SITE_DIR . "personal/order/make/",
                    "SHOW_EMPTY_VALUES"    => "Y",
                    "PATH_TO_AUTHORIZE"    => "",
                    "SHOW_REGISTRATION"    => "Y",
                    "HIDE_ON_BASKET_PAGES" => "Y"
                ),
                false
            ); ?>
    </div>
</div>

<div class="bx-wrapper" id="bx_eshop_wrap">
    <header class="bx-header">
        <div class="bx-header-section main-head">
            <div class="container">
                <div class="row vertical-center main-head_elements horizontal-correct">

                    <div class="logo">

                        <div class="bx-logo nomobile">
                            <a class="bx-logo-block hidden-xs" href="/">
								<? $APPLICATION->IncludeComponent( "bitrix:main.include", "", array(
									"AREA_FILE_SHOW" => "file",
									"PATH"           => SITE_DIR . "include/company_logo.php"
								), false ); ?>
                            </a>
                        </div>

                        <div class="inmobile header-mobile flex">
                            <div>
                                <div class="header-mobile-item">
                                    <div class="menu_button">
                                    </div>
                                </div>
                                <div class="header-mobile-item">
                                    <div class="logo-mobile">
                                        <a href="/">
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/images/logo.svg" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="header-mobile-item">
                                    <div class="search-button">
                                        <a href="/search/"><img src="<?= SITE_TEMPLATE_PATH ?>/images/search.svg" alt=""></a>
                                    </div>
                                </div>
                                <div class="header-mobile-item">
                                    <div class="favorites-button">
                                        <a href="/personal/favorites/"><img src="<?= SITE_TEMPLATE_PATH ?>/images/like_icon.svg" alt=""></a>
                                    </div>
                                </div>
                                <div class="header-mobile-item">
                                    <div class="basket-button">
                                        <a href="/personal/cart/"><img src="<?= SITE_TEMPLATE_PATH ?>/images/shop_cart.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="switch-gender">
                        <div class="bx-inc-orginfo">
                            <div class="dd-menu">
                                    <button class="dd-menu-button">
									<?=$genders[$gender]['NAME']?>
                                </button>
                                <ul class="dd-menu-list">
                                    <?foreach ($genders as $key => $item):?>
                                    <?if ($gender == $key || empty($gender)) {$active = ' active';}?>
                                    <li><a class="dd-menu-list-item<?=$active?>" href="/<?=$key?>-home/"><?=$item['NAME']?></a>
                                    <?endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="search-block">
                        <div class="bx-worktime">
                            <div class="bx-worktime-prop">
								<? $APPLICATION->IncludeComponent( "bitrix:search.title", "visual", array(
									"NUM_CATEGORIES"            => "1",
									"TOP_COUNT"                 => "5",
									"CHECK_DATES"               => "N",
									"SHOW_OTHERS"               => "N",
									"PAGE"                      => SITE_DIR . "search/",
									"CATEGORY_0_TITLE"          => GetMessage( "SEARCH_GOODS" ),
									"CATEGORY_0"                => array(
										0 => "iblock_catalog",
									),
									"CATEGORY_0_iblock_catalog" => array(
										0 => "all",
									),
									"CATEGORY_OTHERS_TITLE"     => GetMessage( "SEARCH_OTHER" ),
									"SHOW_INPUT"                => "Y",
									"INPUT_ID"                  => "title-search-input",
									"CONTAINER_ID"              => "search",
									"PRICE_CODE"                => array(
										0 => "BASE",
									),
									"SHOW_PREVIEW"              => "Y",
									"PREVIEW_WIDTH"             => "75",
									"PREVIEW_HEIGHT"            => "75",
									"CONVERT_CURRENCY"          => "Y"
								),
									false
								); ?>
								<? //$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
                            </div>
                        </div>
                    </div>
                    <a href="<?= $phone ?>" class="number"><span><?= $phone ?></span>
                    </a>
                    <div class="account hidden-xs authentication-width">
						<? $APPLICATION->IncludeComponent(
							"bitrix:sale.basket.basket.line",
							"login_out",
							array(
								"PATH_TO_BASKET"       => SITE_DIR . "personal/cart/",
								"PATH_TO_PERSONAL"     => SITE_DIR . "personal/",
								"SHOW_PERSONAL_LINK"   => "N",
								"SHOW_NUM_PRODUCTS"    => "Y",
								"SHOW_TOTAL_PRICE"     => "Y",
								"SHOW_PRODUCTS"        => "N",
								"POSITION_FIXED"       => "N",
								"SHOW_AUTHOR"          => "Y",
								"PATH_TO_REGISTER"     => SITE_DIR . "registration/",
								"PATH_TO_PROFILE"      => SITE_DIR . "personal/",
								"COMPONENT_TEMPLATE"   => "login_out",
								"PATH_TO_ORDER"        => SITE_DIR . "personal/order/make/",
								"SHOW_EMPTY_VALUES"    => "Y",
								"PATH_TO_AUTHORIZE"    => "",
								"SHOW_REGISTRATION"    => "Y",
								"HIDE_ON_BASKET_PAGES" => "Y"
							),
							false
						); ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="bx-header-section mobile_none">
            <div class="container all_menu">
                <div class="row category-list">
                    <div class="col-md-9 hidden-xs ">
						<? $APPLICATION->IncludeComponent( "bitrix:menu", "catalog_idilika", array(
							"ROOT_MENU_TYPE"        => "top",    // Тип меню для первого уровня
							"MENU_CACHE_TYPE"       => "A",    // Тип кеширования
							"MENU_CACHE_TIME"       => "36000000",    // Время кеширования (сек.)
							"MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
							"MENU_THEME"            => "site",    // Тема меню
							"CACHE_SELECTED_ITEMS"  => "N",
							"MENU_CACHE_GET_VARS"   => "",    // Значимые переменные запроса
							"MAX_LEVEL"             => "3",    // Уровень вложенности меню
							"CHILD_MENU_TYPE"       => "left",    // Тип меню для остальных уровней
							"USE_EXT"               => "Y",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
							"DELAY"                 => "N",    // Откладывать выполнение шаблона меню
							"ALLOW_MULTI_SELECT"    => "N",    // Разрешить несколько активных пунктов одновременно
						),
							false
						); ?>
                    </div>
                    <div class="col-md-3 hidden-xs col-md-correct">
                        <div class="top-basket-inline">
							<? $APPLICATION->IncludeComponent( "bitrix:sale.basket.basket.line",
							 "basket", array(
								"PATH_TO_BASKET"     => SITE_DIR . "personal/cart/",    // Страница корзины
								"PATH_TO_PERSONAL"   => SITE_DIR . "personal/",    // Страница персонального раздела
								"SHOW_PERSONAL_LINK" => "N",    // Отображать персональный раздел
								"SHOW_NUM_PRODUCTS"  => "Y",    // Показывать количество товаров
								"SHOW_TOTAL_PRICE"   => "Y",    // Показывать общую сумму по товарам
								"SHOW_PRODUCTS"      => "N",    // Показывать список товаров
								"POSITION_FIXED"     => "N",    // Отображать корзину поверх шаблона
								"SHOW_AUTHOR"        => "Y",    // Добавить возможность авторизации
								"PATH_TO_REGISTER"   => SITE_DIR . "registration/",    // Страница регистрации
								"PATH_TO_PROFILE"    => SITE_DIR . "personal/",    // Страница профиля
							),
								false
							); ?>
                        </div>
                        <div class="top-basket-inline vertical_line"></div>
                        <div class="top-basket-inline">

                        <?

                            use Bitrix\Main\Loader;

                            Loader::includeModule( "sale" );
                            $delaydBasketItems = CSaleBasket::GetList(
                                array(),
                                array(
                                    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                    "LID"      => SITE_ID,
                                    "ORDER_ID" => "NULL",
                                    "DELAY"    => "Y"
                                ),
                                array()
                            );

                            ?>

                            <a href="/personal/favorites/"><img src="<?= SITE_TEMPLATE_PATH . '/images/like_icon.svg' ?>" alt=""><span id="wishcount" class="fav_count"><?if ( $delaydBasketItems ) { ?><?=trim($delaydBasketItems)?><? } ?></span></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="workarea">
        <div class="bx-content-seection my_container">
            <div class="bx-content">

                <?php
                if ( !isset($_GET['GENDER'])
                    && !$without_h1_breadcrumb
                    && !CSite::InDir('/index.php')
                    && !CSite::InDir('/catalog/')
                    && !CSite::InDir('/news/')
                    && !CSite::InDir('/personal/')) { ?>

                    <div class="container">
                        <div class="row flex head_breadcrumb">
                            <h1 class="col-lg-7 col-md-7" id="pagetitle"><? $APPLICATION->ShowTitle(); ?></h1>
                            <div class="col-lg-5 col-md-5" id="navigation">
                                <? $APPLICATION->IncludeComponent( "bitrix:breadcrumb", "", array(
                                    "START_FROM" => "0",
                                    "PATH"       => "",
                                    "SITE_ID"    => "-"
                                ),
                                    false,
                                    array( 'HIDE_ICONS' => 'Y' )
                                ); ?>
                            </div>
                        </div>
                    </div>

                <? } ?>