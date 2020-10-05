<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доставка");
?><div class="row">
    <div class="container">
        <div id="content_api">
            <? $APPLICATION->IncludeFile(
                SITE_DIR."delivery_info.php",
                array(),
                array(
                    "MODE" => "html"
                )
            ); ?>
        </div>
    </div>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>