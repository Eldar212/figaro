<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
?>


<?


$field = new stdclass();
$field->id = $item["ID"];
if ($item["OFFERS"])
    $field->firstOffer = $item["OFFERS"][0]["ID"];
$field->imageLink = "http://" . $_SERVER['SERVER_NAME'] . $item["PREVIEW_PICTURE"]["SRC"];
$field->title = $item["NAME"];
if ($item["OFFERS"]) {
    $field->price = round($item["OFFERS"][0]["ITEM_PRICES"][0]["BASE_PRICE"]);
} else {
    $field->price = round($item["SCALED_PRICE_1"]);
}

array_push($GLOBALS["res"]["catalogList"], $field);
?>
