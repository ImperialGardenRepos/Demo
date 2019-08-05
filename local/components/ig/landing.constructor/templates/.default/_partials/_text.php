<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var $block
 */
$customCssClass = md5(serialize($block));
?>
<section class="text">
    <div class="text__content text__content--image-<?= $block['IMAGE_FLOW']['VALUE_XML_ID'] ?>">
        <?php if ($block['IMAGE_WIDTH'] !== ''): ?>
            <style>
                @media all and (min-width: 800px) {
                    .text__img--<?=$customCssClass?> {
                        max-width: <?=$block['IMAGE_WIDTH']?>% !important;
                    }

                    .text__text--<?=$customCssClass?> {
                        max-width: <?=95 - $block['IMAGE_WIDTH']?>% !important;
                    }
                }
            </style>
        <?php endif; ?>
        <div class="text__text text__text--<?= $customCssClass ?>">
            <?php
            if ($block['TEXT']['TYPE'] === 'HTML') {
                echo html_entity_decode($block['TEXT']['TEXT']);
            } else {
                echo $block['TEXT']['TEXT'];
            }
            ?>
        </div>
        <?php if ($block['IMAGE']): ?>
            <div class="text__img text__img--<?= $customCssClass ?>">
                <!--ToDO::get image description to be used with alt-->
                <img src="<?= CFile::GetPath($block['IMAGE']) ?>">
            </div>
        <?php endif; ?>
    </div>
</section>
