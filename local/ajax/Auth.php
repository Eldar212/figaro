<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header('Content-Type: application/json');

//===================================== CHECK CONDITIONS ======================

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errExit(405, "неправильный http метод");
}

if ((time() - $_SESSION["phonenumber_confirmation_time"]) > 60 * 5) {
	unset($_SESSION["phonenumber_confirmation_time"]);
	unset($_SESSION["phonenumber_confirmation_code"]);
	unset($_SESSION["phonenumber_confirmation_number"]);
}

if (!isset($_SESSION["phonenumber_confirmation_time"])) {
	errExit(400, "Истек срок дейтсвия кода, либо код введен неправильно");
}

if (!isset($_POST["code"])) {
	errExit(400, "Введите код");
}

if (isset($_SESSION["phonenumber_confirmation_code"]) && $_SESSION["phonenumber_confirmation_code"] != $_POST["code"]) {
	errExit(400, "Код введен неправильно");
}

$number = $_SESSION["phonenumber_confirmation_number"];

$uid = CUser::getList(
	($by="personal_country"),
	($order="desc"),
	["PERSONAL_PHONE" => $number, ],
	["FIELDS" => ["ID"] ]
)->fetch()["ID"];


//====================================- LOGIN/REGISTER -=======================

if (!$uid) {

	do {
		$login = "user_".substr(md5(microtime()."to aru majutsu no login"), 0, 16);
		$email = substr(md5(microtime()."to aru majutsu no email"), 0, 16)."@example.com";
		$password = substr(md5("{$time}to aru majutsu no salt"), 0, 30);
	} while (
		CUser::getList(
			($by="personal_country"),
			($order="desc"),
			[
				'LOGIC' => 'OR',
				"LOGIN" => $login,
				"EMAIL" => $email,
			],
			["ID"]
		)-> fetch() !== false
	);

	$user_id =$USER->Add([
		"LOGIN" => $login,
		'EMAIL' => $email,
		'PASSWORD' => $password,
		'CONFIRM_PASSWORD' => $password,
		"PERSONAL_PHONE" => $number
	]);

	$USER->Authorize($user_id);

} else {
	$auth = $USER->Authorize($uid, true, true);
}


//===================================== USER INFO =============================
$filter = [
	"id",
	"timestamp_x",
	"active",
	"name",
	"last_name",
	"email",
	"last_login",
	"date_register",
	"lid",
	"personal_profession",
	"personal_www",
	"personal_icq",
	"personal_gender",
	"personal_photo",
	"personal_phone",
	"personal_fax",
	"personal_mobile",
	"personal_pager",
	"personal_street",
	"personal_mailbox",
	"personal_city",
	"personal_state",
	"personal_zip",
	"personal_country",
	"personal_notes",
	"work_company",
	"work_department",
	"work_position",
	"work_www",
	"work_phone",
	"work_fax",
	"work_pager",
	"work_street",
	"work_mailbox",
	"work_city",
	"work_state",
	"work_zip",
	"work_country",
	"work_profile",
	"work_logo",
	"work_notes",
	"admin_notes",
	"xml_id",
	"personal_birthday",
	"external_auth_id",
	"second_name",
	"login_attempts",
	"last_activity_date",
	"auto_time_zone",
	"time_zone",
	"time_zone_offset",
	"title",
	"bx_user_id",
	"language_id",
	"is_online"
];

$userdata = array_change_key_case(
	array_intersect_key(
		CUser::GetByID($USER->getID())->Fetch(),
		array_flip(
			array_map((strtoupper),
				$filter
			))));

$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
if (is_numeric($userdata["personal_photo"])) {
	$userdata["personal_photo"] = $protocol . $_SERVER["SERVER_NAME"] . CFile::GetPath($userdata["personal_photo"]);
}
if (is_numeric($userdata["work_logo"])) {
	$userdata["work_logo"] = $protocol . $_SERVER["SERVER_NAME"] . CFile::GetPath($userdata["work_logo"]);
}

$userdata["session_id"] = session_id();
$userdata["result_description"] = "success";

echo json_encode($userdata);

function errExit($num, $msg){
	http_response_code($num);
	exit(json_encode([
		"result_description" => "error",
		"error_text" => $msg,
		"session_id" => session_id(),
	]));
}