<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var $block
 */

?>
<section class="text">
    <div class="text__content text__content--image-<?= $block['IMAGE_FLOW']['VALUE_XML_ID'] ?>">
        <div class="text__text">
            <?php
            if ($block['TEXT']['TYPE'] === 'HTML') {
                echo html_entity_decode($block['TEXT']['TEXT']);
            } else {
                echo $block['TEXT']['TEXT'];
            }
            ?>
        </div>
        <div class="text__img">
            <!--ToDO::get image description to be used with alt-->
            <img src="<?= CFile::GetPath($block['IMAGE']) ?>">
        </div>
    </div>
</section>
