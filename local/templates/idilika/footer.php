<? if ( ! defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true ) {
	die();
} ?>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/include/telephone.php';

?>
</div><!--//workarea-->

<div style="clear:both;"></div>
<footer class="footer">
<div class="container">
            <div class="footer-body ">
                <div class="footer-body__column">
                    <a href="/" class="logo logo-footer"><img src="<?= SITE_TEMPLATE_PATH . '/images/logo.svg' ?>" alt=""></a>
                    <div class="footer-body__group">
                        <a href="/pageNotFound.php"><img src="<?= SITE_TEMPLATE_PATH . '/images/pay/mc.svg' ?>" alt=""></a>
                        <a href="/pageNotFound.php"><img src="<?= SITE_TEMPLATE_PATH . '/images/pay/visa.svg' ?>" alt=""></a>
                        <a href="/pageNotFound.php"><img src="<?= SITE_TEMPLATE_PATH . '/images/pay/mir.svg' ?>" alt=""></a>
                    </div>
                </div>
                <div class="footer-body__column">
                    <nav class="footer-body-menu">
                        <ul class="footer-body-menu-list">
                            <li>
                                <a href="/pageNotFound.php" class="footer-body-menu__link">О нас</a>
                            </li>
                            <li>
                                <a href="/pageNotFound.php" class="footer-body-menu__link">Система лояльности</a>
                            </li>
                            <li>
                                <a href="/pageNotFound.php" class="footer-botton-menu__link">Бренды</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="footer-body__column">
                    <nav class="footer-body-menu">
                        <ul class="footer-body-menu-list">
                            <li>
                                <a href="/pageNotFound.php" class="footer-botton-menu__link footer-botton-instashop"><img
                                        src="<?= SITE_TEMPLATE_PATH . '/images/social/instashop.svg' ?>" alt="" class="footer-instashop">Наш инстаграм</a>
                            </li>
                            <li>
                                <a href="/pageNotFound.php" class="footer-botton-menu__link">Вакансии</a>
                            </li>
                            <li>
                                <a href="/pageNotFound.php" class="footer-botton-menu__link">Контакты</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="footer-body__column footer-body__column_padding">
                    <a href="<?= $phone ?>" class="footer-body__column-link">
                        <span><?= $phone ?></span>
                    </a>
                    <span class="footer-body__column-span footer-body__column-span_margin">Позвоните нам</span>
                    <a href="mailto:info@shopfigaro.com" class="footer-body__column-link">
                        <span>info@shopfigaro.com</span>
                    </a>
                    <span class="footer-body__column-span">Напишите нам</span>
                </div>
            </div>
        </div>
        <div class="watermark_block">
        <div class="container">
            <div class="flex">
                <div class="watermark">© 2020 «Figaro» — мультибрендовый магазин одежды с доставкой по всей России.</div>
                <div class="flex">
                    <a href="/pageNotFound.php" class="watermark__a">Политика конфиденциальности</a>
                </div>
            </div>
        </div>
    </div>
</footer>

</div> <!-- //bx-wrapper -->

<div class="popup" data-popup-name="login">
    <div class="popup-wrapper">
        <div class="popup-content">
            <button class="popup-close"></button>

            <div class="auth">
				<? // Authorization (social/phone/email)
				$APPLICATION->IncludeComponent(
					"bitrix:system.auth.authorize",
					"flat",
					array(),
					false,
					array()
				); ?>
            </div>

        </div>

    </div>
</div>

<script>
    BX.ready(function () {
        var upButton = document.querySelector('[data-role="eshopUpButton"]');
        BX.bind(upButton, "click", function () {
            var windowScroll = BX.GetWindowScrollPos();
            (new BX.easing({
                duration: 500,
                start: {scroll: windowScroll.scrollTop},
                finish: {scroll: 0},
                transition: BX.easing.makeEaseOut(BX.easing.transitions.quart),
                step: function (state) {
                    window.scrollTo(0, state.scroll);
                },
                complete: function () {
                }
            })).animate();
        })
    });
</script>

</body>
</html>