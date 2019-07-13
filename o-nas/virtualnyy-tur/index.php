<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetPageProperty('hideFooter', "Y");
$APPLICATION->SetPageProperty('hideTopBar', "Y");
$APPLICATION->SetTitle("Виртуальный тур");
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
?><div class="section section--grey section--tour-map section--fullheight text">

        <div class="tour-map js-image-zoom-map-lazy" data-append-to="#tour-map-append" data-start-x="600" data-start-y="600">

            <img class="tour-map__image" data-lazy-src="images/tour-map.jpg" src="/local/templates/ig/img/blank.png" width="2500" height="1348" alt="" />

            <div class="tour-map__loader"></div>

            <nav class="tour-map__legend expand-it-wrapper">
                <div class="tour-map__legend-title expand-it" data-expand-label=".link" data-expand-change-label="Скрыть легенду">
                    <div class="cols cols--auto">
                        <div class="col">
                            <span class="link link--dotted">Показать легенду</span>
                        </div>
                        <div class="col col--fit text-align-right">
                            <svg class="icon icon--chevron-up">
                                <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="tour-map__legend-content expand-it-container">
                    <ol class="tour-map__legend-list expand-it-inner">
                        <li><a href="#tour-tooltip-1-marker" class="link--dotted js-tour-map-tooltip-open">Бонсаи и стриженые формы</a></li>
                        <li><a href="#tour-tooltip-2-marker" class="link--dotted js-tour-map-tooltip-open">Водные растения</a></li>
                        <li><a href="#tour-tooltip-3-marker" class="link--dotted js-tour-map-tooltip-open">Лиственные деревья</a></li>
                        <li><a href="#tour-tooltip-4-marker" class="link--dotted js-tour-map-tooltip-open">Лиственные кустарники</a></li>
                        <li><a href="#tour-tooltip-5-marker" class="link--dotted js-tour-map-tooltip-open">Многолетние растения</a></li>
                        <li><a href="#tour-tooltip-6-marker" class="link--dotted js-tour-map-tooltip-open">Плодовые деревья</a></li>
                        <li><a href="#tour-tooltip-7-marker" class="link--dotted js-tour-map-tooltip-open">Плодовые кустарники</a></li>
                        <li><a href="#tour-tooltip-10-marker" class="link--dotted js-tour-map-tooltip-open">Хвойные деревья</a></li>
                        <li><a href="#tour-tooltip-11-marker" class="link--dotted js-tour-map-tooltip-open">Хвойные кустарники</a></li>
                        <li><a href="#tour-tooltip-12-marker" class="link--dotted js-tour-map-tooltip-open">Эксклюзивный материал</a></li>
                        <li><a href="#tour-tooltip-13-marker" class="link--dotted js-tour-map-tooltip-open">Пруд с пеликанами</a></li>
                    </ol>
                </div>
            </nav>

        </div>

        <div class="hidden">

            <div id="tour-map-append">

                <div class="tm-nameplate pointer-events-none" style="left: 1.4%; top: 18.175%;">
                    <div class="tm-nameplate__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-arrow"></use>
                        </svg>
                    </div>
                    <div class="tm-nameplate__inner">
                        Волоколамск<br>
                        Псков
                    </div>
                </div>

                <div class="tm-nameplate pointer-events-none" style="left: 14.6%; top: 6.3%;">
                    <div class="tm-nameplate__icon">
                        <svg class="icon icon--tour-arrow-pavl">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-arrow"></use>
                        </svg>
                    </div>
                    <div class="tm-nameplate__inner">
                        Княжье озеро<br>
                        Павловская слобода
                    </div>
                </div>

                <div class="tm-nameplate pointer-events-none" style="right: 5.6%; top: 12.24%;">
                    <div class="tm-nameplate__icon">
                        <svg class="icon icon--tour-arrow-mkad">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-arrow"></use>
                        </svg>
                    </div>
                    <div class="tm-nameplate__inner">
                        Москва<br>
                        МКАД, 23 км
                    </div>
                </div>

                <div class="tm-nameplate pointer-events-none" style="left: 52.8%; top: 20.20%;">
                    <div class="tm-nameplate__inner">
                        Новорижское шоссе
                    </div>
                </div>

                <div class="tm-nameplate pointer-events-none" style="left: 1%; top: 43.75%;">
                    <div class="tm-nameplate__icon">
                        <svg class="icon icon--tour-arrow-mmk">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-arrow"></use>
                        </svg>
                    </div>
                    <div class="tm-nameplate__inner">
                        ММК<br>
                        Малое кольцо
                    </div>
                </div>

                <div class="tm-nameplate pointer-events-none" style="left: 20.00%; top: 97.55%;">
                    <div class="tm-nameplate__icon">
                        <svg class="icon icon--tour-arrow-chest">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-arrow"></use>
                        </svg>
                    </div>
                    <div class="tm-nameplate__inner">
                        Чесноково
                    </div>
                </div>

                <div class="tm-nameplate tm-nameplate--small text-align-center pointer-events-none" style="left: 28.6%; top: 52.67%;">
                    <div class="tm-nameplate__inner">
                        WC
                    </div>
                </div>

                <!-- Пример со всплывающей подсказкой -->
                <!--
                <div class="tm-nameplate tm-nameplate--small text-align-center cursor-default tooltip" data-tooltipster='{"theme": "tooltipster-tour-map"}' data-title="Это наш туалет" style="left: 28.6%; top: 52.67%;">
                    <div class="tm-nameplate__inner">
                        WC
                    </div>
                </div>
                -->

                <div class="tm-nameplate tm-nameplate--small text-align-center tm-nameplate--large pointer-events-none" style="left: 15.4%; top: 68.25%;">
                    <div class="tm-nameplate__inner">
                        P1
                    </div>
                </div>

                <div class="tm-nameplate tm-nameplate--small text-align-center tm-nameplate--large pointer-events-none" style="left: 16.4%; top: 43%;">
                    <div class="tm-nameplate__inner">
                        P2
                    </div>
                </div>

                <div class="tm-nameplate tm-nameplate--small text-align-center tm-nameplate--large pointer-events-none" style="left: 25.2%; top: 31.6%;">
                    <div class="tm-nameplate__inner">
                        P3
                    </div>
                </div>

                <div class="tm-nameplate tm-nameplate--small text-align-center tm-nameplate--large pointer-events-none" style="left: 20%; top: 84.2%;">
                    <div class="tm-nameplate__inner">
                        КПП
                    </div>
                </div>


                <div class="tm-marker w-hover" style="left: 26.6%; top: 41.9%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": ["top", "bottom"]}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-hall-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-hall"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Garden Hall
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-hall-tooltip">
                            <h5>Garden Hall</h5>
                            <p>Приходите к нам отметить ваше торжество или присоединяйтесь к нашим мероприятиям.</p>
                            <!--<p><img data-lazy-src="/local/templates/ig/pic/VT_garden_hall.jpg" src="/local/templates/ig/pic/VT_garden_hall.jpg" alt="Garden Hall"></p>-->
                            <p><a href="/o-nas/dosug/garden-hall/" class="link--bordered-pseudo">Подробнее об Event-площадке</a></p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker w-hover" style="left: 22%; top: 51.2%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-garden-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-garden"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Сад “The Imperial<br>
                                Garden Revive”
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-garden-tooltip">
                            <h5>Сад “The Imperial Garden Revive”</h5>
                            <p>Авторский сад, получивший серебряную медаль в самом престижном в мире фестивале ландшафтного дизайна RHS Chelsea Flower Show в Великобритании (номинация Fresh Garden). Ландшафтный дизайнер Татьяна Гольцова.</p>
                            <p>Часы работы: с 8:00 до 19:00. Ежедневно. Вход свободный.</p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker w-hover" style="left: 23.76%; top: 61.20%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-park-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-park"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Парк "Revive"
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-park-tooltip">
                            <h5>Парк "Revive"</h5>
                            <p>Пришло время возрождать отношения между странами, людьми, природой.</p>
                            <p>Возрождать истинные ценности, мир и гармонию, которых так не хватает. Мы можем сделать это все вместе.</p>
                            <!--<p><img data-lazy-src="/local/templates/ig/pic/VT_park.jpg" src="/local/templates/ig/pic/VT_park.jpg" alt="Парк Revive"></p>-->
                            <p><a href="/o-nas/dosug/park/" class="link--bordered-pseudo">Узнать больше про парк "Revive"</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 14.4%; top: 50.44%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-office-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-office"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Офис
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-office-tooltip">
                            <h5>Офисное здание АО «Ривьера».</h5>
                            <p><img data-lazy-src="/local/templates/ig/pic/VT_ofis.jpg" src="/local/templates/ig/pic/VT_ofis.jpg" alt="Офисное здание АО «Ривьера»"></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 24.8%; top: 93.1%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-zoo-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-zoo"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Зоопарк
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-zoo-tooltip">
                            <h5>Зоопарк</h5>
                            <p>Смешные альпаки, милые еноты, важные павлины, весёлые кролики и их друзья ждут вас в зоопарке Imperial Garden.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/dosug/zoopark/" class="link--bordered-pseudo">Подробнее о зоопарке</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 29.2%; top: 85.6%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-shop-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-shop"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Офис розничных<br>
                                продаж
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-shop-tooltip">
                            <h5>Офис розничных продаж</h5>
                            <p>К вашим услугам приветливые менеджеры и комфортные гольф-кары. Мы с удовольствием проведём экскурсию по садовому центру, поможем подобрать растения под ландшафтный проект или предложим собственные идеи по созданию сада вашей мечты.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/komanda/#prodazhi" class="link--bordered-pseudo">Наша команда</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 32.6%; top: 81.3%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-furniture-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-furniture"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Садовая мебель
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-furniture-tooltip">
                            <h5>Садовая мебель</h5>
                            <p>Компания «Кватросис» предлагает комплексные решения по производству и продаже садовой мебели для открытых пространств загородных участков. Совокупность качественной продукции, уникальных идей, современных технологий и профессионализма позволяют нам создавать неповторимую атмосферу уюта и комфорта в открытых интерьерах.</p>
                            <p>Часы работы: с 10:00 до 19:00. Ежедневно.</p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 37%; top: 82.34%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-stone-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-stone"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Stone Shop
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-stone-tooltip">
                            <h5>Stone Shop</h5>
                            <p>Компания UNIMART предлагает профессиональную помощь в решении вопросов строительства и оформления интерьера.</p>
                            <p>В show-room UNIMART на территории Imperial Garden можно увидеть образцы продукции фасадных и кровельных материалов, материалы для благоустройства, общестроительные и отделочные материалы, интерьерные и фасадные покрытия, материалы и оборудование для инженерных сетей. Всё собрано в одном месте, поэтому вам больше не нужно делать заказ по каталогам, сомневаться в правильности выбранного цвета или фактуры товара.</p>
                            <p>Часы работы: с 10:00 до 19:00. Ежедневно.</p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 43%; top: 85.31%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-light-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-light"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Экспозиция<br>
                                уличных фонарей
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-light-tooltip">
                            <h5>Showroom уличных светильников Robers (Германия)</h5>
                            <p>Дизайнерские светильники ручной работы от немецкой фабрики Robers.</p>
                            <p>Мастера Robers используют только высококачественные материалы, тщательным образом отобранные для каждого отдельного элемента будущего произведения искусства: алюминий и железо, медь и сталь, особо прочное стекло и многое-многое другое.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 32%; top: 93.1%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-garden-item-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-garden-item"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Товары для сада
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-garden-item-tooltip">
                            <h5>Товары для сада</h5>
                            <p>В Garden Shop есть всё для вдохновения и ухода за садом: последние коллекции одежды для работы в саду, садовый инвентарь, оборудование для полива и уборки, удобрения, грунты, травосмеси, средства по защите растений, цветочные горшки, кормушки для уличных птиц и многое другое, что не оставит вас равнодушными.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                            <p><a href="/katalog/tovary-dlya-sada/" class="link--bordered-pseudo">Перейти в каталог</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 36.4%; top: 93.1%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-art-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-art"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Арт-галерея
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-art-tooltip">
                            <h5>Арт-галерея</h5>
                            <p>Единственная в России галерея, занимающаяся и всесторонне представляющая эксклюзивную ландшафтную скульптуру – Art Gallery.</p>
                            <p>Галерея ищет по всему миру и привозит в Россию уникальные проекты, фрагменты и произведения из экзотических пород дерева и других природных материалов.</p>
                            <p>Обязательно заходите насладиться прекрасным.</p>
                            <p>Часы работы: с 10:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/dosug/art-gallery/" class="link--bordered-pseudo">Подробнее о галерее</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--light w-hover" style="left: 40.4%; top: 93.1%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-opt-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-opt"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Офис оптовых<br>
                                продаж
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-opt-tooltip">
                            <h5>Офис оптовых
                                продаж</h5>
                            <p>Здесь мы помогаем ландшафтным дизайнерам воплощать в жизнь даже самые смелые проекты. Персонализированный подход, огромный ассортимент и накопительная система скидок – вступайте в Garden Family Club.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/komanda/#optovye-prodazhi" class="link--bordered-pseudo">Наша команда</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--dark w-hover" style="left: 35.2%; top: 28.56%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-choco-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-choco"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Шоколадная фабрика
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-choco-tooltip">
                            <h5>Шоколадная фабрика</h5>
                            <p>Вкуснейший шоколад и десерты собственного производства.<br><br>
                                Часы работы: с 10:00 до 18:00. Ежедневно.<br>
                                Принимаются к оплате банковские карты.</p>
                            <p><a href="/o-nas/dosug/shokoladnaya-fabrika/" class="link--bordered-pseudo">О фабрике</a></p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--dark w-hover" style="left: 31.4%; top: 34.87%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-zoo-contact-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-zoo"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Зоопарк
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-zoo-contact-tooltip">
                            <h5>Зоопарк</h5>
                            <p>Белые павлины и декоративный красавицы курочки.<br><br>Часы работы: с 8:00 до 19:00. Ежедневно.</p>
                        </div>
                    </div>
                </div>

                <div class="tm-marker tm-marker--dark w-hover" style="left: 24.2%; top: 73.88%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-cafe-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-cafe"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Летнее кафе
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-cafe-tooltip">
                            <h5>Летнее кафе «Cofee Piu»</h5>
                            <p>Авторская кухня, освежающие коктейли, детское меню. Просторная летняя веранда. Бесплатная анимация и мастер-классы для детей.
                                <br>
                                Часы работы: с 10:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/dosug/kafe-coffee-piu/" class="link--bordered-pseudo">Подробнее о кафе «Cofee Piu»</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--dark w-hover" style="left: 23.8%; top: 81.23%;">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-play-tooltip"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-play"></use>
                        </svg>
                    </div>

                    <div class="tooltipster-base tooltipster-sidetip tooltipster-tour-map tooltipster-default tooltipster-top tooltipster-fade tooltipster-show nowrap">
                        <div class="tooltipster-box">
                            <div class="tooltipster-content">
                                Детская площадка
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="tour-play-tooltip">
                            <h5>Детская площадка</h5>
                            <p>Чистейший белый песок, весёлые горки и качели, детская площадка, батуты. Бесплатная анимация, аквагрим, мыльные пузыри.</p>
                            <p>Часы работы: с 08:00 до 19:00. Ежедневно.</p>
                            <p><a href="/o-nas/dosug/detskaja-ploshhadka/" class="link--bordered-pseudo">Подробнее о площадке</a></p>
                        </div>
                    </div>

                </div>



                <div class="tm-marker tm-marker--green w-hover" style="left: 29.04%; top: 65.58%;" id="tour-tooltip-1-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        1
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-1">
                            <h5>Бонсаи и топиары</h5>
                            <p>Растения необычной формы, фигурно подстриженные деревья и кустарники используются для создания ландшафтных групп.</p>
                            <p><a href="/katalog/rasteniya/?filterAlias=c045e48edbe1ce18d6522fec5ca54de0" class="link--bordered-pseudo">Все бонсаи в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 86.56%; top: 84.64%;" id="tour-tooltip-3-1-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-1">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 79.84%; top: 63.95%;" id="tour-tooltip-3-2-marker-DEL">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-2"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-2">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 93.64%; top: 52.74%;" id="tour-tooltip-3-3-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-3"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-3">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 49.12%; top: 83%;" id="tour-tooltip-3-4-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-4"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-4">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 69.80%; top: 49.55%;" id="tour-tooltip-3-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-5"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-5">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 33.68%; top: 49.04%;" id="tour-tooltip-4-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-4-2"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        4
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-4-2">
                            <h5>Лиственные кустарники</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=47c641f2855b3a780e1398f207223da1" class="link--bordered-pseudo">Лиственные кустарники в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 47.12%; top: 48.96%;" id="tour-tooltip-4-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-4-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        4
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-4-1">
                            <h5>Лиственные кустарники</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=47c641f2855b3a780e1398f207223da1" class="link--bordered-pseudo">Лиственные кустарники в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 75.28%; top: 55.71%;" id="tour-tooltip-10-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-10-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-10-1">
                            <h5>Хвойные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=38a7e4519a27540d8765d4f2aa17a085" class="link--bordered-pseudo">Хвойные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 55.04%; top: 49.33%;" id="tour-tooltip-10-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-10-2"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-10-2">
                            <h5>Хвойные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=38a7e4519a27540d8765d4f2aa17a085" class="link--bordered-pseudo">Хвойные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 40.52%; top: 48.81%;" id="tour-tooltip-11-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-11-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        11
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-11-1">
                            <h5>Хвойные кустарники</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=bdb56b33db3f5e811329c1853cf04742" class="link--bordered-pseudo">Хвойные кустарники в каталоге</a></p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 82.88%; top: 75.37%;" id="tour-tooltip-6-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-6-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        6
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-6-1">
                            <h5>Плодовые деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=32c2315a0790ddbf23edc1b00fb7d5b8" class="link--bordered-pseudo">Плодовые в каталоге</a></p>
                        </div>
                    </div>

                </div>



                <div class="tm-marker tm-marker--green w-hover" style="left: 33.12%; top: 62.69%;" id="tour-tooltip-7-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-7-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        7
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-7-1">
                            <h5>Плодовые кустарники</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=32c2315a0790ddbf23edc1b00fb7d5b8" class="link--bordered-pseudo">Плодовые в каталоге</a></p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 61%; top: 49%;" id="tour-tooltip-3-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-3-6"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        3
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-3-6">
                            <h5>Лиственные деревья</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=7cf5bbfe7630b4199b821d103d39e317" class="link--bordered-pseudo">Лиственные деревья в каталоге</a></p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 55.40%; top: 83%;" id="tour-tooltip-12-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-12-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-12-1">
                            <h5>Эксклюзивный материал</h5>
                            <p>Эксклюзивный материал</p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 61.44%; top: 83%;" id="tour-tooltip-12-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-12-2"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-12-2">
                            <h5>Эксклюзивный материал</h5>
                            <p>Эксклюзивный материал</p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 67.72%; top: 83%;" id="tour-tooltip-12-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-12-3"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-12-3">
                            <h5>Эксклюзивный материал</h5>
                            <p>Эксклюзивный материал</p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 73.96%; top: 83%;" id="tour-tooltip-12-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-12-4"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        10
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-12-4">
                            <h5>Эксклюзивный материал</h5>
                            <p>Эксклюзивный материал</p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 80%; top: 91.91%;" id="tour-tooltip-13-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-13-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        11
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-13-1">
                            <h5>Пруд с пеликанами</h5>
                            <p>В конце центральной аллеи находится секретный пруд Imperial Garden, в котором живут красавцы белые пеликаны, уроженцы Северной Америки.</p>
                        </div>
                    </div>

                </div>

                <div class="tm-marker tm-marker--green w-hover" style="left: 31.04%; top: 74.11%;" id="tour-tooltip-5-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-5-1"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        5
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-5-1">
                            <h5>Многолетние растения</h5>
                            <p><a href="/katalog/rasteniya/?filterAlias=1f0255c5dc2e65091fc8c97ec4ce27bf" class="link--bordered-pseudo">Многолетние растения в каталоге</a></p>
                        </div>
                    </div>

                </div>


                <div class="tm-marker tm-marker--green w-hover" style="left: 62.40%; top: 91.91%;" id="tour-tooltip-2-marker">
                    <div class="tm-marker__shape tooltip tooltip-popover cursor-pointer" data-tooltipster='{"theme": "tooltipster-tour-map-popover", "side": "top"}' data-tooltip-btn-close="true" data-tooltip-selector="#tour-tooltip-2"></div>
                    <div class="tm-marker__bg">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__shadow">
                        <svg class="icon">
                            <use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tour-marker"></use>
                        </svg>
                    </div>
                    <div class="tm-marker__icon">
                        2
                    </div>

                    <div class="hidden">
                        <div id="tour-tooltip-2">
                            <h5>Водные растения</h5>
                            <p>Добро пожаловать в мир водных растений: прекрасных кувшинок и лотосов, рогульника и сальвинии, и многих других очаровательных представителей водной флоры.</p>
                            <p><a href="/katalog/rasteniya/?filterAlias=2430ad5932c084ece29f3239944eccc6" class="link--bordered-pseudo">Водные растения в каталоге</a></p>
                        </div>
                    </div>

                </div>

            </div>

        </div>


    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>