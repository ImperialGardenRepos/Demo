<div class="fcard">
    <div class="fcard__grid">
        <div class="fcard__header"><?
            if (!empty($arSortProp["RECOMMENDED"]["VALUE"])) { ?>
                <div class="tags">
                    <div class="tag">Хит сезона</div>
                </div><?
            } ?>

            <h1 class="fcard__title">
                <?php if ($arParams['IS_AJAX']): ?>
                    <a href="<?= $arResult['DETAIL_PAGE_URL'] ?>" class="link--bordered-pseudo">
                        <?php $APPLICATION->ShowTitle() ?>
                    </a>
                <?php else: ?>
                    <?php $APPLICATION->ShowTitle() ?>
                <?php endif; ?>
            </h1>
            <div class="fcard__subtitle"><?= $strFormattedNameLat ?></div>
        </div>
        <?= $this->__component->getImagesBlock($arResult); ?>
        <div class="fcard__main">
            <div class="p text">
                <?= $arResult["PREVIEW_TEXT"] ?>
            </div>
            <div class="ptgbs ptgbs--ffeatures">
                <?= \ig\CFormat::formatListMainProperties($arResult, array("COLOR_EXT" => "Y")); ?>
            </div>
        </div>
    </div>
    <?
    if (!$arParams["IS_AJAX"]) { ?>
        <div class="fcard__tabs js-header-sticky-wrapper">
        <div class="fcard__tabs-inner js-header-sticky">

            <nav class="tabs tabs--flex tabs--active-allowed" data-goto-hash-change="true">
                <div class="tabs__inner js-tabs-fixed-center">
                    <div class="tabs__scroll tabs__scroll--nopadding tabs__scroll--nopadding-always js-tabs-fixed-center-scroll">
                        <div class="tabs__scroll-inner">
                            <ul class="tabs__list">
                                <li class="tabs__item">
                                    <a class="tabs__link tabs__link--block js-goto" href="#offers"><span
                                                class="link link--ib link--dotted">Варианты</span></a></li>
                                <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                          href="#specs"><span class="link link--ib link--dotted">Особенности</span></a>
                                </li><?
                                if (false) { ?>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#tab-3"><span class="link link--ib link--dotted">Описание</span></a>
                                    </li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#tab-4"><span class="link link--ib link--dotted">Сорта вида</span></a>
                                    </li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#tab-5"><span class="link link--ib link--dotted">Что важно</span></a>
                                    </li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#useful"><span class="link link--ib link--dotted">Полезное</span></a>
                                    </li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#services"><span class="link link--ib link--dotted">Услуги</span></a>
                                    </li><?
                                }

                                if (!empty($strSimilarProducts)) { ?>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#related"><span class="link link--ib link--dotted">Похожие растения</span></a>
                                    </li><?
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

        </div>
        </div><?
    }


    $bHeightNowExtEnabled = \ig\CHelper::isPropertyEnabledForGroup("HEIGHT_NOW_EXT", $arSortProp["GROUP"]["VALUE"]);
    ?>
    <div id="offers" class="fcard__offers" data-goto-offset-element=".header, .fcard__tabs">
        <div class="thead mobile-hide">
            <div class="thead__grid">
                <div class="thead__cell thead__cell--tcard-tags">
                    Теги
                </div><?
                if ($bHeightNowExtEnabled) { ?>
                    <div class="thead__cell thead__cell--tcard-height js-sort sort-desc cursor-pointer user-select-none"
                         data-sort-selector=".tcard" data-sort-data-attr="sort-height" data-sort-order="asc">
                    Высота, см
                    <svg class="icon icon--chevron-down">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
                    </svg>
                    <svg class="icon icon--chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
                    </svg>
                    </div><?
                } ?>
                <div class="thead__cell js-sort sort-desc cursor-pointer user-select-none" data-sort-selector=".tcard"
                     data-sort-data-attr="sort-pack" data-sort-order="asc">
                    Упаковка
                    <svg class="icon icon--chevron-down">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
                    </svg>
                    <svg class="icon icon--chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
                    </svg>
                </div>
                <div class="thead__cell thead__cell--tcard-price js-sort sort-desc cursor-pointer user-select-none"
                     data-sort-selector=".tcard" data-sort-data-attr="sort-price" data-sort-order="asc">
                    Цена за шт.
                    <svg class="icon icon--chevron-down">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
                    </svg>
                    <svg class="icon icon--chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
                    </svg>
                </div>
                <div class="thead__cell thead__cell--tcard-quantity">
                    Кол-во
                </div>
                <div class="thead__cell thead__cell--tcard-price-total">
                    Итого
                </div>
                <div class="thead__cell thead__cell--tcard-actions">

                </div>
            </div>
        </div>

        <div class="fcard__offers-content js-scrollbar<?= ((count($arResult["OFFERS"]) < 10) ? ' fcard__offers-content--noscroll' : '') ?>"
             data-scrollbar-allow-page-scroll="true">
            <div class="tcards">
                <div class="tcards__inner"><?


                    foreach ($arResult["OFFERS"] as $arOffer) {
                        $arOfferProp = $arOffer["PROPERTIES"];

                        $intHeightSort = \ig\CHelper::getGroupPropertiesValues($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])["UF_SORT_ASC"];
                        if (strlen($intHeightSort) <= 0) {
                            $intHeightSort = \ig\CHelper::getGroupPropertiesValues($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])["UF_SORT"];
                        } ?>
                    <div class="tcard js-icard" data-sort-instock="<?= $arOfferProp["AVAILABLE"]["VALUE_SORT"] ?>"
                         data-sort-height="<?= intval($intHeightSort) ?>"
                         data-sort-pack="<?= $arOfferProp["PACK"]["VALUE_SORT"] ?>"
                         data-sort-price="<?= $arOffer["MIN_PRICE_VALUE"] ?>">
                        <div class="tcard__inner js-icard-offer-actions">
                            <div class="tcard__grid" data-id="<?= $arOffer["ID"] ?>"
                                 data-cart-data='{"offerID": <?= $arOffer["ID"] ?>}'>
                                <div class="tcard__col tcard__col--tags">
                                    <div class="mobile-show">
                                        <div class="tags"><?
                                            if (!empty($arOfferProp["ACTION"]["VALUE"])) {
                                                echo '<div class="tag">Скидка</div>';
                                            }

                                            if (!empty($arOfferProp["NEW"]["VALUE"])) {
                                                echo '<div class="tag">Новинка</div>';
                                            }
                                            ?>
                                            <div class="tag tag--circled<?= (\ig\CHelper::isAvailable($arOfferProp["AVAILABLE"]["VALUE"]) ? ' color-green' : '') ?>"><?= $arOfferProp["AVAILABLE"]["VALUE"] ?></div>
                                        </div>
                                    </div>
                                    <div class="mobile-hide">
                                        <div class="tag-icons">
                                            <div class="tag-icon tooltip tablet-small-show-inline-block"
                                                 data-title="<?= $arOfferProp["AVAILABLE"]["VALUE"] ?>">
                                                <span class="icon icon-circle<?= (\ig\CHelper::isAvailable($arOfferProp["AVAILABLE"]["VALUE"]) ? ' icon-circle--active' : ' icon-circle--inactive') ?>"></span>
                                            </div><?
                                            if (!empty($arOfferProp["NEW"]["VALUE"])) { ?>
                                                <div class="tag-icon tooltip" data-title="Новинка">
                                                <svg class="icon">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
                                                </svg>
                                                </div><?
                                            }

                                            if (!empty($arOfferProp["ACTION"]["VALUE"])) { ?>
                                                <div class="tag-icon tooltip tooltipstered tooltip-inited tooltip-active"
                                                     data-title="Акция">
                                                <svg class="icon">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
                                                </svg>
                                                </div><?
                                            } ?>
                                        </div>
                                    </div>
                                </div><?
                                if ($bHeightNowExtEnabled) { ?>
                                    <div class="tcard__col tcard__col--param tcard__col--height">
                                    <div class="ptgbs ptgbs--params">
                                        <div class="ptgb ptgb--param-height">
                                            <div class="ptgb__inner">
                                                <div class="ptgb__content">
                                                    <div class="ptgb__subtitle">Высота (см)</div>
                                                    <div class="ptgb__title">
                                                        <?= \ig\CHelper::getGroupPropertiesValues($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])["UF_NAME"] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div><?
                                }
                                if ($arSort["PROPERTIES"]["GROUP"]["VALUE"] == 'TEg4drYJ') {
                                    if (strlen($arOffer["PROPERTIES"]["CONTAINER_SIZE"]["VALUE"])) {
                                        $strResult .= '
								<div class="ptgb ptgb--param-pack">
									<div class="ptgb__inner">
										<div class="ptgb__content">
											<div class="ptgb__subtitle">
												' . $arOffer["PROPERTIES"]["CONTAINER_SIZE"]["NAME"] . '
											</div>
											<div class="ptgb__title">
												<div class="ptgb__title-inner">' . $arOffer["PROPERTIES"]["CONTAINER_SIZE"]["VALUE"] . '</div>
											</div>
										</div>
									</div>
								</div>';
                                    }
                                } ?>
                                <div class="tcard__col tcard__col--param tcard__col--pack">
                                    <div class="ptgbs ptgbs--params">
                                        <div class="ptgb ptgb--param-pack">
                                            <div class="ptgb__inner">
                                                <div class="ptgb__content">
                                                    <div class="ptgb__subtitle">Упаковка</div>
                                                    <div class="ptgb__title">
                                                        <div class="ptgb__title-inner">
                                                            <?= \ig\CFormat::formatContainerPack($arOffer, $arSort) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tcard__col tcard__col--price">

                                    <div class="ptgbs ptgbs--actions">

                                        <div class="ptgb ptgb--action-price">
                                            <div class="ptgb__inner">
                                                <div class="ptgb__content">
                                                    <div class="ptgb__subtitle">Цена</div>
                                                    <div class="ptgb__title ptgb__title--textfield">
                                                        <div class="js-icard-price-discount-wrapper<?= ($arOffer["BASE_PRICE_VALUE"] > 0 ? '' : ' hidden') ?>">
                                                            <div class="tcard__price color-active"><span
                                                                        class="font-bold js-icard-price-discount"><?= \ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                <span class="font-light">₽</span></div>
                                                        </div>
                                                        <div class="js-icard-price-wrapper<?= ($arOffer["BASE_PRICE_VALUE"] > 0 ? ' hidden' : '') ?>">
                                                            <div class="tcard__price"><span
                                                                        class="font-bold js-icard-price"><?= \ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                <span class="font-light">₽</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcard__col tcard__col--quantity">
                                    <div class="ptgbs ptgbs--actions">
                                        <div class="ptgb ptgb--action-quantity">
                                            <div class="ptgb__inner">
                                                <div class="ptgb__content">
                                                    <div class="ptgb__subtitle">Кол-во</div>
                                                    <div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel"
                                                           class="input-spin__textfield textfield keyfilter-pint js-icard-spinner"
                                                           data-spin="spinner" data-rule="quantity" data-min="0"
                                                           data-max="9999" data-step="1"
                                                           data-price="<?= $arOffer["MIN_PRICE_VALUE"] ?>" value="0"
                                                           maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcard__col tcard__col--price-total">
                                    <div class="ptgbs ptgbs--actions">
                                        <div class="ptgb ptgb--action-total">
                                            <div class="ptgb__inner">
                                                <div class="ptgb__content">
                                                    <div class="ptgb__subtitle">Сумма</div>
                                                    <div class="ptgb__title ptgb__title--textfield">
                                                        <div class="tcard__price-total inactive"><strong
                                                                    class="js-icard-price-total">0</strong> <span
                                                                    class="font-light">₽</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcard__col tcard__col--actions">
                                    <div class="tcard__actions-btns btns">
                                        <button class="btn btn--cart js-cart-add js-cart-add_<?= $arOffer["ID"] ?>"
                                                data-label='<span class="mobile-hide">В корзине</span>'
                                                data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
                                            <span class="btn__title"><span class="mobile-hide">В корзину</span></span>
                                            <span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
                                        </button>
                                        <button class="btn btn--icon hidden js-cart-remove js-cart-remove_<?= $arOffer["ID"] ?>">
                                            <svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
                                            </svg>
                                        </button>

                                        <button class="btn btn--icon btn--favorite js-favorites-toggle js-favoriteButton_<?= $arOffer["ID"] ?>">
                                            <svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
                                            </svg>
                                            <svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
                                            </svg>
                                            <svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div><?
                    } ?>
                </div>
            </div>
        </div>
        <div class="fcard__offers-more">
            <a class="btn btn--fullwidth btn--more-small js-toggle-element" data-toggle-class="active"
               data-toggle-element-class="" data-toggle-label=".link" data-toggle-change-label="Свернуть все варианты"
               href="#offers">
                <svg class="icon icon--linespacing">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-linespacing"></use>
                </svg>
                <span class="link link--dotted link--ib">Показать все варианты</span>
            </a>
        </div>
    </div>
</div>