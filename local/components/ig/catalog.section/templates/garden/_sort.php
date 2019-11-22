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
/** @var int $offersIndex */

switch ($arParams['ORDER_FIELD']) {
    case 'FULL_NAME':
        $activeSortParam = 'name';
        break;
    case 'MIN_PRICE':
        $activeSortParam = 'price';
        break;
    case 'SORT':
        $activeSortParam = 'popular';
        break;
    default:
        $activeSortParam = 'name';
        break;
}
$sortDirection = $arParams['ORDER_DIRECTION'] === 'ASC' ? 'asc' : 'desc';
$sortDirectionSwitched = $sortDirection === 'asc' ? 'desc' : 'asc';

$sortParams = [
    [
        'name' => 'названию',
        'code' => 'name',
    ],
    [
        'name' => 'цене',
        'code' => 'price',
    ],
    [
        'name' => 'популярности',
        'code' => 'popular',
    ],
];

$ascIcon = '▲';
$descIcon = '▼';
?>
<div class="sort-list text">
    <p>
        <span class="sort-list__heading">Сортировать по </span>
        <?php foreach ($sortParams as $param): ?>
            <?php if ($param['code'] === $activeSortParam): ?>
                <span class="sort-list__icon"><?= $sortDirection === 'asc' ? $ascIcon : $descIcon ?></span>
            <?php endif; ?>
            <span class="sort-list__param <?= $param['code'] === $activeSortParam ? ' sort-list__param--active' : '' ?>">
                <span class="link link--bordered-pseudo js-sort-param" data-value="<?= $param['code'] ?>"
                      data-direction="<?= $param['code'] === $activeSortParam ? $sortDirectionSwitched : 'asc' ?>">
                <?= $param['name'] ?>
                </span>
            </span>
            &nbsp;
        <?php endforeach; ?>
    </p>
</div>
