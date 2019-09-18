<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Config\Option;
use ig\CHelper;
use ig\CSeo;

if ($APPLICATION->GetProperty('hideFooter') !== 'Y') : ?>
    <footer class="footer">
        <div class="container">
            <div class="fsocial">
                <div class="fsocial__inner">
                    <div class="fsocial__item fsocial__item--label">Поделиться</div>
                    <script type='text/javascript'
                            src='//platform-api.sharethis.com/js/sharethis.js#property=5ca8e8a52c4f3b001126efbf&product=inline-share-buttons'
                            async='async'></script>
                    <style>
                        #st_gdpr_iframe {
                            bottom: 0 !important;
                        }
                    </style>
                    <div class="fsocial__item fsocial__item--list sharethis-inline-share-buttons"></div>
                </div>
            </div>
            <div class="footer__grid-wrapper">
                <div class="footer__grid">
                    <div class="footer__col footer__col--logo">
                        <a class="flogo" href="/">
                            <svg class="icon icon--logo">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-logo"></use>
                            </svg>
                        </a>
                    </div>
                    <?php
                    $APPLICATION->IncludeComponent(
                        'bitrix:menu',
                        'footer',
                        [
                            'ROOT_MENU_TYPE' => 'bottom',
                            'MAX_LEVEL' => '1',
                            'CHILD_MENU_TYPE' => 'left',
                            'USE_EXT' => 'N',
                            'MENU_CACHE_TYPE' => 'A',
                            'MENU_CACHE_TIME' => '3600',
                            'MENU_CACHE_USE_GROUPS' => 'N',
                            'MENU_CACHE_GET_VARS' => []
                        ]
                    );
                    ?>

                    <div class="footer__col footer__col--social">
                        <span>Мы в соцсетях:</span>
                        <div class="csocials">
                            <!--noindex-->
                            <a class="csocial csocial--facebook"
                               href="<?= Option::get('grain.customsettings', 'facebook_link') ?>">
                                <svg class="icon icon--facebook">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-facebook"></use>
                                </svg>
                            </a>
                            <!--/noindex-->
                            <!--noindex-->
                            <a class="csocial csocial--instagram"
                               href="<?= Option::get('grain.customsettings', 'instagram_link') ?>">
                                <svg class="icon icon--instagram">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-instagram"></use>
                                </svg>
                            </a>
                            <!--/noindex-->
                            <!--noindex-->
                            <a class="csocial csocial--youtube"
                               href="<?= Option::get('grain.customsettings', 'youtube_link') ?>">
                                <svg class="icon icon--youtube">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-youtube"></use>
                                </svg>
                            </a>
                            <!--/noindex-->
                        </div>
                    </div>

                    <div class="footer__col footer__col--copyright">
                        &copy;
                        <?php
                        $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'AREA_FILE_SUFFIX' => 'inc',
                                'EDIT_TEMPLATE' => '',
                                'PATH' => '/local/inc_area/copyright.php'
                            ]
                        ); ?>
                        <?= date('Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>
<?php

?>

</div>
<div class="clear"></div>
<script>
    var obDynamicData = <?=json_encode(CHelper::getDynamicData())?>;
</script>

<?php $APPLICATION->ShowHeadScripts() ?>
</body>
</html>