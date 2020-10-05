<?
if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

if ( empty( $arResult ) ) {
	return;
}

if ( isset( $_GET['GENDER'] ) ) {
	$gender = $_GET['GENDER'];
} elseif ( isset( $_COOKIE['GENDER'] ) ) {
	$gender = $_COOKIE['GENDER'];
} else {
	$gender = 'woman';
}

// Получаем ID раздела по SECTION_CODE
$section_id = CIBlockFindTools::GetSectionID( 0, $gender, array( "IBLOCK_ID" => 2 ) );
$section_id = CIBlockSection::GetByID( $section_id );
$section_id = $section_id->GetNext();

$arSectionsInfo = array();
if ( IsModuleInstalled( "iblock" ) ) {

	$arFilter = Array(
		"TYPE"          => "catalog",
		'ACTIVE'        => 'Y',
		'GLOBAL_ACTIVE' => 'Y',
		'SECTION_ID'    => $section_id
	);

	if ( CModule::IncludeModule( "iblock" ) ) {

		$arSectionsInfo = CIBlockSection::GetList(
			Array( 'SORT' => 'ASC' ),
			$arFilter,
			false,
			Array()
		);

		while ( $ob = $arSectionsInfo->GetNext() ) {

			$arResult['ALL_ITEMS'][] = array(
				'ID'   => $ob['ID'],
				'NAME' => $ob['NAME'],
				'LINK' => $ob['SECTION_PAGE_URL']
			);

		}

	}

}