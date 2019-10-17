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
<?php if ($arResult['ITEMS'] !== []): ?>
    <div class="section section--filter bg-pattern bg-pattern--grey-leaves">
        <div class="container">
            <div class="ltabs">
                <div class="ltabs__list">
                    <?php include '_tabs.php' ?>
                </div>
            </div>
            <div class="tab-panes">
                <div class="filter-top">
                    <div class="js-filter-stabs-mobile-place">
                        <form class="tab-pane filter js-filter" action="<?= $arResult['BASE_URL'] ?>"
                              method="get">
                            <?php /** ToDo:: at least rename, code style differs too significantly */ ?>
                            <input type="hidden" name="frmCatalogFilterSent" value="Y">
                            <input type="hidden" name="IS_AJAX" value="Y">
                            <div class="js-filter-inner">
                                <div class="filter__main">
                                    <div class="filter__section filter__popup js-filter-section-wrapper expand-it-wrapper">
                                        <div class="filter__content">
                                            <?php include '_header.php' ?>
                                        </div>
                                        <div class="filter__section-header">
                                            <?php include '_controls.php' ?>
                                        </div>
                                        <div class="filter__section-content expand-it-container js-filter-section<?= ($arParams['EXPAND_FILTER'] === 'Y' ? ' overflow-visible active' : '') ?>"
                                             id="filter-main-list">
                                            <?php include '_content.php' ?>
                                        </div>
                                        <div class="filter__footer">
                                            <?php include '_footer.php' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>