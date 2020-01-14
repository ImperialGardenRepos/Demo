<div class="tabs-vert-fixed scroll-activate" data-scroll-activate-trigger="#vertical-tabs-trigger"
     data-scroll-activate-offset-element=".header" data-scroll-activate-trigger-hook="onLeave"
     data-scroll-activate-reverse="true">
    <nav class="tabs tabs--vert tabs--active-allowed" data-goto-hash-change="true">
        <div class="tabs__inner js-tabs-fixed-center">
            <div class="tabs__scroll js-tabs-fixed-center-scroll">
                <div class="tabs__scroll-inner">
                    <ul class="tabs__list">
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--block js-goto" href="#catalog">
                                <svg class="icon">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-book-outline"></use>
                                </svg>
                                <span class="link link--ib link--dotted">Каталог</span>
                            </a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--block js-goto" href="#services">
                                <svg class="icon">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-gardener-outline"></use>
                                </svg>
                                <span class="link link--ib link--dotted">Услуги</span>
                            </a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--block js-goto" href="#objects">
                                <svg class="icon">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-maple-leaf"></use>
                                </svg>
                                <span class="link link--ib link--dotted">Объекты</span>
                            </a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--block js-goto" href="#entertainment">
                                <svg class="icon">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-drink-apple"></use>
                                </svg>
                                <span class="link link--ib link--dotted">Досуг</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
<div id="vertical-tabs-trigger" class="vertical-tabs-trigger"></div>