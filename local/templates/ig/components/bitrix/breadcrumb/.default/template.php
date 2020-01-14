<?php

use ig\Seo\PageSchema;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

PageSchema::setBreadcrumbsSchema($arResult, $this);
//delayed function must return a string
if (empty($arResult)) {
    return '';
}

$strReturn = '';

if ($APPLICATION->GetProperty('NOT_SHOW_NAV_CHAIN') !== 'Y') {
    $itemSize = count($arResult);
    $strReturn = '
<div class="section js-breadcrumb-to-replace section--breadcrumb mobile-hide">
	<div class="container">
		<div class="breadcrumb">';

    for ($index = 0; $index < $itemSize; $index++) {
        $title = htmlspecialcharsex($arResult[$index]['TITLE']);
        $arrow = ($index > 0 ? '
				<svg class="icon icon--long-arrow">
                    <use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
                </svg>' : '');

        if ((string)$arResult[$index]['LINK'] !== '') {
            $strReturn .= $arrow . '<a title="' . $title . '" href="' . $arResult[$index]['LINK'] . '">' . $title . '</a>';
        } else {
            $strReturn .= $arrow . $title;
        }
    }

    $strReturn .= '
		</div>
    </div>
</div>';
}
return $strReturn;
