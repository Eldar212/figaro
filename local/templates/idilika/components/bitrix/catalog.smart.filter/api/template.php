<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

$min_price = "";
$max_price = "";


CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("highloadblock");


/*$rsProducts = CIBlockElement::GetList(
    Array('CATALOG_PRICE_1' => 'desc'),
    Array('IBLOCK_ID' => $iblock_id, 'IBLOCK_SECTION_ID' => $section_id, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "!CATALOG_PRICE_1" => null),
    false,
    Array('nTopCount' => 1),
    Array('IBLOCK_ID', 'ID', 'NAME', 'CATALOG_GROUP_1')
);
if($arProducts = $rsProducts->Fetch()){
    $max_price = $arProducts["CATALOG_PRICE_1"];
}
$rsProducts = CIBlockElement::GetList(
    Array('CATALOG_PRICE_1' => 'asc,nulls'),
    Array('IBLOCK_ID' => $iblock_id, 'IBLOCK_SECTION_ID' => $section_id, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "!CATALOG_PRICE_1" => null),
    false,
    Array('nTopCount' => 1),
    Array('IBLOCK_ID', 'ID', 'NAME', 'CATALOG_GROUP_1')
);
if($arProducts = $rsProducts->Fetch()){
    $min_price = $arProducts["CATALOG_PRICE_1"];
}

$field = new stdclass();
$field->code = 'PRICE';
$field->name = 'Цена';
$field->displayType = 'seekbar';
$field->min_price = $min_price;
$field->minPriceFilterCode = "arrFilter_P1_MIN";
$field->max_price = $max_price;
$field->maxPriceFilterCode = "arrFilter_P1_MAX";
array_push($GLOBALS["res"]["filter"], $field);
*/

foreach ($arResult['ITEMS'] as $item) {

    $values = array();

    $fieldProp = new stdclass();
    $fieldProp->id = $item["ID"];
    $fieldProp->code = $item["CODE"];
    $fieldProp->name = $item["NAME"];

    if($item['DISPLAY_TYPE'] == 'F' || $item['DISPLAY_TYPE'] == 'G') {
        $fieldProp->displayType = 'checkbox';
    } else if($item['DISPLAY_TYPE'] == 'K') {
        $fieldProp->displayType = 'radiobutton';
    } else if($item['DISPLAY_TYPE'] == 'P') {
        $fieldProp->displayType = 'selectOption';
    }

    foreach ($item['VALUES'] as $k => $val){
        $fieldVal = new stdclass();
        $fieldVal->code = $k;
        $fieldVal->filterCode = $val["CONTROL_ID"];
        $fieldVal->name = $val["VALUE"];

        array_push($values, $fieldVal);
    }
    $fieldProp->values = $values;

    array_push($GLOBALS["res"]["filter"], $fieldProp);

}

?>