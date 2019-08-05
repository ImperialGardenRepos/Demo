<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="section section--grey section--cart">
    <div class="container">
        <form class="form form--cart form-validate" method="post" action="<?= $APPLICATION->GetCurDir() ?>">
            <h1>Оформление заказа</h1>
            <h2>Товары в корзине</h2>
            <div class="ccards">
                <div class="ccards__inner"><?
                    foreach ($arResult["BASKET_ITEMS"] as $arBasketItem) {
                        $intProductID = $arBasketItem["PRODUCT_ID"];

                        $arOffer = $arResult["IBLOCK"]["OFFERS"][$intProductID];
                        $arOfferProp = $arOffer["PROPERTIES"];
                        $arSort = $arResult["IBLOCK"]["PRODUCTS"][$arOfferProp["CML2_LINK"]["VALUE"]];
                        $arSortProp = $arSort["PROPERTIES"];

                        if ($arSort["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('catalog')) {
                            if (!empty($arSortProp["IS_RUSSIAN"]["VALUE"])) {
                                $strName = $arSort["NAME"];
                                $strName2 = $arSortProp["NAME_LAT"]["VALUE"];
                            } else {
                                $strName = $arSortProp["NAME_LAT"]["VALUE"];
                                $strName2 = $arSort["NAME"];
                            }

                            if (empty($strName)) {
                                $strName = $strName2;
                                $strName2 = '';
                            }

                            $strGroup = $arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["NAME"];
                        } else {
                            $strName = $arSort["NAME"];
                            $strGroup = '';
                        }

                        $arSectionNav = $arResult["IBLOCK"]["SECTIONS"][$arSort["IBLOCK_SECTION_ID"]]["NAV"];

                        $strFormattedName = \ig\CFormat::formatPlantTitle(
                            (!empty($arSortProp["IS_VIEW"]["VALUE"]) ? '' : $strName),
                            $arSectionNav[0]["NAME"],
                            $arSectionNav[1]["NAME"]);

                        $floatBasePrice = (empty($arOffer["BASE_PRICE_VALUE"]) ? $arOffer["MIN_PRICE_VALUE"] : $arOffer["BASE_PRICE_VALUE"]);
                        $floatMinPrice = (empty($arBasketItem["PRICE"]) ? $arBasketItem["BASE_PRICE"] : $arBasketItem["PRICE"]);

//							$floatBasePrice = $arOffer["BASE_PRICE_VALUE"];
//							$floatMinPrice = (empty($arBasketItem["DISCOUNT_PRICE"])?$arBasketItem["BASE_PRICE"]:$arBasketItem["DISCOUNT_PRICE"]);

                        if (empty($floatMinPrice)) $floatMinPrice = $floatBasePrice;

                        if ($floatBasePrice == $floatMinPrice) {
                            unset($floatBasePrice);
                        }

                        $strTag = '';
                        if (!empty($arSortProp["RECOMMENDED"]["VALUE"])) {
                            $strTag .= '<div class="tag">Рекомендация ig</div>';
                        }

                        if (!empty($arSortProp["ACTION"]["VALUE"])) {
                            $strTag .= '<div class="tag">Скидка</div>';
                        }

                        if (!empty($arSortProp["NEW"]["VALUE"])) {
                            $strTag .= '<div class="tag">Новинка</div>';
                        }

                        $arImages = \ig\CHelper::getImagesArray($arOffer, $arSort, array(
                            "RESIZE" => array("WIDTH" => 140, "HEIGHT" => 140), "CNT" => 1
                        ));

                        $arImageData = \ig\CUtil::getFirst($arImages);


                        $strImage = '';
                        if (!empty($arImageData)) {
                            $strImage .= '<img class="active" data-lazy-src="' . $arImageData["RESIZE"]["src"] . '" src="' . SITE_TEMPLATE_PATH . '/img/blank.png" data-src-full="' . $arImageData["SRC"] . '" data-ihs-content="' . $strTitle . '" alt="' . $strTitle . '">';
                        } else {
                            $strImage = '<img class="active" src="' . SITE_TEMPLATE_PATH . '/img/blank.png" alt="' . $arSort["NAME"] . '">';
                        }
                        ?>
                        <div class="ccard js-icard js-icard-use-discounts js-icard-to-remove">
                        <div class="ccard__inner js-icard-offer-actions">
                            <div class="ccard__content" data-id="<?= $arOffer["ID"] ?>"
                                 data-cart-data='<?= json_encode(array("offerID" => intval($arOffer["ID"]), "getDiscount" => 'Y')) ?>'>
                                <div class="ccard__header">
                                    <div class="cols-wrapper">
                                        <div class="cols cols--auto">
                                            <div class="col">
                                                <div class="breadcrumb"><?
                                                    if (false) { ?>
                                                        <a
                                                        href="<?= $arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["URL"] ?>"><?= $strGroup ?></a><?
                                                    }

                                                    if (!empty($strGroup)) { ?>
                                                        <?= $strGroup ?>
                                                        <svg class="icon icon--long-arrow">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
                                                        </svg><?
                                                    } ?>
                                                    <a target="_blank"
                                                       href="<?= $arSectionNav[0]["SECTION_PAGE_URL"] ?>"><?= $arSectionNav[0]["NAME"] ?></a><?
                                                    if (isset($arSectionNav[1])) { ?>
                                                        <svg class="icon icon--long-arrow">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
                                                        </svg>
                                                        <a target="_blank"
                                                           href="<?= $arSectionNav[1]["SECTION_PAGE_URL"] ?>"><?= $arSectionNav[1]["NAME"] ?></a><?
                                                    } ?>
                                                </div>
                                            </div><?
                                            if (!empty($strTag)) { ?>
                                                <div class="col text-align-right">
                                                <div class="tags"><?= $strTag ?></div>
                                                </div><?
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="ccard__title">
                                        <a target="_blank" href="<?= $arSort["DETAIL_PAGE_URL"] ?>"
                                           class="link--ib link--dotted" data-fancybox data-type="ajax"
                                           data-src="<?= $arSort["DETAIL_PAGE_URL"] ?>"><?= $strFormattedName ?></a>
                                    </div>
                                </div>

                                <div class="ccard__main">
                                    <div class="ccard__image image-slider image-slider--noshadow">
                                        <?= $strImage ?>
                                    </div>
                                    <div class="ccard__pa">

                                        <div class="ccard__params"><?
                                            if (!empty($strTag)) { ?>
                                                <div class="tags mobile-show">
                                                <?= $strTag ?>
                                                </div><?
                                            } ?>
                                            <div class="ptgbs ptgbs--params">
                                                <?
                                                if (!empty($arOfferProp["SIZE"]["VALUE"])) { ?>
                                                    <div class="ptgb ptgb--param-height">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Объем</div>
                                                            <div class="ptgb__title"><?= $arOfferProp["SIZE"]["VALUE"] . ' ' . $arOfferProp["UNIT"]["VALUE"] ?></div>
                                                        </div>
                                                    </div>
                                                    </div><?
                                                }

                                                if (!empty($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])) { ?>
                                                    <div class="ptgb ptgb--param-height">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Высота (cм)</div>
                                                            <div class="ptgb__title"><?= \ig\CHelper::getGroupPropertiesValues($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])["UF_NAME"] ?></div>
                                                        </div>
                                                    </div>
                                                    </div><?
                                                }

                                                if ($arOfferProp["CROWN_WIDTH"]["VALUE"] > 0) { ?>
                                                    <div class="ptgb ptgb--param-width tablet-hide">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Ширина кроны (см)</div>
                                                            <div class="ptgb__title"><?= $arOfferProp["CROWN_WIDTH"]["VALUE"] ?></div>
                                                        </div>
                                                    </div>
                                                    </div><?
                                                } ?>
                                                <div class="ptgb ptgb--param-pack"><?
                                                    if (!empty($arOfferProp["PACK"]["VALUE"])) { ?>
                                                        <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Упаковка</div>
                                                            <div class="ptgb__title">
                                                                <div class="ptgb__title-inner"><?= $arOfferProp["PACK"]["VALUE"] ?></div>
                                                            </div>
                                                        </div>
                                                        </div><?
                                                    } ?>
                                                </div>
                                                <div class="ptgb ptgb--param-price">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Цена шт.</div>


                                                            <div class="ptgb__title">
                                                                <div class="js-icard-price-discount-wrapper<?= ($floatBasePrice > 0 ? '' : ' hidden') ?>">
                                                                    <div class="ccard__price color-active"><span
                                                                                class="font-bold js-icard-price-discount"><?= \ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                        <span class="font-light">₽</span></div>
                                                                </div>
                                                                <div class="js-icard-price-wrapper<?= ($floatBasePrice > 0 ? ' hidden' : '') ?>">
                                                                    <div class="ccard__price"><span
                                                                                class="font-bold js-icard-price"><?= \ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                        <span class="font-light">₽</span></div>
                                                                </div>
                                                            </div>


                                                            <?
                                                            if (false) { ?>
                                                                <div class="ptgb__title">
                                                                <div class="ccard__price<?= (isset($floatBasePrice) ? ' color-active' : '') ?>">
                                                                    <span class="font-bold js-icard-price"><?= \ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                    <span class="font-light">₽</span></div>
                                                                </div><?
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="ccard__actions">

                                            <div class="ptgbs ptgbs--actions">

                                                <div class="ptgb ptgb--action-price mobile-show-table-cell">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Цена</div>
                                                            <div class="ptgb__title ptgb__title--textfield">

                                                                <div class="ccard__price-total"><span
                                                                            class="font-bold js-icard-price"><?= \ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN" => '')) ?></span>
                                                                    <span class="font-light">₽</span></div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ptgb ptgb--action-quantity">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Кол-во</div>
                                                            <div class="ptgb__title ptgb__title--textfield">
						                                            <span class="input-spin touch-focused"
                                                                          data-trigger="spinner">
						                                                <span class="input-spin__btn" data-spin="down">&minus;</span>
						                                                <span data-spin-clone
                                                                              class="input-spin__value hidden"><?= $arBasketItem["QUANTITY"] ?></span>
						                                                <input type="tel"
                                                                               class="input-spin__textfield textfield keyfilter-pint js-icard-spinner"
                                                                               data-spin="spinner"
                                                                               name="QUANTITY[<?= $arOffer["ID"] ?>]"
                                                                               data-rule="quantity" data-min="0"
                                                                               data-max="9999" data-step="1"
                                                                               value="<?= $arBasketItem["QUANTITY"] ?>"
                                                                               maxlength="4" size="6">
						                                                <span class="input-spin__btn"
                                                                              data-spin="up">+</span>
						                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ptgb ptgb--action-total">
                                                    <div class="ptgb__inner">
                                                        <div class="ptgb__content">
                                                            <div class="ptgb__subtitle">Сумма</div>
                                                            <div class="ptgb__title ptgb__title--textfield">
                                                                <div class="ccard__price-total"><strong
                                                                            class="js-icard-price-total"><?= \ig\CFormat::getFormattedPrice($arBasketItem["SUM_NUM"], "RUB", array("RUB_SIGN" => '')) ?></strong>
                                                                    <span class="font-light">₽</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ccard__actions-btns-right btns">
                                                <button class="btn btn--icon js-cart-remove">
                                                    <svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
                                                    </svg>
                                                </button>
                                                <button class="btn btn--icon btn--favorite js-favorites-toggle">
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
                            </div>

                        </div>
                        </div><?
                    } ?>
                </div>
            </div>
            <? include("floatBar.php"); ?>
            <h2>Ваши данные</h2>

            <div class="form__items">

                <div class="form__item">

                    <div class="cols-wrapper">
                        <div class="cols"><?
                            $arPropTmp = array();
                            foreach ($arResult["JS_DATA"]["ORDER_PROP"]["properties"] as $arOrderProp) {
                                $arOrderProp["VALUE"] = $arResult["USER_VALS"]["ORDER_PROP"][$arOrderProp["ID"]];
                                $arPropTmp[$arOrderProp["CODE"]] = $arOrderProp;
                            }


                            $arOrderProp = $arPropTmp["NAME"]; ?>
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="text" class="textfield"
                                               name="ORDER_PROP_<?= $arOrderProp["ID"] ?>"<?= ($arOrderProp["REQUIRED"] == 'Y' ? ' data-rule-required="true"' : '') . ($arOrderProp["IS_EMAIL"] == 'Y' ? ' data-rule-email="true"' : '') ?>
                                               value="<?= $arOrderProp["VALUE"] ?>">
                                        <div class="textfield-placeholder"><?= $arOrderProp["NAME"] ?></div><?
                                        if ($arOrderProp["REQUIRED"] == 'Y') { ?>
                                            <div class="textfield-after color-active">*</div><?
                                        } ?>
                                    </div>
                                </div>
                            </div><?
                            $arOrderProp = $arPropTmp["EMAIL"]; ?>
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="text" class="textfield"
                                               name="ORDER_PROP_<?= $arOrderProp["ID"] ?>"<?= ($arOrderProp["REQUIRED"] == 'Y' ? ' data-rule-required="true"' : '') . ($arOrderProp["IS_EMAIL"] == 'Y' ? ' data-rule-email="true"' : '') ?>
                                               value="<?= $arOrderProp["VALUE"] ?>">
                                        <div class="textfield-placeholder"><?= $arOrderProp["NAME"] ?></div><?
                                        if ($arOrderProp["REQUIRED"] == 'Y') { ?>
                                            <div class="textfield-after color-active">*</div><?
                                        } ?>
                                    </div>
                                </div>
                            </div><?
                            $arOrderProp = $arPropTmp["PHONE"]; ?>
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="text" class="textfield"
                                               name="ORDER_PROP_<?= $arOrderProp["ID"] ?>"<?= ($arOrderProp["REQUIRED"] == 'Y' ? ' data-rule-required="true"' : '') . ($arOrderProp["IS_EMAIL"] == 'Y' ? ' data-rule-email="true"' : '') ?>
                                               value="<?= $arOrderProp["VALUE"] ?>">
                                        <div class="textfield-placeholder"><?= $arOrderProp["NAME"] ?></div><?
                                        if ($arOrderProp["REQUIRED"] == 'Y') { ?>
                                            <div class="textfield-after color-active">*</div><?
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><?
                $arOrderProp = $arPropTmp["ADDRESS"]; ?>
                <div class="form__item">
                    <div class="form__item-field form__item-field--error-absolute">
                        <div class="textfield-wrapper">
                            <input type="text" class="textfield" name="ORDER_PROP_<?= $arOrderProp["ID"] ?>"
                                   value="<?= $arOrderProp["VALUE"] ?>"<?= ($arOrderProp["REQUIRED"] == 'Y' ? ' data-rule-required="true"' : '') ?>>
                            <div class="textfield-placeholder"><?= $arOrderProp["NAME"] ?></div><?
                            if ($arOrderProp["REQUIRED"] == 'Y') { ?>
                                <div class="textfield-after color-active">*</div><?
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="form__item">
                    <div class="form__item-field form__item-field--error-absolute">
                        <div class="textfield-wrapper">
                            <input type="text" class="textfield"
                                   value="<?= $arResult["USER_VALS"]["ORDER_DESCRIPTION"] ?>" name="ORDER_DESCRIPTION">
                            <div class="textfield-placeholder">Комментарий к заказу</div>
                        </div>
                    </div>
                </div>
            </div>

            <h2>Доставка</h2>

            <div class="form__items form__items--delivery">
                <div class="form__item">
                    <div class="checkboxes checkboxes--w-title">
                        <div class="checkboxes__inner"><?
                            foreach ($arResult["DELIVERY"] as $arDelivery) { ?>
                                <label class="checkbox checkbox--w-title checkbox--radio checkbox--block checkbox-plain-js">
                                <div class="checkbox__icon"></div>
                                <input type="radio" name="<?= $arDelivery["FIELD_NAME"] ?>"
                                       value="<?= $arDelivery["ID"] ?>"<?= ($arDelivery["ID"] == $arResult["USER_VALS"]["DELIVERY_ID"] ? ' checked' : '') ?>>
                                <div>
                                    <div class="checkbox__title"><?= $arDelivery["NAME"] ?></div><?
                                    if (!empty($arDelivery["DESCRIPTION"])) { ?>
                                        <div class="checkbox__summary">
                                        <?= $arDelivery["DESCRIPTION"] ?>
                                        </div><?
                                    } ?>
                                </div>
                                </label><?
                            }

                            if (false) { ?>

                                <label class="checkbox checkbox--w-title checkbox--radio checkbox--block checkbox-plain-js">
                                    <div class="checkbox__icon"></div>
                                    <input type="radio" name="delivery" value="2">
                                    <div>
                                        <div class="checkbox__title">Самовывоз</div>
                                        <div class="checkbox__summary">
                                            <p>Вы можете самостоятельно забрать свой заказ после того, как с Вами
                                                свяжется менеджер и подтвердит наличие и готовность заказа к выдаченна
                                                территории садового
                                                центра IG.</p>

                                            <p>Пожалуйста, прежде чем приежать за заказом согласуйте время и дату
                                                самовывоза - дождитесь звонка нашего менеджера или посвоните сами по
                                                телефону:</p>

                                            <div class="phone">8 800 222 47 06</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="checkbox checkbox--w-title checkbox--radio checkbox--block checkbox-plain-js">
                                    <div class="checkbox__icon"></div>
                                    <input type="radio" name="delivery" value="3" disabled>
                                    <div>
                                        <div class="checkbox__title">Бесплатная доставка</div>
                                        <div class="checkbox__summary">
                                            <p>При сумме заказа от 70 000 ₽ - доставка бесплатная.</p>
                                        </div>
                                    </div>
                                </label><?
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form__actions" id="form-cart-footer">
                <div class="cols-wrapper">
                    <div class="cols">
                        <div class="col">
                            <div class="checkboxes">
                                <div class="checkboxes__inner">
                                    <label class="checkbox checkbox--block checkbox-plain-js">
                                        <div class="checkbox__icon"></div>
                                        <div class="form__item-field form__item-field--error-absolute">
                                            <input type="checkbox" class="checkbox__input" name="OFERTA" value="Y"
                                                   checked data-rule-required="true"
                                                   data-msg-required="Вам нужно согласиться с условиями оферты.">
                                            Я ознакомлен с условиями и согласен с <a target="_blank"
                                                                                     href="/download/politika.pdf">политикой
                                                конфиденциальности</a>
                                        </div>
                                    </label><?
                                    $arOrderProp = $arPropTmp["SUBSCRIBE"]; ?>
                                    <label class="checkbox checkbox--block checkbox-plain-js">
                                        <div class="checkbox__icon"></div>
                                        <input type="checkbox"
                                               name="ORDER_PROP_<?= $arOrderProp["ID"] ?>"<?= ($arResult["USER_VALS"]["ORDER_PROP"][$arOrderProp["ID"]] == 'Y' ? ' checked' : '') ?>
                                               value="Y">
                                        Хочу получать новости и акции по почте
                                    </label>

                                </div>
                            </div>

                        </div>
                        <div class="col text-align-right text-align-center-on-mobile">
                            <button type="submit" name="confirmorder" value="Y" class="btn btn--large">Отправить заказ
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <input type="hidden" name="<?= $arParams["ACTION_VARIABLE"] ?>" value="processOrder">
        </form>

    </div>
</div>