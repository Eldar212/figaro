<?
//define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
$APPLICATION->AddChainItem("Регистрация");

$userName = CUser::GetFullName();
if (!$userName)
	$userName = CUser::GetLogin();

$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"",
	array(
		"SHOW_FIELDS" => array(
			"NAME",
			"PERSONAL_PHONE",
			"EMAIL",
			"PASSWORD",
			"CONFIRM_PASSWORD",
			"PERSONAL_BIRTHDAY",
			"PERSONAL_GENDER"
		),
		"AUTH" => "Y",
		"SUCCESS_PAGE" => "/personal/private/"
	),
	false,
	array()
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>