<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$basket_id = $_POST['basket_id'];

if(\Bitrix\Main\Loader::includeModule("sale")) {
    if (CSaleBasket::Delete($basket_id))
        echo "Запись успешно удалена";

};


?>