<?php
/**
 * @var $block
 */
//ToDo:: die without prolog
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
