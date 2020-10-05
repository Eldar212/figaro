<?php
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );
require( $_SERVER["DOCUMENT_ROOT"] . "/inc/sms.ru/sms.ru.php" );

header( 'Content-Type: application/json' );

if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
	errExit( 405, "Неправильный http метод" );
}

$number = $_POST['number'];
if ( ! isset( $number ) ) {
	errExit( 400, "Номер телефона не передан" );
}

if ( ! preg_match( '/\d{11,}/', $number ) ) {
	errExit( 400, 'Номер телефона должен быть указан в международном формате без знака "+"' );
}

$code       = rand( 10000, 99999 );
$smsru      = new SMSRU( APIKEY );
$data       = new stdClass();
$data->to   = $number;
$data->text = 'Ваш одноразовый код: ' . $code;
$result     = $smsru->send_one( $data );

if ( $result->status_code == 202 ) {
	errExit( 400, "Неправильно указан номер телефона получателя" );
} else if ( $result->status_code == 230 ) {
	errExit( 400, "Превышен лимит количества сообщений на этот номер в день" );
} else if ( $result->status != "OK" ) {
	errExit( 400, "Неизвестная ошибка" );
}

$_SESSION["phonenumber_confirmation_time"]   = time();
$_SESSION["phonenumber_confirmation_code"]   = $code;
$_SESSION["phonenumber_confirmation_number"] = $number;

echo json_encode( [
	"result_description" => "success",
	"error_text"         => "ok",
	"session_id"         => session_id()
] );

function errExit( $num, $msg ) {
	http_response_code( $num );
	exit( json_encode( [
		"result_description" => "error",
		"error_text"         => $msg,
		"session_id"         => session_id(),
	] ) );
}