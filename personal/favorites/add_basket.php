<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$basket_id = $_POST['basket_id'];

if(\Bitrix\Main\Loader::includeModule("sale")) {
    $arFields = array(
        "QUANTITY" => 1,
        "DELAY" => "N"
    );
    if(CSaleBasket::Update($basket_id, $arFields)) {
        echo "true";
    } else {
        echo "false";
    }

};


?>