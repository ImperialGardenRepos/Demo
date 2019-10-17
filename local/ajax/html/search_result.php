<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="ddbox__dropdown-container">
    <?php if ($searchResult !== []) : ?>
        <div class="ddbox__dropdown-inner js-ddbox-scrollbar">
            <div class="checkboxes">
                <div class="checkboxes__inner">
                    <?php foreach ($searchResult as $item): ?>
                        <div class="plant-checkbox plant-checkbox--level0 checkbox checkbox--block">
                            <div class="plant-checkbox__title">
                                <a href="<?= $item['p_link'] ?>" target="_blank" class="link--bordered">
                                    <?= $item['p_full_name'] ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="ddbox__dropdown-scroll-gradient"></div>
    <?php else: ?>
        <div class="ddbox__dropdown-inner js-ddbox-scrollbar">
            <p>Ничего не найдено. Попробуйте расширить запрос.</p>
        </div>
    <?php endif; ?>
</div>