<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle( "Интернет-магазин \"Одежда\"" );
?>
    <div class="banner_slide">
		<?
		$detail = "";

		if ( $gender != '' ) {
			$gender_section = $gender_banner_ids[ $gender ];
		} else {
			$gender_section = $gender_banner_ids['woman'];
		}

		$arSelect = array();

		$arFilter = array( "IBLOCK_ID" => 4, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", 'SECTION_ID' => $gender_section );
		$res      = CIBlockElement::GetList( array(), $arFilter, false, array(), $arSelect );
		while ( $ob = $res->GetNextElement() ) {
			$arFields = $ob->GetFields();
			$arProps  = $ob->GetProperties();

			$img = CFile::GetPath( $arFields["PREVIEW_PICTURE"] );

			if ( ! empty( $arProps["READ_MORE_ELS"]["VALUE"] ) ) {
				$res_detail = CIBlockElement::GetByID( $arProps["READ_MORE_ELS"]["VALUE"] );
				if ( $ar_res = $res_detail->GetNext() ) {
					$detail = $ar_res['DETAIL_PAGE_URL'];
				}
			} else if ( ! empty( $arProps["READ_MORE_SECT"]["VALUE"] ) ) {
				$res_detail = CIBlockSection::GetByID( $arProps["READ_MORE_SECT"]["VALUE"] );
				if ( $ar_res = $res_detail->GetNext() ) {
					$detail = $ar_res['SECTION_PAGE_URL'];
				}
			}
			?>
            <a href="<?= $detail ?>" class="banner_el" style="background-image: url('<?= $img ?>')"></a>
		<? } ?>
    </div>

    <div class="flex main_block_index bb">
        <div class="container">
            <div class="flex sections_center">
				<?
				if ( $gender != '' ) {
					$gender_section = $gender_catalog_ids[ $gender ];
				} else {
					$gender_section = $gender_catalog_ids['woman'];
				}

				$arFilter   = array( 'IBLOCK_ID' => 2, 'ACTIVE' => "Y", 'SECTION_ID' => $gender_section );
				$rsSections = CIBlockSection::GetList( array( 'SORT' => 'ASC' ), $arFilter, false, array( "UF_*" ) );
				while ( $arSection = $rsSections->Fetch() ) {
					$cl  = $arSection["UF_WIDTH"];
					$img = CFile::GetPath( $arSection["PICTURE"] );

					if ( $cl == 1 ) {
						$width = 360;
					} elseif ( $cl == 2 ) {
						$width = 555;
					} else {
						$width = 750;
					}

					$img = CFile::ResizeImageGet( $arSection["PICTURE"], array( "width" => $width, "height" => "750" ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true );

					if ( ! isset( $img['src'] ) ) {
						$img = CFile::GetPath( $arSection["PICTURE"] );
					} else {
						$img = $img['src'];
					}

					?>
					<? if ( $img ) { ?>
                        <a href="<?= '/' . $arSection['IBLOCK_TYPE_ID'] . '/' . $arSection['CODE'] . '/' ?>" class="section_block w<?= $cl ?>" style="background-image: url('<?= $img ?>')">
                            <span><?= $arSection["NAME"] ?></span>
                        </a>
					<? } ?>
				<? } ?>
            </div>
        </div>
    </div>


<?
$arFilter   = array( "IBLOCK_ID" => 1, "ACTIVE" => "Y" );
$news_count = CIBlockElement::GetList( array(), $arFilter, array(), false, array() );
?>
<? if ( $news_count > 0 ): ?>
    <div class="news_block bb">
        <div class="container">
            <div class="flex news_head">
                <h2>На этой неделе</h2>
                <a class="all_news_link" href="/news/">Все статьи</a>
            </div>

			<? $APPLICATION->IncludeComponent( "bitrix:news.list", "news_list", array(
				"IBLOCK_TYPE"                     => "news",    // Тип информационного блока (используется только для проверки)
				"IBLOCK_ID"                       => "1",    // Код информационного блока
				"NEWS_COUNT"                      => "6",    // Количество новостей на странице
				"SORT_BY1"                        => "ACTIVE_FROM",    // Поле для первой сортировки новостей
				"SORT_ORDER1"                     => "DESC",    // Направление для первой сортировки новостей
				"SORT_BY2"                        => "SORT",    // Поле для второй сортировки новостей
				"SORT_ORDER2"                     => "ASC",    // Направление для второй сортировки новостей
				"FILTER_NAME"                     => "",    // Фильтр
				"FIELD_CODE"                      => array(    // Поля
					0 => "",
					1 => "",
				),
				"PROPERTY_CODE"                   => array(    // Свойства
					0 => "",
					1 => "",
				),
				"CHECK_DATES"                     => "Y",    // Показывать только активные на данный момент элементы
				"DETAIL_URL"                      => "",    // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
				"AJAX_MODE"                       => "N",    // Включить режим AJAX
				"AJAX_OPTION_SHADOW"              => "Y",
				"AJAX_OPTION_JUMP"                => "N",    // Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE"               => "Y",    // Включить подгрузку стилей
				"AJAX_OPTION_HISTORY"             => "N",    // Включить эмуляцию навигации браузера
				"CACHE_TYPE"                      => "A",    // Тип кеширования
				"CACHE_TIME"                      => "36000000",    // Время кеширования (сек.)
				"CACHE_FILTER"                    => "N",    // Кешировать при установленном фильтре
				"CACHE_GROUPS"                    => "Y",    // Учитывать права доступа
				"PREVIEW_TRUNCATE_LEN"            => "120",    // Максимальная длина анонса для вывода (только для типа текст)
				"ACTIVE_DATE_FORMAT"              => "d.m.Y",    // Формат показа даты
				"DISPLAY_PANEL"                   => "N",
				"SET_TITLE"                       => "N",    // Устанавливать заголовок страницы
				"SET_STATUS_404"                  => "N",    // Устанавливать статус 404
				"INCLUDE_IBLOCK_INTO_CHAIN"       => "N",    // Включать инфоблок в цепочку навигации
				"ADD_SECTIONS_CHAIN"              => "N",    // Включать раздел в цепочку навигации
				"HIDE_LINK_WHEN_NO_DETAIL"        => "N",    // Скрывать ссылку, если нет детального описания
				"PARENT_SECTION"                  => "",    // ID раздела
				"PARENT_SECTION_CODE"             => "",    // Код раздела
				"DISPLAY_NAME"                    => "Y",    // Выводить название элемента
				"DISPLAY_TOP_PAGER"               => "N",    // Выводить над списком
				"DISPLAY_BOTTOM_PAGER"            => "N",    // Выводить под списком
				"PAGER_SHOW_ALWAYS"               => "N",    // Выводить всегда
				"PAGER_TEMPLATE"                  => "",    // Шаблон постраничной навигации
				"PAGER_DESC_NUMBERING"            => "N",    // Использовать обратную навигацию
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",    // Время кеширования страниц для обратной навигации
				"PAGER_SHOW_ALL"                  => "N",    // Показывать ссылку "Все"
				"AJAX_OPTION_ADDITIONAL"          => "",    // Дополнительный идентификатор
				"COMPONENT_TEMPLATE"              => "flat",
				"SET_BROWSER_TITLE"               => "Y",    // Устанавливать заголовок окна браузера
				"SET_META_KEYWORDS"               => "Y",    // Устанавливать ключевые слова страницы
				"SET_META_DESCRIPTION"            => "Y",    // Устанавливать описание страницы
				"SET_LAST_MODIFIED"               => "N",    // Устанавливать в заголовках ответа время модификации страницы
				"INCLUDE_SUBSECTIONS"             => "Y",    // Показывать элементы подразделов раздела
				"DISPLAY_DATE"                    => "Y",    // Выводить дату элемента
				"DISPLAY_PICTURE"                 => "Y",    // Выводить изображение для анонса
				"DISPLAY_PREVIEW_TEXT"            => "Y",    // Выводить текст анонса
				"MEDIA_PROPERTY"                  => "",    // Свойство для отображения медиа
				"SEARCH_PAGE"                     => "/search/",    // Путь к странице поиска
				"USE_RATING"                      => "N",    // Разрешить голосование
				"USE_SHARE"                       => "N",    // Отображать панель соц. закладок
				"PAGER_TITLE"                     => "Новости",    // Название категорий
				"PAGER_BASE_LINK_ENABLE"          => "N",    // Включить обработку ссылок
				"SHOW_404"                        => "N",    // Показ специальной страницы
				"MESSAGE_404"                     => "",    // Сообщение для показа (по умолчанию из компонента)
				"TEMPLATE_THEME"                  => "site",    // Цветовая тема
			),
				false
			);
			?>
        </div>
    </div>
<? endif; ?>


<? $APPLICATION->IncludeComponent( "bitrix:catalog.products.viewed", "viewed", array(
	"ACTION_VARIABLE"            => "action_cpv",    // Название переменной, в которой передается действие
	"ADDITIONAL_PICT_PROP_2"     => "-",    // Дополнительная картинка
	"ADDITIONAL_PICT_PROP_3"     => "-",    // Дополнительная картинка
	"ADD_PROPERTIES_TO_BASKET"   => "Y",    // Добавлять в корзину свойства товаров и предложений
	"ADD_TO_BASKET_ACTION"       => "ADD",    // Показывать кнопку добавления в корзину или покупки
	"BASKET_URL"                 => "/personal/basket.php",    // URL, ведущий на страницу с корзиной покупателя
	"CACHE_GROUPS"               => "Y",    // Учитывать права доступа
	"CACHE_TIME"                 => "3600",    // Время кеширования (сек.)
	"CACHE_TYPE"                 => "A",    // Тип кеширования
	"CONVERT_CURRENCY"           => "N",    // Показывать цены в одной валюте
	"DEPTH"                      => "2",    // Максимальная отображаемая глубина разделов
	"DISPLAY_COMPARE"            => "N",    // Разрешить сравнение товаров
	"ENLARGE_PRODUCT"            => "STRICT",    // Выделять товары в списке
	"HIDE_NOT_AVAILABLE"         => "N",    // Недоступные товары
	"HIDE_NOT_AVAILABLE_OFFERS"  => "N",    // Недоступные торговые предложения
	"IBLOCK_ID"                  => "2",    // Инфоблок
	"IBLOCK_MODE"                => "single",    // Показывать товары из
	"IBLOCK_TYPE"                => "catalog",    // Тип инфоблока
	"LABEL_PROP_2"               => "",    // Свойство меток товара
	"LABEL_PROP_3"               => "",    // Свойство меток товара
	"LABEL_PROP_POSITION"        => "top-left",    // Расположение меток товара
	"MESS_BTN_ADD_TO_BASKET"     => "В корзину",    // Текст кнопки "Добавить в корзину"
	"MESS_BTN_BUY"               => "Купить",    // Текст кнопки "Купить"
	"MESS_BTN_DETAIL"            => "Подробнее",    // Текст кнопки "Подробнее"
	"MESS_BTN_SUBSCRIBE"         => "Подписаться",    // Текст кнопки "Уведомить о поступлении"
	"MESS_NOT_AVAILABLE"         => "Нет в наличии",    // Сообщение об отсутствии товара
	"PAGE_ELEMENT_COUNT"         => "9",    // Количество элементов на странице
	"PARTIAL_PRODUCT_PROPERTIES" => "N",    // Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
	"PRICE_CODE"                 => "",    // Тип цены
	"PRICE_VAT_INCLUDE"          => "Y",    // Включать НДС в цену
	"PRODUCT_BLOCKS_ORDER"       => "price,props,sku,quantityLimit,quantity,buttons",    // Порядок отображения блоков товара
	"PRODUCT_ID_VARIABLE"        => "id",    // Название переменной, в которой передается код товара для покупки
	"PRODUCT_PROPS_VARIABLE"     => "prop",    // Название переменной, в которой передаются характеристики товара
	"PRODUCT_QUANTITY_VARIABLE"  => "quantity",    // Название переменной, в которой передается количество товара
	"PRODUCT_ROW_VARIANTS"       => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",    // Вариант отображения товаров
	"PRODUCT_SUBSCRIPTION"       => "Y",    // Разрешить оповещения для отсутствующих товаров
	"SECTION_CODE"               => "",    // Код раздела
	"SECTION_ELEMENT_CODE"       => "",    // Символьный код элемента, для которого будет выбран раздел
	"SECTION_ELEMENT_ID"         => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],    // ID элемента, для которого будет выбран раздел
	"SECTION_ID"                 => $GLOBALS["CATALOG_CURRENT_SECTION_ID"],    // ID раздела
	"SHOW_CLOSE_POPUP"           => "N",    // Показывать кнопку продолжения покупок во всплывающих окнах
	"SHOW_DISCOUNT_PERCENT"      => "N",    // Показывать процент скидки
	"SHOW_FROM_SECTION"          => "N",    // Показывать товары из раздела
	"SHOW_MAX_QUANTITY"          => "N",    // Показывать остаток товара
	"SHOW_OLD_PRICE"             => "N",    // Показывать старую цену
	"SHOW_PRICE_COUNT"           => "1",    // Выводить цены для количества
	"SHOW_SLIDER"                => "Y",    // Показывать слайдер для товаров
	"SLIDER_INTERVAL"            => "3000",    // Интервал смены слайдов, мс
	"SLIDER_PROGRESS"            => "N",    // Показывать полосу прогресса
	"TEMPLATE_THEME"             => "blue",    // Цветовая тема
	"USE_ENHANCED_ECOMMERCE"     => "N",    // Отправлять данные электронной торговли в Google и Яндекс
	"USE_PRICE_COUNT"            => "N",    // Использовать вывод цен с диапазонами
	"USE_PRODUCT_QUANTITY"       => "N",    // Разрешить указание количества товара
),
	false
); ?>

<? /*if (IsModuleInstalled("advertising")):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner",
	"bootstrap",
	array(
		"COMPONENT_TEMPLATE" => "bootstrap",
		"TYPE" => "MAIN",
		"NOINDEX" => "Y",
		"QUANTITY" => "3",
		"BS_EFFECT" => "fade",
		"BS_CYCLING" => "N",
		"BS_WRAP" => "Y",
		"BS_PAUSE" => "Y",
		"BS_KEYBOARD" => "Y",
		"BS_ARROW_NAV" => "Y",
		"BS_BULLET_NAV" => "Y",
		"BS_HIDE_FOR_TABLETS" => "N",
		"BS_HIDE_FOR_PHONES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
	),
	false
);?>
<?endif*/ ?>

<? /*
global $trendFilter;
$trendFilter = array('PROPERTY_TREND' => '4');
?>
<h2>Тренды сезона</h2>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	".default",
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "2",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "trendFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT"
		),
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "N",
	),
	false
);*/ ?>


<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>