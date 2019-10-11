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
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-index">
    <h1 class="h1"><?php $APPLICATION->ShowTitle() ?></h1>
    <?php foreach ($arResult['IBLOCKS'] as $iBlock): ?>
        <?php if (count($iBlock['ITEMS']) > 0): ?>
            <b><?= $iBlock['NAME'] ?></b>
            <ul>
                <?php foreach ($iBlock['ITEMS'] as $item): ?>
                    <li><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif ?>
    <?php endforeach; ?>
</div>
