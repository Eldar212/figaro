<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?><div class="bx-hdr-profile">
	<div class="bx-basket-block">
        <?
		if (!$compositeStub)
		{
			if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0)) { ?>
                <a class="tt_capitalize" href="<?= $arParams['PATH_TO_BASKET'] ?>">
                    <img src="<?= SITE_TEMPLATE_PATH . '/images/shop_cart.svg' ?>" alt="">
                    <span><?= $arResult['PRODUCT(S)'].' '.$arResult['NUM_PRODUCTS']; ?></span>
                </a>

				<?
			} else {
			    ?>
                <a class="tt_capitalize" href="<?= $arParams['PATH_TO_BASKET'] ?>">
                    <img src="<?= SITE_TEMPLATE_PATH . '/images/shop_cart.svg' ?>" alt="">
                    <span class="header-bottom-content__none">нет товаров</span>
                </a>
                <?
            }
		}
		?>
	</div>
</div>