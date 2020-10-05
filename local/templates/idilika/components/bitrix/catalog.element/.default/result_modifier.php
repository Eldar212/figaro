<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
CModule::IncludeModule( 'highloadblock' );

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$HL_Infoblock_ID = 3; // ID Highload-инфоблока со списком характеристик
$hlblock = HLBT::getById($HL_Infoblock_ID)->fetch();
$entity = HLBT::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
	'select' => array('*')
));

$attributes = array();
while($arData = $rsData->Fetch()){
	$attributes[$arData['ID']] = $arData['UF_ATTRUBUTE_NAME'];
}

if (isset($arResult['PROPERTIES']['PRODUCT_ATTRIBUTES'])) {

	foreach ($arResult['PROPERTIES']['PRODUCT_ATTRIBUTES']['VALUE'] as &$value) {
		$value['NAME'] = $attributes[$value['ID']];
	}

}

/* Получаем описание бренда */

if (!empty($arResult['PROPERTIES']['BRAND_REF']['VALUE'])) {

	$HL_Infoblock_ID   = 2; // ID Highload-инфоблока со списком характеристик
	$hlblock           = HLBT::getById( $HL_Infoblock_ID )->fetch();
	$entity            = HLBT::compileEntity( $hlblock );
	$entity_data_class = $entity->getDataClass();

	$rsData = $entity_data_class::getList( array(
		'select' => array( '*' ),
		'filter' => array( 'UF_XML_ID' => $arResult['PROPERTIES']['BRAND_REF']['VALUE'] )
	) );

	while($arData = $rsData->Fetch()){
		$arResult['PROPERTIES']['BRAND_REF']['VALUE_DESCRIPTION'] = $arData['UF_DESCRIPTION'];
	}

}