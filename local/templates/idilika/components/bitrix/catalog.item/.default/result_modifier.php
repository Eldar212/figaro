<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $templateData
 */


use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
CModule::IncludeModule( 'highloadblock' );

if (!empty($arResult['ITEM']['PROPERTIES']['BRAND_REF']['VALUE'])) {

	$HL_Infoblock_ID   = 2; // ID Highload-инфоблока со списком характеристик
	$hlblock           = HLBT::getById( $HL_Infoblock_ID )->fetch();
	$entity            = HLBT::compileEntity( $hlblock );
	$entity_data_class = $entity->getDataClass();

	$rsData = $entity_data_class::getList( array(
		'select' => array( '*' ),
		'filter' => array( 'UF_XML_ID' => $arResult['ITEM']['PROPERTIES']['BRAND_REF']['VALUE'] )
	) );

	while ( $arData = $rsData->Fetch() ) {
		$arResult['ITEM']['PROPERTIES']['BRAND_REF']['UF_NAME'] = $arData['UF_NAME'];
	}

}