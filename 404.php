<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

$without_h1_breadcrumb = true;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>

<div class="container empty_page">
    <img src="/local/templates/idilika/images/empty-page.png" alt="">
    <p class="empty_title">Страница не найдена</p>
    <span class="empty_subtext">К сожалению, страница на которой вы находитесь не существует</span>
    <a href="/">
        <button class="btn-black">На главную</button>
    </a>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>