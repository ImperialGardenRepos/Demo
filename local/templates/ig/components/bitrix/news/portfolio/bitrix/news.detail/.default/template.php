<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use ig\CRegistry;

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

$this->AddEditAction($item['ID'], $arResult['ADD_LINK']);
$this->AddEditAction($item['ID'], $arResult['EDIT_LINK']);
$this->AddDeleteAction($item['ID'], $arResult['DELETE_LINK']);
?>
<div class="section section--grey section--article text section--fullheight">
    <div class="container" id="<?= $this->GetEditAreaId($arResult['ID']) ?>">
        <h1><?= $arResult['NAME'] ?></h1>

        <?php if ($arResult['IMAGES'] !== []): ?>
            <div class="image-slider image-slider--autosize js-images-hover-slider js-images-popup-slider cursor-pointer">
                <?php $activeClass = ' active'; ?>
                <?php foreach ($arResult['IMAGES'] as $image): ?>
                    <?php /** @var array $image */ ?>
                    <img class="<?= $activeClass ?>" data-lazy-src="<?= $image['PREVIEW'] ?>"
                         src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png" data-src-full="<?= $image['DETAIL'] ?>"
                         data-ihs-content="<?= $arResult['NAME'] ?>" alt="<?= $arResult['NAME'] ?>">
                    <?php $activeClass = ''; ?>
                <?php endforeach; ?>
                <div class="image-slider__size">
                    <img src="<?= $arResult['IMAGES'][0]['PREVIEW'] ?>" alt="<?= $arResult['NAME'] ?>">
                </div>
            </div>
        <?php endif; ?>

        <?php $hasVideoClass = '' ?>
        <?php if ($arResult['PROPERTIES']['VIDEO']['VALUE'] !== ''): ?>
            <?php
            $hasVideoClass = ' pparams--center';
            $videoPreviewPictureSrc = $arResult['PROPERTIES']['VIDEO_PREVIEW']['VALUE'] === '' ? SITE_TEMPLATE_PATH . '/pic/video-image.jpg' : $arResult['PROPERTIES']['VIDEO_PREVIEW']['VALUE'];
            ?>

            <a class="ivideo ivideo--right" href="<?= $arResult['PROPERTIES']['VIDEO']['VALUE'] ?>" target="_blank"
               data-fancybox>
                <div class="ivideo__image">
                    <img src="<?= $videoPreviewPictureSrc ?>" alt="<?= $arResult['NAME'] ?> &ndash; видео">
                </div>
                <div class="ivideo__icon">
                    <svg class="icon">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-play-circle"></use>
                    </svg>
                </div>
                <?php if ($arResult['PROPERTIES']['VIDEO']['DESCRIPTION'] !== ''): ?>
                    <div class="ivideo__title"><?= $arResult['PROPERTIES']['VIDEO']['DESCRIPTION'] ?></div>
                <?php endif; ?>
            </a>
        <?php endif; ?>

        <div class="pparams pparams--large<?= $hasVideoClass ?>">
            <?php if ($arResult['YEAR_FINISH'] !== null): ?>
                <div class="pparam pparam--large">
                    <div class="pparam__title">Дата окончания работ</div>
                    <div class="pparam__value"><?= $arResult['YEAR_FINISH'] ?> год</div>
                </div>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['TOTAL_AREA']['VALUE'] !== ''): ?>
                <div class="pparam pparam--large">
                    <div class="pparam__title">Общая площадь</div>
                    <div class="pparam__value"><?= $arResult['PROPERTIES']['TOTAL_AREA']['VALUE'] ?> га</div>
                </div>
            <?php endif; ?>
        </div>
        <div class="p text">
            <?php if ($arResult['DETAIL_TEXT'] !== ''): ?>
                <p>
                    <?= $arResult['DETAIL_TEXT'] ?>
                </p>
            <?php endif; ?>

            <?php if ($arResult['PROPERTIES']['TASKS']['VALUE']): ?>
                <p>
                    <strong>Решенные задачи</strong>
                </p>
                <ul class="list-cols-3">
                    <?php foreach ($arResult['PROPERTIES']['TASKS']['VALUE'] as $task): ?>
                        <li><?= $task ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="h2">Заказать подобный проект</div>
        <?php
        /**
         * ToDo:: FORM Move forms to component
         */
        ?>
        <form class="form form--quote form-validate js-action" action="<?= $APPLICATION->GetCurPage() ?>" method="post">
            <div class="form__items js-message js-message-container">
                <div class="form__item">
                    <div class="cols-wrapper">
                        <div class="cols">
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="text" class="textfield" name="F[NAME]" data-rule-required="true">
                                        <div class="textfield-placeholder">Ваше имя</div>
                                        <div class="textfield-after color-active">*</div>
                                    </div>
                                </div>

                            </div>
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="email" class="textfield" name="F[EMAIL]" data-rule-required="true"
                                               data-rule-email="true">
                                        <div class="textfield-placeholder">E-mail</div>
                                        <div class="textfield-after color-active">*</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form__item-field form__item-field--error-absolute">
                                    <div class="textfield-wrapper">
                                        <input type="tel" class="textfield mask-phone-ru" name="F[PHONE]"
                                               data-rule-required="true" data-rule-phonecomplete="true">
                                        <div class="textfield-placeholder">Телефон</div>
                                        <div class="textfield-after color-active">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form__item form__item--lm">
                    <div class="cols-wrapper">
                        <div class="cols">
                            <div class="col vmiddle">
                                <div class="checkboxes">
                                    <div class="checkboxes__inner">
                                        <div class="checkbox checkbox--noinput">
                                            Нажимая на кнопку “отправить”, Вы соглашаетесь
                                            с&nbsp;<a target="_blank"
                                                      href="<?= CRegistry::get('politika-konfidentsialnosti') ?>">политикой
                                                конфиденциальности</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col tablet-hide"></div>
                            <div class="col vmiddle text-align-right text-align-center-on-mobile">
                                <button type="submit" class="btn btn--medium minw200 active">Запросить</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <input type="hidden" name="F[OBJECT_ID]" value="<?= $arResult["ID"] ?>">
            <input type="hidden" name="frmProjectDetailSent" value="Y">
        </form>
        <?php if ($arResult['MORE_OBJECT'] !== []) : ?>
            <div class="h2">Ещё проекты</div>
            <div class="tgbs tgbs--project">
                <div class="tgbs__inner">
                    <?php foreach ($arResult['MORE_OBJECT'] as $featuredObject): ?>
                        <div class="tgb tgb--img-hover tgb--project">
                            <a class="tgb__inner" href="<?= $featuredObject['DETAIL_PAGE_URL'] ?>">
                                <?php if ($featuredObject['IMAGE'] !== null): ?>
                                    <div class="tgb__image img-to-bg">
                                        <img data-lazy-src="<?= $featuredObject['IMAGE'] ?>"
                                             src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png"
                                             alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="tgb__overlay">
                                    <div class="tgb__overlay-part">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="tgb__title h2"><?= $featuredObject['NAME'] ?></div>
                                            <div class="pparams">
                                                <?php if ($featuredObject['YEAR_FINISH'] !== null): ?>
                                                    <div class="pparam">
                                                        <div class="pparam__title">Дата окончания работ</div>
                                                        <div class="pparam__value"><?= $featuredObject['YEAR_FINISH'] ?>
                                                            год
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($featuredObject['PROPERTIES']['TOTAL_AREA']['VALUE']): ?>
                                                    <div class="pparam">
                                                        <div class="pparam__title">Общая площадь</div>
                                                        <div class="pparam__value">
                                                            <?= $featuredObject['PROPERTIES']['TOTAL_AREA']['VALUE'] ?> га
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tgb__overlay-part mobile-hide">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="tgb__summary text">
                                                <p><?= $featuredObject['PREVIEW_TEXT'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>