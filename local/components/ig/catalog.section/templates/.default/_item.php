<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var array $item */

/** @var CBitrixComponent $component */

?>

<div class="icard<?= $isLastPage ? ' scroll-load-last-one' : '' ?> js-icard"
     data-offer-index="0"
     data-offers-url="/local/ajax/offers.php?productID=<?= $item['ID'] ?>">
    <div class="icard__inner">
        <div class="icard__content">
            <div class="icard__header">
                <div class="cols-wrapper">
                    <div class="cols cols--auto">
                        <div class="col">
                            <?php include '_breadcrumb.php' ?>
                        </div>
                        <?php include '_tags.php' ?>
                    </div>
                </div>
                <?php include '_title.php' ?>
            </div>
            <div class="icard__main">
                <div class="icard__main-grid">
                    <div class="icard__image">
                        <div class="icard__image-inner js-icard-offer-images">
                            <?php include '_gallery.php' ?>
                        </div>
                    </div>
                    <?php include '_main-props.php' ?>
                    <div class="icard__main-col">
                        <div class="icard__pa">
                            <div class="icard__params">
                                <div class="icard__offers-params-wrapper">
                                    <div class="icard__offers-params js-icard-offer-params">
                                        <?php include '_params.php' ?>
                                    </div>
                                </div>
                                <div class="icard__quickview mobile-show">
                                    <a href="#" class="link--dotted" data-fancybox
                                       data-type="ajax"
                                       data-src="/local/ajax/catalog_detail_quick.php?sortID=<?= $item['ID'] ?>">
                                        <svg class="icon icon--zoom">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
                                        </svg>
                                        Быстрый просмотр
                                    </a>
                                </div>
                                <?php if (count($item['OFFERS']) > 1) : ?>
                                    <div class="icard__counts">
                                        <span class="icard__count">Вариантов: <strong><?= count($item['OFFERS']) ?></strong></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (count($item['OFFERS']) > 1) : ?>
                                <div class="icard__nav ">
                                    <div class="icard__nav-link js-icard-offer-change"
                                         data-action="prev">
                                        <svg class="icon">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-up"></use>
                                        </svg>
                                    </div>
                                    <div class="icard__nav-page js-icard-offer-number">
                                        1
                                    </div>
                                    <div class="icard__nav-link js-icard-offer-change"
                                         data-action="next">
                                        <svg class="icon">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
                                        </svg>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="icard__nav  icard__nav--empty"></div>
                            <?php endif; ?>
                            <div class="icard__actions">
                                <div class="icard__offers-actions-wrapper">
                                    <div class="icard__offers-actions js-icard-offer-actions">
                                        <?php include '_price.php' ?>
                                    </div>
                                </div>
                                <div class="icard__quickview mobile-hide">
                                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>"
                                       class="link--dotted"
                                       data-fancybox
                                       data-type="ajax"
                                       data-src="<?= $item['DETAIL_PAGE_URL'] ?>">
                                        <svg class="icon icon--zoom">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
                                        </svg>
                                        быстрый просмотр
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
