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
?>
<?php
/** ToDo: normally */
/** ToDo: sections menu */
$sections = [];
$sectionModel = CIBlockSection::GetList(
    [],
    [
        'IBLOCK_ID' => [CATALOG_GARDEN_IBLOCK_ID],
        'ACTIVE' => 'Y',
    ],
    false,
    [
        'ID',
        'IBLOCK_ID',
        'LIST_PAGE_URL',
        'SECTION_PAGE_URL',
        'DEPTH_LEVEL',
        'NAME',
    ]
);
while ($section = $sectionModel->GetNext()) {
    if ((int)$section['DEPTH_LEVEL'] === 1) {
        $sections[$section['LIST_PAGE_URL']]['CHILDREN'][$section['SECTION_PAGE_URL']]['NAME'] = $section['NAME'];
    }
    if ((int)$section['DEPTH_LEVEL'] === 2) {
        $sectionUrlArray = explode('/', trim($section['SECTION_PAGE_URL'], '/'));
        array_pop($sectionUrlArray);
        $parentSectionUrl = '/' . implode('/', $sectionUrlArray) . '/';
        $sections[$section['LIST_PAGE_URL']]['CHILDREN'][$parentSectionUrl]['CHILDREN'][$section['SECTION_PAGE_URL']]['NAME'] = $section['NAME'];
    }
}
?>
<div class="tcheckboxes">
    <div class="tcheckboxes__inner js-tcheckboxes">
        <div class="tcheckboxes__scroll js-tcheckboxes-scroll">
            <div class="tcheckboxes__scroll-inner">
                <div class="cols-wrapper cols-wrapper--filter-sections">
                    <div class="cols cols--flex">
                        <?php foreach ($sections['/katalog/tovary-dlya-sada/']['CHILDREN'] as $section): ?>
                            <div class="col">
                                <div class="tcheckbox-wrapper dropdown-selection">
                                    <div class="tcheckbox checkbox-plain-js js-ddbox-input">
                                        <div class="tcheckbox__inner">
                                            <span class="link link--dotted"><?= $section['NAME'] ?></span>
                                        </div>
                                    </div>
                                    <div class="dtcheckbox-trigger js-dtcheckbox-trigger tooltip  tooltip-popover tooltip-popover-right"
                                         data-tooltipster='{"theme": "tooltipster-popover-dtcheckbox"}'>
                                        <svg class="icon">
                                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-menu-list"></use>
                                        </svg>
                                    </div>
                                    <div class="dropdown-selection-content dtcheckbox-dropdown dtcheckbox-dropdown--left js-dtcheckbox-dropdown">
                                        <div class="dtcheckbox-dropdown__scroll">
                                            <?php foreach ($section['CHILDREN'] as $link => $subsection): ?>
                                                <span class="tcheckbox tcheckbox--block checkbox-plain-js js-ddbox-input">
                                                    <a class="link link--dotted"
                                                       href="<?= $link ?>"><?= $subsection['NAME'] ?></a>
                                                </span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>