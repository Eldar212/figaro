<?php

AddEventHandler( "main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler" );
//AddEventHandler( "main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler" );
function OnBeforeUserUpdateHandler( &$arFields ) {
	$arFields["LOGIN"] = $arFields["EMAIL"];

	return $arFields;
}

// подписываемся на событие CurrencyFormat модуля валют.
// вызывается в функции \CAllCurrencyLang::CurrencyFormat
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandlerCompatible( 'currency', 'CurrencyFormat',
	array( 'CCurrencyLangHandler', 'CurrencyFormat' ) );

class CCurrencyLangHandler {
	public static function CurrencyFormat( $price, $currency ) {
		if ( ! ( defined( 'ADMIN_SECTION' ) && true === ADMIN_SECTION ) ) {
			return sprintf( '%s &#8381;', number_format( $price, 0, ' ', ' ' ) );
		}
	}
}


// Поле характеристики
AddEventHandler( "iblock", "OnIBlockPropertyBuildList", array( "CPropertyTypeProductAttributes", "GetUserTypeDescription" ) );

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

CModule::IncludeModule( 'highloadblock' );

const MY_HL_BLOCK_ID = 3; // ID инфоблока со списком характеристик

class CPropertyTypeProductAttributes {

	public function GetUserTypeDescription() {
		return array(
			"PROPERTY_TYPE"        => "S",
			"USER_TYPE"            => "PRODUCT_ATTRIBUTES",
			"DESCRIPTION"          => "Характеристики товара",
			"GetPropertyFieldHtml" => array( "CPropertyTypeProductAttributes", "GetPropertyFieldHtml" ),
			"ConvertToDB"          => array( __CLASS__, "ConvertToDB" ),
			"ConvertFromDB"        => array( __CLASS__, "ConvertFromDB" )
		);
	}

	public function GetPropertyFieldHtml( $arProperty, $value, $strHTMLControlName ) {

		$value = $value['VALUE'];

		// Получаем список характеристик
		$hlblock    = HLBT::getById( MY_HL_BLOCK_ID )->fetch();
		$entity     = HLBT::compileEntity( $hlblock );
		$attributes = $entity->getDataClass();

		$rsData     = $attributes::getList( array(
			'select' => array( '*' )
		) );

		$attributes = array();
		$i          = 0;
		while ( $el = $rsData->fetch() ) {
			$attributes[ $i ]['ID']   = $el['ID'];
			$attributes[ $i ]['NAME'] = $el['UF_ATTRUBUTE_NAME'];

			$i ++;
		}

		$htmlField = '<div class="attributes">';


		$htmlField .= '<select class="attribute-select" name="'.$strHTMLControlName['VALUE'].'[ID]">';

			$htmlField .= '<option value="">Выбрать</option>';

			foreach ( $attributes as $attribute ) {
				if ($value['ID'] == $attribute['ID']) {
					$htmlField .= '<option value="' . $attribute['ID'] . '" selected>' . $attribute['NAME'] . '</option>';
				} else {
					$htmlField .= '<option value="' . $attribute['ID'] . '">' . $attribute['NAME'] . '</option>';
				}
			}

		$htmlField .= '</select>: ';

		if ($value['VALUE'] != '') {
			$htmlField .= '<input type="text" name="' . $strHTMLControlName['VALUE'] . '[VALUE]" value="'.$value['VALUE'].'">';
		} else {
			$htmlField .= '<input type="text" name="' . $strHTMLControlName['VALUE'] . '[VALUE]">';
		}

		$htmlField .= '</div>';

		$htmlField .= '
		
			<style>
			</style>
			
			<script>
			</script>
		
		';

		return $htmlField;
	}

	public static function ConvertToDB( $arProperty, $arValue ) {


		if (!empty($arValue['VALUE']['ID']) || !empty($arValue['VALUE']['VALUE'])) {

			$arValue['VALUE'] = json_encode( $arValue['VALUE'] );

			return $arValue;

		}

	}

	public static function ConvertFromDB( $arProperty, $arValue ) {
		$arValue['VALUE'] = json_decode( $arValue['VALUE'], true );

		return $arValue;
	}

}

// Получаем список гендеров
if(CModule::IncludeModule("iblock") ) {
	$genders_list = CIBlockSection::GetList(
		Array(),
		Array(
			"IBLOCK_ID"   => 2,
			"ACTIVE"      => "Y",
			"DEPTH_LEVEL" => 1
		), false,
		Array(
			"ID",
			"IBLOCK_ID",
			"NAME",
			"CODE"
		),
		Array( "nPageSize" => 50 )
	);

	while ( $row = $genders_list->GetNext() ) {

		$genders[$row['CODE']] = array(
			'ID'   => $row['ID'],
			'NAME' => $row['NAME']
		);

	}
}