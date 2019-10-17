<?php

use ig\CImageResize;
use ig\Events\Admin;
use ig\Events\IBlock;
use ig\Events\Sale;
use ig\Events\Seo;
use ig\Events\System;
use ig\sphinx\CatalogGardenOffers;
use ig\sphinx\CatalogOffers;

AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', [IBlock::class, 'OnBeforeIBlockElementAddHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', [IBlock::class, 'OnBeforeIBlockElementAddOrUpdateHandler'], 1000);
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [IBlock::class, 'OnBeforeIBlockElementUpdateHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [IBlock::class, 'OnBeforeIBlockElementAddOrUpdateHandler'], 1000);
AddEventHandler('iblock', 'OnBeforeIBlockElementDelete', [IBlock::class, 'onBeforeIBlockElementDeleteHandler']);
AddEventHandler('main', 'OnAdminTabControlBegin', [IBlock::class, 'RemoveYandexDirectTab']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionAdd', [IBlock::class, 'onBeforeIBlockSectionAddOrUpdateHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', [IBlock::class, 'onBeforeIBlockSectionUpdateHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', [IBlock::class, 'onBeforeIBlockSectionAddOrUpdateHandler'], 1000);
AddEventHandler('iblock', 'OnAfterIBlockElementAdd', [IBlock::class, 'OnAfterIBlockElementAddHandler']);
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', [IBlock::class, 'OnAfterIBlockElementUpdateHandler']);

AddEventHandler('main', 'OnBuildGlobalMenu', [Admin::class, 'OnBuildGlobalMenuAddon']);
AddEventHandler('main', 'OnAdminListDisplay', [Admin::class, 'onAdminListDisplay']);
AddEventHandler('main', 'OnBeforeProlog', [Admin::class, 'processGroupActions']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', [Admin::class, 'checkRights']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionDelete', [Admin::class, 'checkRights']);
AddEventHandler('main', 'OnProlog', [Admin::class, 'onPrologHandler'], 50);
AddEventHandler('main', 'OnAfterUserLogin', [Admin::class, 'OnAfterUserLoginHandler']);
AddEventHandler('main', 'OnEndBufferContent', [Admin::class, 'OnEndBufferContentHandler']);

//ToDo:: sphinx events normally

CatalogOffers::initEvents();
CatalogGardenOffers::initEvents();
//ToDo:: resize events normally
CImageResize::initEvents();


AddEventHandler('sale', 'OnOrderNewSendEmail', [Sale::class, 'OnOrderNewSendEmailHandler']);
AddEventHandler('main', 'OnProlog', [System::class, 'onPrologHandler'], 50);

AddEventHandler('main', 'OnEpilog', [Seo::class, 'setFinalMeta']);