<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var $block
 */
?>
<section class="banner" style="background: url(<?=CFile::GetPath($block['IMAGE'])?>)">
    <div class="banner__content">
        <div class="banner__heading">
            <?=$block['HEADING']?>
        </div>
        <div class="banner__text">
            <?php
                if($block['TEXT']['TYPE'] === 'HTML') {
                    echo html_entity_decode($block['TEXT']['TEXT']);
                } else {
                    echo $block['TEXT']['TEXT'];
                }
            ?>
        </div>
    </div>
</section>
