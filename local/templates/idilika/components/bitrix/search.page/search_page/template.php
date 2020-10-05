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

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

global $gender;

?>
<div class="search-page">
    <form action="" method="get" class="flex_search">
		<? if ( $arParams["USE_SUGGEST"] === "Y" ):
			if ( strlen( $arResult["REQUEST"]["~QUERY"] ) && is_object( $arResult["NAV_RESULT"] ) ) {
				$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
				$obSearchSuggest        = new CSearchSuggest( $arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"] );
				$obSearchSuggest->SetResultCount( $arResult["NAV_RESULT"]->NavRecordCount );
			}
			?>
			<? $APPLICATION->IncludeComponent(
			"bitrix:search.suggest.input",
			"",
			array(
				"NAME"          => "q",
				"VALUE"         => $arResult["REQUEST"]["~QUERY"],
				"INPUT_SIZE"    => 40,
				"DROPDOWN_SIZE" => 10,
				"FILTER_MD5"    => $arResult["FILTER_MD5"],
			),
			$component, array( "HIDE_ICONS" => "Y" )
		); ?>
		<? else: ?>
            <input class="search_page_q" type="text" name="q" value="<?= $arResult["REQUEST"]["QUERY"] ?>" size="40" placeholder="Что вы ищите?"/>
		<? endif; ?>
		<? if ( $arParams["SHOW_WHERE"] ): ?>
            &nbsp;<select name="where">
                <option value=""><?= GetMessage( "SEARCH_ALL" ) ?></option>
				<? foreach ( $arResult["DROPDOWN"] as $key => $value ): ?>
                    <option value="<?= $key ?>"<? if ( $arResult["REQUEST"]["WHERE"] == $key )
						echo " selected" ?>><?= $value ?></option>
				<? endforeach ?>
            </select>
		<? endif; ?>
        <button type="submit" class="search-button-pc search-button-pc_full"></button>
        <input type="hidden" name="how" value="<? echo $arResult["REQUEST"]["HOW"] == "d" ? "d" : "r" ?>"/>
		<? if ( $arParams["SHOW_WHEN"] ): ?>
            <script>
                var switch_search_params = function () {
                    var sp = document.getElementById('search_params');
                    var flag;
                    var i;

                    if (sp.style.display == 'none') {
                        flag = false;
                        sp.style.display = 'block'
                    } else {
                        flag = true;
                        sp.style.display = 'none';
                    }

                    var from = document.getElementsByName('from');
                    for (i = 0; i < from.length; i++)
                        if (from[i].type.toLowerCase() == 'text')
                            from[i].disabled = flag;

                    var to = document.getElementsByName('to');
                    for (i = 0; i < to.length; i++)
                        if (to[i].type.toLowerCase() == 'text')
                            to[i].disabled = flag;

                    return false;
                }
            </script>
            <br/><a class="search-page-params" href="#" onclick="return switch_search_params()"><? echo GetMessage( 'CT_BSP_ADDITIONAL_PARAMS' ) ?></a>
            <div id="search_params" class="search-page-params" style="display:<? echo $arResult["REQUEST"]["FROM"] || $arResult["REQUEST"]["TO"] ? 'block' : 'none' ?>">
				<? $APPLICATION->IncludeComponent(
					'bitrix:main.calendar',
					'',
					array(
						'SHOW_INPUT'            => 'Y',
						'INPUT_NAME'            => 'from',
						'INPUT_VALUE'           => $arResult["REQUEST"]["~FROM"],
						'INPUT_NAME_FINISH'     => 'to',
						'INPUT_VALUE_FINISH'    => $arResult["REQUEST"]["~TO"],
						'INPUT_ADDITIONAL_ATTR' => 'size="10"',
					),
					null,
					array( 'HIDE_ICONS' => 'Y' )
				); ?>
            </div>
		<? endif ?>
    </form>
    <br/>

	<? if ( isset( $arResult["REQUEST"]["ORIGINAL_QUERY"] ) ):
		?>
        <div class="search-language-guess">
		<?
		echo GetMessage( "CT_BSP_KEYBOARD_WARNING", array( "#query#" => '<a href="' . $arResult["ORIGINAL_QUERY_URL"] . '">' . $arResult["REQUEST"]["ORIGINAL_QUERY"] . '</a>' ) ) ?>
        </div><?
	endif; ?>

	<? if ( $arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false ): ?>
	<? elseif ( $arResult["ERROR_CODE"] != 0 ): ?>

        <div class="query-for-search">Введите запрос для поиска</div>

		<? /*<table border="0" cellpadding="5">
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
			<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
			<td><?=GetMessage("SEARCH_AND_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
			<td><?=GetMessage("SEARCH_OR_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
			<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top">( )</td>
			<td valign="top">&nbsp;</td>
			<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
		</tr>
	</table>*/ ?>
	<? elseif ( count( $arResult["SEARCH"] ) > 0 ): ?>
		<?
		function plural_form( $number, $before, $after ) {
			$cases = array( 2, 0, 1, 1, 1, 2 );
			echo $before[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ] . ' ' . $number . ' ' . $after[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
		}

		?>
        <p class="res_search">
			<?=
			plural_form(
				$arResult["NAV_RESULT"]->result->num_rows,
				array( 'Найден', 'Найдено', 'Найдено' ),
				array( 'товар', 'товара', 'товаров' )
			);
			?>
        </p>
		<? if ( $arParams["DISPLAY_TOP_PAGER"] != "N" )
			echo $arResult["NAV_STRING"] ?>
        <div class="cat_list cat_list_search flex news-list">
			<? foreach ( $arResult["SEARCH"] as $arItem ): ?>
				<?

				$img_paper  = "";
				$price_id   = "";
				$price_item = "";

				$resBlocks = CIBlockElement::GetList( array(), array( "ID" => $arItem["ITEM_ID"] ), false, array(), array( '*', 'CATALOG_GROUP_1' ) );
				while ( $ob = $resBlocks->GetNextElement() ) {
					$arFields = $ob->GetFields();
					$arProps  = $ob->GetProperties();

					if ( $arFields["PREVIEW_PICTURE"] ) {
						$img_paper = CFile::ResizeImageGet( $arFields['PREVIEW_PICTURE'], array( "width" => 330, 'height' => 410 ), BX_RESIZE_IMAGE_EXACT, true );

						if ( ! isset( $img_paper['src'] ) ) {
							$img_paper = $item['PREVIEW_PICTURE_SRC'];
						} else {
							$img_paper = $img_paper['src'];
						}

					} elseif ( $arFields["DETAIL_PICTURE"] ) {
						$img_paper = CFile::ResizeImageGet( $arFields['DETAIL_PICTURE'], array( "width" => 330, 'height' => 410 ), BX_RESIZE_IMAGE_EXACT, true );

						if ( ! isset( $img_paper['src'] ) ) {
							$img_paper = $item['DETAIL_PICTURE_SRC'];
						} else {
							$img_paper = $img_paper['src'];
						}
					}

					$price_id   = $arFields["CATALOG_PRICE_ID_1"];
					$price_item = $arFields["CATALOG_PRICE_1"];
					$currency   = $arFields["CATALOG_CURRENCY_1"];

				}


				if ( ! empty( $arProps['BRAND_REF']['VALUE'] ) ) {

					$HL_Infoblock_ID   = 2; // ID Highload-инфоблока со списком характеристик
					$hlblock           = HLBT::getById( $HL_Infoblock_ID )->fetch();
					$entity            = HLBT::compileEntity( $hlblock );
					$entity_data_class = $entity->getDataClass();

					$rsData = $entity_data_class::getList( array(
						'select' => array( '*' ),
						'filter' => array( 'UF_XML_ID' => $arProps['BRAND_REF']['VALUE'] )
					) );

					while ( $arData = $rsData->Fetch() ) {
						$arProps['BRAND_REF']['UF_NAME'] = $arData['UF_NAME'];
					}

				}
				?>

                <div class="cat_list_el news-item">
                    <div class="product-item-container" data-entity="item">
                        <div class="product-item">
                            <a class="img_item_search_wrapper" href="<?= $arItem['URL'] ?>" data-entity="image-wrapper">
                                <div class="img_item_search" style="background-image: url('<?= $img_paper ?>'); "></div>
                            </a>

							<? if ( ! empty( $arProps['BRAND_REF']['UF_NAME'] ) ): ?>
                                <div class="product-item-brand">
                                    <a href="/catalog/<?= $gender ?>/filter/brand_ref-is-<?= $item['PROPERTIES']['BRAND_REF']['VALUE'] ?>/apply/">
										<?= $arProps['BRAND_REF']['UF_NAME'] ?>
                                    </a>
                                </div>

							<? endif; ?>

                            <div class="product-item-title">
                                <a href="<?= $arItem['URL'] ?>" title="<?= $arItem['TITLE'] ?>"><?= $arItem['TITLE'] ?></a>
                            </div>
                            <div class="product-item-info-container product-item-price-container flex" data-entity="price-block">
                                <div>
                                    <span class="product-item-price-current"><?= CurrencyFormat( $price_item, $currency ) ?></span>
                                </div>
                                <div class="add_like">
                                    <a href="javascript:void(0)" class="wishbtn
                                    <? if ( in_array( $arResult["ID"], $arBasketItems ) ) {
										echo 'in_wishlist ';
									} ?>"
                                       onclick="add2wish(
                                               '<?= $arItem["ITEM_ID"] ?>',
                                               '<?= $price_id ?>',
                                               '<?= $price_item ?>',
                                               '<?= $arItem['TITLE'] ?>',
                                               '<?= $arItem['URL_WO_PARAMS'] ?>',
                                               this)">
                                        <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z"
                                                  stroke="#A4A4A4" stroke-width="1.5" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<? /*<a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a>
		<p><?echo $arItem["BODY_FORMATED"]?></p>
		<?if (
			$arParams["SHOW_RATING"] == "Y"
			&& strlen($arItem["RATING_TYPE_ID"]) > 0
			&& $arItem["RATING_ENTITY_ID"] > 0
		):?>
			<div class="search-item-rate"><?
				$APPLICATION->IncludeComponent(
					"bitrix:rating.vote", $arParams["RATING_TYPE"],
					Array(
						"ENTITY_TYPE_ID" => $arItem["RATING_TYPE_ID"],
						"ENTITY_ID" => $arItem["RATING_ENTITY_ID"],
						"OWNER_ID" => $arItem["USER_ID"],
						"USER_VOTE" => $arItem["RATING_USER_VOTE_VALUE"],
						"USER_HAS_VOTED" => $arItem["RATING_USER_VOTE_VALUE"] == 0? 'N': 'Y',
						"TOTAL_VOTES" => $arItem["RATING_TOTAL_VOTES"],
						"TOTAL_POSITIVE_VOTES" => $arItem["RATING_TOTAL_POSITIVE_VOTES"],
						"TOTAL_NEGATIVE_VOTES" => $arItem["RATING_TOTAL_NEGATIVE_VOTES"],
						"TOTAL_VALUE" => $arItem["RATING_TOTAL_VALUE"],
						"PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER_PROFILE"],
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);?>
			</div>
		<?endif;?>
		<small><?=GetMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small><br /><?
		if($arItem["CHAIN_PATH"]):?>
			<small><?=GetMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small><?
		endif;
		?><hr />*/ ?>
			<? endforeach; ?>
        </div>
        <div style="clear: both"></div>
		<? if ( $arParams["DISPLAY_BOTTOM_PAGER"] != "N" ) {
			?>
            <div style="clear: both"></div>
			<? echo $arResult["NAV_STRING"];
		} ?>

		<? /*if($arResult["REQUEST"]["HOW"]=="d"):?>
		<a href="<?=$arResult["URL"]?>&amp;how=r<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=GetMessage("SEARCH_SORTED_BY_DATE")?></b>
	<?else:?>
		<b><?=GetMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a>
	<?endif;*/ ?>
	<? else: ?>
		<? ShowNote( GetMessage( "SEARCH_NOTHING_TO_FOUND" ) ); ?>
	<? endif; ?>
</div>