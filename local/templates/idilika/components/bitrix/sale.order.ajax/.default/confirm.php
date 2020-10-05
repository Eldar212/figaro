<? if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ( $arParams["SET_TITLE"] == "Y" ) {
	$APPLICATION->SetTitle( Loc::getMessage( "SOA_ORDER_COMPLETE" ) );
}
?>

<? if ( ! empty( $arResult["ORDER"] ) ): ?>

    <div class="container empty_page">
        <img src="/local/templates/idilika/images/thanks-order.png" alt="">
        <p class="empty_title">Спасибо за заказ!</p>
        <span class="empty_subtext">
            <?= Loc::getMessage( "SOA_ORDER_SUC", array(
	            "#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format( 'd.m.Y H:i' ),
	            "#ORDER_ID#"   => $arResult["ORDER"]["ACCOUNT_NUMBER"]
            ) ) ?>
			<? if ( $arResult["ORDER"]['PAY_SYSTEM_ID'] != 1 ): ?>
				<?= Loc::getMessage( "SOA_PAYMENT_SUC", array(
					"#PAYMENT_ID#" => $arResult['PAYMENT'][ $arResult['ORDER']["PAYMENT_ID"] ]['ACCOUNT_NUMBER']
				) ) ?>
			<? endif ?>
            <?if ( $arResult["ORDER"]['PAY_SYSTEM_ID'] == 1 ):?>
                <br>В ближайшее время с вами свяжется наш оператор, чтобы подтведить заказ
             <?else:?>
                <br>После оплаты с вами свяжется наш оператор, чтобы подтвердить заказ
            <?endif;?>

        </span>

        <?if ( $arResult["ORDER"]['PAY_SYSTEM_ID'] == 1 ):?>
        <a href="/">
            <button class="btn-black">На главную</button>
        </a>
        <?endif;?>

	    <? if ( $arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y' ) {
		    if ( ! empty( $arResult["PAYMENT"] ) ) {
			    foreach ( $arResult["PAYMENT"] as $payment ) {
				    if ( $payment["PAID"] != 'Y' ) {
					    if ( ! empty( $arResult['PAY_SYSTEM_LIST'] )
					         && array_key_exists( $payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'] )
					    ) {
						    $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][ $payment["ID"] ];

						    if ( empty( $arPaySystem["ERROR"] ) ) {

							    if ( strlen( $arPaySystem["ACTION_FILE"] ) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y" ) {
								    $orderAccountNumber   = urlencode( urlencode( $arResult["ORDER"]["ACCOUNT_NUMBER"] ) );
								    $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];

								    echo '<script>window.open(\'<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>\');</script>';

								    echo Loc::getMessage( "SOA_PAY_LINK", array( "#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&PAYMENT_ID=" . $paymentAccountNumber ) );

								    if ( CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF'] ) {
									    echo Loc::getMessage( "SOA_PAY_PDF", array( "#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&pdf=1&DOWNLOAD=Y" ) );
								    }

							    } else {
								    echo $arPaySystem["BUFFERED_OUTPUT"];
							    }

						    } else {
							    echo '<span style="color:red;">' . Loc::getMessage( "SOA_ORDER_PS_ERROR" ) . '</span>';
						    }
					    } else {
						    echo '<span style="color:red;">' . Loc::getMessage( "SOA_ORDER_PS_ERROR" ) . '</span>';
					    }
				    }
			    }
		    }
	    } else {
		    echo '<strong>'.$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'].'</strong>';
	    }
	    ?>
    </div>

	<? if ( false ): ?>
        <table class="sale_order_full_table">
            <tr>
                <td>
					<?= Loc::getMessage( "SOA_ORDER_SUC", array(
						"#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format( 'd.m.Y H:i' ),
						"#ORDER_ID#"   => $arResult["ORDER"]["ACCOUNT_NUMBER"]
					) ) ?>
					<? if ( ! empty( $arResult['ORDER']["PAYMENT_ID"] ) ): ?>
						<?= Loc::getMessage( "SOA_PAYMENT_SUC", array(
							"#PAYMENT_ID#" => $arResult['PAYMENT'][ $arResult['ORDER']["PAYMENT_ID"] ]['ACCOUNT_NUMBER']
						) ) ?>
					<? endif ?>
					<? if ( $arParams['NO_PERSONAL'] !== 'Y' ): ?>
                        <br/><br/>
						<?= Loc::getMessage( 'SOA_ORDER_SUC1', [ '#LINK#' => $arParams['PATH_TO_PERSONAL'] ] ) ?>
					<? endif; ?>
                </td>
            </tr>
        </table>
	<? endif; ?>

<? else: ?>

    <b><?= Loc::getMessage( "SOA_ERROR_ORDER" ) ?></b>
    <br/><br/>

    <table class="sale_order_full_table">
        <tr>
            <td>
				<?= Loc::getMessage( "SOA_ERROR_ORDER_LOST", [ "#ORDER_ID#" => htmlspecialcharsbx( $arResult["ACCOUNT_NUMBER"] ) ] ) ?>
				<?= Loc::getMessage( "SOA_ERROR_ORDER_LOST1" ) ?>
            </td>
        </tr>
    </table>

<? endif ?>