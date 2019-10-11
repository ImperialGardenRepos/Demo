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
/** @var CBitrixComponent $componentName */
?>
<?php if ($arResult['ITEMS'] !== []): ?>
    <div class="container">
        <div class="html-map">
            <h1 class="h1--large html-map__heading">Карта сайта</h1>
            <div class="html-map__level html-map__level--root">
                <?php foreach ($arResult['ITEMS'] as $firstLevelLink => $firstLevel): ?>
                    <p><strong><a href="<?= $firstLevelLink ?>"><?= $firstLevel['NAME'] ?></a></strong></p>
                    <?php if (isset($firstLevel['CHILDREN'])): ?>
                        <div class="html-map__level html-map__level--child">
                            <?php foreach ($firstLevel['CHILDREN'] as $secondLevelLink => $secondLevel): ?>
                                <p><a href="<?= $secondLevelLink ?>"><?= $secondLevel['NAME'] ?></a></p>
                                <?php if (isset($secondLevel['CHILDREN'])): ?>
                                    <div class="html-map__level html-map__level--child">
                                        <?php foreach ($secondLevel['CHILDREN'] as $thirdLevelLink => $thirdLevel): ?>
                                            <p><a href="<?= $thirdLevelLink ?>"><?= $thirdLevel['NAME'] ?></a></p>
                                            <?php if (isset($thirdLevel['CHILDREN'])): ?>
                                                <div class="html-map__level html-map__level--child html-map__level--last">
                                                    <?php foreach ($thirdLevel['CHILDREN'] as $fourthLevelLink => $fourthLevel): ?>
                                                        <p>
                                                            <i><a href="<?= $fourthLevelLink ?>"><?= $thirdLevel['NAME'] ?> <?= $fourthLevel['NAME'] ?></a></i>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
