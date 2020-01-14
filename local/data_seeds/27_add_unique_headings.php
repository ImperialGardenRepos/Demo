<?php

use ig\Datasource\Highload\VirtualPageTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;

if (!$USER->IsAdmin()) {
    exit();
}

$headings = [
    '/katalog/rasteniya/air/obyknovennyj/obyknovennyj/' => 'Аир обыкновенный - Acorus calamus',
    '/katalog/rasteniya/aktinidiya/arguta-f/arguta-/' => 'Актинидия аргута ♀ - Actinidia arguta',
    '/katalog/rasteniya/aktinidiya/dzhiralda/dzhiralda/' => 'Актинидия Джиральда - Actinidia giraldii',
    '/katalog/rasteniya/astrantsiya/krupnaya/krupnaya/' => 'Астранция крупная - Astrantia major',
    '/katalog/rasteniya/barvinok/malyj/malyj/' => 'Барвинок малый - Vinca minor',
    '/katalog/rasteniya/beresklet/evropejskij/evropejskij/' => 'Бересклет европейский - Euonymus europaeus',
    '/katalog/rasteniya/beresklet/krylatyj/krylatyj/' => 'Бересклет крылатый - Euonymus alatus',
    '/katalog/rasteniya/beresklet/krylatyy-bonsay/krylatyy-bonsay/' => 'Бересклет крылатый (Бонсай) - Euonymus krylatyy',
    '/katalog/rasteniya/beresklet/krylatyy-shar/krylatyy-shar/' => 'Бересклет крылатый (Шар) - Euonymus krylatyy',
    '/katalog/rasteniya/beresklet/krylatyy-zont/krylatyy-zont/' => 'Бересклет крылатый (Зонт) - Euonymus krylatyy',
    '/katalog/rasteniya/bereza/povislaya/povislaya/' => 'Береза повислая - Betula pendula',
    '/katalog/rasteniya/bereza/povislaya-arka/povislaya-arka/' => 'Береза повислая (Арка) - Betula povislaya',
    '/katalog/rasteniya/boyaryshnik/arnolda-shar/arnolda-shar/' => 'Боярышник Арнольда (Шар) - Crataegus arnolda',
    '/katalog/rasteniya/boyaryshnik/arnolda-zont/arnolda-zont/' => 'Боярышник Арнольда (Зонт) - Crataegus arnolda',
    '/katalog/rasteniya/boyaryshnik/krovavo-krasnyy-element-zhivoy-izgorodi/krovavo-krasnyy-element-zhivoy-izgorodi/' => 'Боярышник кроваво-красный (Элемент живой изгороди) - Crataegus krovavo-krasnyy',
    '/katalog/rasteniya/boyaryshnik/krovavo-krasnyy-shar/krovavo-krasnyy-shar/' => 'Боярышник кроваво-красный (Шар) - Crataegus krovavo-krasnyy',
    '/katalog/rasteniya/boyaryshnik/krovavo-krasnyy-zont/krovavo-krasnyy-zont/' => 'Боярышник кроваво-красный (Зонт) - Crataegus krovavo-krasnyy',
    '/katalog/rasteniya/boyaryshnik/myagkovatyy-zont/myagkovatyy-zont/' => 'Боярышник мягковатый (Зонт) - Crataegus',
    '/katalog/rasteniya/boyaryshnik/obyknovennyj/obyknovennyj/' => 'Боярышник обыкновенный - Crataegus laevigata',
    '/katalog/rasteniya/boyaryshnik/odnopestichnyj/odnopestichnyj/' => 'Боярышник однопестичный - Crataegus monogyna',
    '/katalog/rasteniya/boyaryshnik/slivolistnyj/slivolistnyj/' => 'Боярышник сливолистный - Crataegus x persimilis',
    '/katalog/rasteniya/boyaryshnik/slivolistnyy-element-zhivoy-izgorodi/slivolistnyy-element-zhivoy-izgorodi/' => 'Боярышник сливолистный (Элемент живой изгороди) - Crataegus slivolistnyy',
    '/katalog/rasteniya/boyaryshnik/slivolistnyy-zont/slivolistnyy-zont/' => 'Боярышник сливолистный (Зонт) - Crataegus slivolistnyy',
    '/katalog/rasteniya/brunnera/krupnolistnaya/krupnolistnaya/' => 'Бруннера крупнолистная - Brunnera macrophylla',
    '/katalog/rasteniya/buzina/chernaya/chernaya/' => 'Бузина черная - Sambucus nigra',
    '/katalog/rasteniya/cheremukha/maaka/maaka/' => 'Черемуха Маака - Padus maackii',
    '/katalog/rasteniya/cheremukha/obyknovennaya/obyknovennaya/' => 'Черемуха обыкновенная - Padus racemosa',
    '/katalog/rasteniya/cheremukha/pozdnyaya/pozdnyaya/' => 'Черемуха поздняя - Padus serotina',
    '/katalog/rasteniya/cheremukha/virginskaya/virginskaya/' => 'Черемуха виргинская - Padus virginiana',
    '/katalog/rasteniya/chubushnik/venechnyj/venechnyj/' => 'Чубушник венечный - Philadelphus coronarius',
    '/katalog/rasteniya/deren/belyj/belyj/' => 'Дерен белый - Cornus alba',
    '/katalog/rasteniya/deren/krovavo-krasnyj/krovavo-krasnyj/' => 'Дерен кроваво-красный - Cornus sanguinea',
    '/katalog/rasteniya/devichij-vinograd/pyatilistochkovyy/pyatilistochkovyy/' => 'Девичий виноград пятилисточковый - Parthenocissus quinquefolia',
    '/katalog/rasteniya/dub/chereshchatyj/chereshchatyj/' => 'Дуб черешчатый - Quercus robur',
    '/katalog/rasteniya/dub/krasnyj/krasnyj/' => 'Дуб красный - Quercus rubra',
    '/katalog/rasteniya/el/jengelmana/jengelmana/' => 'Ель Энгельмана - Picea engelmannii',
    '/katalog/rasteniya/el/kolyuchaya/kolyuchaya/' => 'Ель колючая - Picea pungens',
    '/katalog/rasteniya/el/obyknovennaya/obyknovennaya/' => 'Ель обыкновенная - Picea abies',
    '/katalog/rasteniya/el/obyknovennaya-element-zhivoy-izgorodi/obyknovennaya-element-zhivoy-izgorodi/' => 'Ель обыкновенная (Элемент живой изгороди) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-grin-art/obyknovennaya-grin-art/' => 'Ель обыкновенная (Грин-арт) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-konus/obyknovennaya-konus/' => 'Ель обыкновенная (Конус) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-kub/obyknovennaya-kub/' => 'Ель обыкновенная (Куб) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-shar/obyknovennaya-shar/' => 'Ель обыкновенная (Шар) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-shar-na-shtambe/obyknovennaya-shar-na-shtambe/' => 'Ель обыкновенная (Шар на штамбе) - Picea obyknovennaya',
    '/katalog/rasteniya/el/obyknovennaya-yarusy/obyknovennaya-yarusy/' => 'Ель обыкновенная (Ярусы) - Picea obyknovennaya',
    '/katalog/rasteniya/el/serbskaya/serbskaya/' => 'Ель сербская - Picea omorika',
    '/katalog/rasteniya/el/sizaya/sizaya/' => 'Ель сизая - Picea glauca',
    '/katalog/rasteniya/gejkhera/amerikanskaya/amerikanskaya/' => 'Гейхера американская - Heuchera americana',
    '/katalog/rasteniya/gejkhera/krovavo-krasnaya/krovavo-krasnaya/' => 'Гейхера кроваво-красная - Heuchera sanguinea',
    '/katalog/rasteniya/geran/velikolepnaya/velikolepnaya/' => 'Герань великолепная - Geranium x magnificum',
    '/katalog/rasteniya/golubika/vysokoroslaya/vysokoroslaya/' => 'Голубика высокорослая - Vaccinium corymbosum',
    '/katalog/rasteniya/gortenziya/metelchataya/metelchataya/' => 'Гортензия метельчатая - Hydrangea paniculata',
    '/katalog/rasteniya/grusha/ussurijskaya/ussurijskaya/' => 'Груша уссурийская - Pyrus ussuriensis',
    '/katalog/rasteniya/irga/lamarka-shar/lamarka-shar/' => 'Ирга Ламарка (Шар) - Amelanchier lamarka',
    '/katalog/rasteniya/irga/lamarka-shpalera/lamarka-shpalera/' => 'Ирга Ламарка (Шпалера) - Amelanchier lamarka',
    '/katalog/rasteniya/irga/lamarka-zont/lamarka-zont/' => 'Ирга Ламарка (Зонт) - Amelanchier lamarka',
    '/katalog/rasteniya/iva/belaya/belaya/' => 'Ива белая - Salix alba',
    '/katalog/rasteniya/iva/belaya-forma-serebristaya/belaya-forma-serebristaya/' => 'Ива белая форма серебристая - Salix alba f. argentea',
    '/katalog/rasteniya/iva/izvilistaya/izvilistaya/' => 'Ива извилистая - Salix matsudana',
    '/katalog/rasteniya/iva/kozya/kozya/' => 'Ива козья - Salix caprea',
    '/katalog/rasteniya/iva/lomkaya/lomkaya/' => 'Ива ломкая - Salix x fragilis',
    '/katalog/rasteniya/iva/purpurnaya/purpurnaya/' => 'Ива пурпурная - Salix purpurea',
    '/katalog/rasteniya/kalina/obyknovennaya/obyknovennaya/' => 'Калина обыкновенная - Viburnum opulus',
    '/katalog/rasteniya/kalina/obyknovennaya-shar/obyknovennaya-shar/' => 'Калина обыкновенная (Шар) - Viburnum obyknovennaya',
    '/katalog/rasteniya/karagana/drevovidnaya/drevovidnaya/' => 'Карагана древовидная - Caragana arborescens',
    '/katalog/rasteniya/kashtan-konskiy/obyknovennyj/obyknovennyj/' => 'Каштан конский обыкновенный - Aesculus hippocastanum',
    '/katalog/rasteniya/kizilnik/blestyashchiy-element-zhivoy-izgorodi/blestyashchiy-element-zhivoy-izgorodi/' => 'Кизильник блестящий (Элемент живой изгороди) - Cotoneaster blestyashchiy',
    '/katalog/rasteniya/kizilnik/blestyashchiy-konus/blestyashchiy-konus/' => 'Кизильник блестящий (Конус) - Cotoneaster blestyashchiy',
    '/katalog/rasteniya/kizilnik/blestyashchiy-kub/blestyashchiy-kub/' => 'Кизильник блестящий (Куб) - Cotoneaster blestyashchiy',
    '/katalog/rasteniya/klen/krasnyj/krasnyj/' => 'Клен красный - Acer rubrum',
    '/katalog/rasteniya/klen/ostrolistnyj/ostrolistnyj/' => 'Клен остролистный - Acer platanoides',
    '/katalog/rasteniya/klen/polevoy-bonsay/polevoy-bonsay/' => 'Клен полевой (Бонсай) - Acer polevoy',
    '/katalog/rasteniya/klen/polevoy-shar/polevoy-shar/' => 'Клен полевой (Шар) - Acer polevoy',
    '/katalog/rasteniya/klen/serebristyj/serebristyj/' => 'Клен серебристый - Acer saccharinum',
    '/katalog/rasteniya/klen/tatarskiy-podvid-ginnala-element-zhivoy-izgorodi/tatarskiy-podvid-ginnala-element-zhivoy-izgorodi/' => 'Клен татарский подвид Гиннала (Элемент живой изгороди) - Acer tatarskiy-podvid-ginnala',
    '/katalog/rasteniya/klen/tatarskiy-podvid-ginnala-shar/tatarskiy-podvid-ginnala-shar/' => 'Клен татарский подвид Гиннала (Шар) - Acer tatarskiy-podvid-ginnala',
    '/katalog/rasteniya/klen/tatarskiy-podvid-ginnala-zont/tatarskiy-podvid-ginnala-zont/' => 'Клен татарский подвид Гиннала (Зонт) - Acer tatarskiy-podvid-ginnala',
    '/katalog/rasteniya/leshchina/obyknovennaya/obyknovennaya/' => 'Лещина обыкновенная - Corylus avellana',
    '/katalog/rasteniya/lipa/krupnolistnaya/krupnolistnaya/' => 'Липа крупнолистная - Tilia platyphyllos',
    '/katalog/rasteniya/lipa/krupnolistnaya-krysha/krupnolistnaya-krysha/' => 'Липа крупнолистная (Крыша) - Tilia krupnolistnaya',
    '/katalog/rasteniya/lipa/krupnolistnaya-kub/krupnolistnaya-kub/' => 'Липа крупнолистная (Куб) - Tilia krupnolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya/melkolistnaya/' => 'Липа мелколистная - Tilia cordata',
    '/katalog/rasteniya/lipa/melkolistnaya-arka/melkolistnaya-arka/' => 'Липа мелколистная (Арка) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-element-zhivoy-izgorodi/melkolistnaya-element-zhivoy-izgorodi/' => 'Липа мелколистная (Элемент живой изгороди) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-konus/melkolistnaya-konus/' => 'Липа мелколистная (Конус) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-krysha/melkolistnaya-krysha/' => 'Липа мелколистная (Крыша) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-kub/melkolistnaya-kub/' => 'Липа мелколистная (Куб) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-shar-na-shtambe/melkolistnaya-shar-na-shtambe/' => 'Липа мелколистная (Шар на штамбе) - Tilia melkolistnaya',
    '/katalog/rasteniya/lipa/melkolistnaya-shpalera/melkolistnaya-shpalera/' => 'Липа мелколистная (Шпалера) - Tilia melkolistnaya',
    '/katalog/rasteniya/listvennitsa/evropejskaya/evropejskaya/' => 'Лиственница европейская - Larix decidua',
    '/katalog/rasteniya/listvennitsa/evropeyskaya-bonsay/evropeyskaya-bonsay/' => 'Лиственница европейская (Бонсай) - Larix evropeyskaya',
    '/katalog/rasteniya/listvennitsa/evropeyskaya-shpalera/evropeyskaya-shpalera/' => 'Лиственница европейская (Шпалера) - Larix evropeyskaya',
    '/katalog/rasteniya/listvennitsa/kempfera/kempfera/' => 'Лиственница Кемпфера - Larix kaempferi',
    '/katalog/rasteniya/listvennitsa/kempfera-arka/kempfera-arka/' => 'Лиственница Кемпфера (Арка) - Larix kempfera',
    '/katalog/rasteniya/listvennitsa/kempfera-bonsay/kempfera-bonsay/' => 'Лиственница Кемпфера (Бонсай) - Larix kempfera',
    '/katalog/rasteniya/listvennitsa/kempfera-element-zhivoy-izgorodi/kempfera-element-zhivoy-izgorodi/' => 'Лиственница Кемпфера (Элемент живой изгороди) - Larix kempfera',
    '/katalog/rasteniya/listvennitsa/kempfera-volna/kempfera-volna/' => 'Лиственница Кемпфера (Волна) - Larix kempfera-volna',
    '/katalog/rasteniya/listvennitsa/sibirskaya-bonsay/sibirskaya-bonsay/' => 'Лиственница сибирская (Бонсай) - Larix sibirskaya',
    '/katalog/rasteniya/mikrobiota/perekrestnoparnaya/perekrestnoparnaya/' => 'Микробиота перекрестнопарная - Microbiota decussata',
    '/katalog/rasteniya/mozhzhevelnik/kazatskij/kazatskij/' => 'Можжевельник казацкий - Juniperus sabina',
    '/katalog/rasteniya/mozhzhevelnik/pfittseriana/pfittseriana/' => 'Можжевельник Пфитцериана - Juniperus x pfitzeriana',
    '/katalog/rasteniya/olkha/chernaya/chernaya/' => 'Ольха черная - Alnus glutinosa',
    '/katalog/rasteniya/olkha/seraya-shar-na-shtambe/seraya-shar-na-shtambe/' => 'Ольха серая (Шар на штамбе) - Alnus seraya',
    '/katalog/rasteniya/pikhta/balzamicheskaya/balzamicheskaya/' => 'Пихта бальзамическая - Abies balsamea',
    '/katalog/rasteniya/pikhta/korejskaya/korejskaya/' => 'Пихта корейская - Abies koreana',
    '/katalog/rasteniya/pikhta/koreyskaya-shar-na-shtambe/koreyskaya-shar-na-shtambe/' => 'Пихта корейская (Шар на штамбе) - Abies koreyskaya',
    '/katalog/rasteniya/pikhta/odnotsvetnaya/odnotsvetnaya/' => 'Пихта одноцветная - Abies concolor',
    '/katalog/rasteniya/pontederiya/serdtselistnaya/serdtselistnaya/' => 'Понтедерия сердцелистная - Pontederia cordata',
    '/katalog/rasteniya/psevdotsuga/menzisa/menzisa/' => 'Псевдотсуга Мензиса - Pseudotsuga menziesii',
    '/katalog/rasteniya/puzyreplodnik/kalinolistnyj/kalinolistnyj/' => 'Пузыреплодник калинолистный - Physocarpus opulifolius',
    '/katalog/rasteniya/roza/morshchinistaya/morshchinistaya/' => 'Роза морщинистая - Rosa rugosa',
    '/katalog/rasteniya/roza/sobachya/sobachya/' => 'Роза собачья - Rosa canina',
    '/katalog/rasteniya/ryabina/melanocarpa/melanocarpa/' => 'Рябина черноплодная - Sorbus melanocarpa',
    '/katalog/rasteniya/ryabina/obyknovennaya/obyknovennaya/' => 'Рябина обыкновенная - Sorbus aucuparia',
    '/katalog/rasteniya/ryabina/obyknovennaya-arka/obyknovennaya-arka/' => 'Рябина обыкновенная (Арка) - Sorbus obyknovennaya',
    '/katalog/rasteniya/samshit/vechnozelenyj/vechnozelenyj/' => 'Самшит вечнозеленый - Buxus sempervirens',
    '/katalog/rasteniya/samshit/vechnozelenyy-piramida/vechnozelenyy-piramida/' => 'Самшит вечнозеленый (Пирамида) - Buxus vechnozelenyy',
    '/katalog/rasteniya/samshit/vechnozelenyy-shar/vechnozelenyy-shar/' => 'Самшит вечнозеленый (Шар) - Buxus vechnozelenyy',
    '/katalog/rasteniya/siren/kitajskaya/kitajskaya/' => 'Сирень китайская - Syringa x chinensis',
    '/katalog/rasteniya/siren/mejera/mejera/' => 'Сирень Мейера - Syringa meyeri',
    '/katalog/rasteniya/siren/obyknovennaya/obyknovennaya/' => 'Сирень обыкновенная - Syringa vulgaris',
    '/katalog/rasteniya/skhenoplektus/ozernyj/ozernyj/' => 'Схеноплектус озерный - Schoenoplectus lacustris',
    '/katalog/rasteniya/skumpiya/kozhevennaya/kozhevennaya/' => 'Скумпия кожевенная - Cotinus coggygria',
    '/katalog/rasteniya/smorodina/alpijskaya/alpijskaya/' => 'Смородина альпийская - Ribes alpinum',
    '/katalog/rasteniya/sosna/chernaya/chernaya/' => 'Сосна черная - Pinus nigra',
    '/katalog/rasteniya/sosna/chernaya-bonsay/chernaya-bonsay/' => 'Сосна черная (Бонсай) - Pinus chernaya',
    '/katalog/rasteniya/sosna/chernaya-shar/chernaya-shar/' => 'Сосна черная (Шар) - Pinus chernaya',
    '/katalog/rasteniya/sosna/gornaya/gornaya/' => 'Сосна горная - Pinus mugo',
    '/katalog/rasteniya/sosna/gornaya-bonsay/gornaya-bonsay/' => 'Сосна горная (Бонсай) - Pinus gornaya',
    '/katalog/rasteniya/sosna/gornaya-shar/gornaya-shar/' => 'Сосна горная (Шар) - Pinus gornaya',
    '/katalog/rasteniya/sosna/gornaya-zont/gornaya-zont/' => 'Сосна горная (Зонт) - Pinus gornaya',
    '/katalog/rasteniya/sosna/obyknovennaya/obyknovennaya/' => 'Сосна обыкновенная - Pinus sylvestris',
    '/katalog/rasteniya/sosna/obyknovennaya-bonsay/obyknovennaya-bonsay/' => 'Сосна обыкновенная (Бонсай) - Pinus obyknovennaya',
    '/katalog/rasteniya/sosna/obyknovennaya-piniya/obyknovennaya-piniya/' => 'Сосна обыкновенная (Пиния) - Pinus obyknovennaya',
    '/katalog/rasteniya/sosna/obyknovennaya-volna/obyknovennaya-volna/' => 'Сосна обыкновенная (Волна) - Pinus obyknovennaya',
    '/katalog/rasteniya/sosna/obyknovennaya-zont/obyknovennaya-zont/' => 'Сосна обыкновенная (Зонт) - Pinus obyknovennaya',
    '/katalog/rasteniya/sosna/untsinata/untsinata/' => 'Сосна унцината - Pinus uncinata',
    '/katalog/rasteniya/sosna/vejmutova/vejmutova/' => 'Сосна Веймутова - Pinus strobus',
    '/katalog/rasteniya/spireya/arguta/arguta/' => 'Спирея аргута - Spiraea x arguta',
    '/katalog/rasteniya/spireya/berezolistnaya/berezolistnaya/' => 'Спирея березолистная - Spiraea betulifolia',
    '/katalog/rasteniya/spireya/billarda/billarda/' => 'Спирея Билларда - Spiraea x billardii',
    '/katalog/rasteniya/spireya/nipponskaya/nipponskaya/' => 'Спирея ниппонская - Spiraea nipponica',
    '/katalog/rasteniya/spireya/vangutta/vangutta/' => 'Спирея Вангутта - Spiraea x vanhouttei',
    '/katalog/rasteniya/tis/yagodnyj/yagodnyj/' => 'Тис ягодный - Taxus baccata',
    '/katalog/rasteniya/topol/chernyj/chernyj/' => 'Тополь черный - Populus nigra',
    '/katalog/rasteniya/trostnik/yuzhnyj/yuzhnyj/' => 'Тростник южный - Phragmites australis',
    '/katalog/rasteniya/verbejnik/monetchatyj/monetchatyj/' => 'Вербейник монетчатый - Lysimachia nummularia',
    '/katalog/rasteniya/vyaz/melkolistnyy-shar/melkolistnyy-shar/' => 'Вяз мелколистный (Шар) - Ulmus melkolistnyy',
    '/katalog/rasteniya/vyaz/shershavyj/shershavyj/' => 'Вяз шершавый - Ulmus glabra Huds.',
    '/katalog/rasteniya/yablonya/sarzhenta-zont/sarzhenta-zont/' => 'Яблоня Саржента (Зонт) - Malus sarzhenta',
    '/katalog/rasteniya/yablonya/toringo/toringo/' => 'Яблоня торинго - Malus toringo',
    '/katalog/rasteniya/yablonya/toringo-shar/toringo-shar/' => 'Яблоня торинго (Шар) - Malus toringo',
    '/katalog/rasteniya/yasen/obyknovennyj/obyknovennyj/' => 'Ясень обыкновенный - Fraxinus excelsior',
    '/katalog/rasteniya/zhimolost/tatarskaya/tatarskaya/' => 'Жимолость татарская - Lonicera tatarica',
];

echo '<pre>';
foreach ($headings as $url => $heading) {
    $page = VirtualPageTable::getList([
        'select' => ['ID'],
        'filter' => [
            'UF_URL' => $url,
        ],
    ])->fetch();

    if ($page === false) {
        $result = VirtualPageTable::add([
            'UF_NAME' => $heading,
            'UF_URL' => $url,
            'UF_H1' => $heading,
            'UF_ACTIVE' => 1,
        ]);

        if ($result->isSuccess()) {
            echo 'OK' . PHP_EOL;
            continue;
        }
        exit();
    }
    $result = VirtualPageTable::update($page['ID'], [
        'UF_H1' => $heading,
        'UF_ACTIVE' => 1,
    ]);
    if ($result->isSuccess()) {
        echo 'OK' . PHP_EOL;
        continue;
    }
    exit();
}
echo '</pre>';