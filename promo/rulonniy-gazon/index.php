<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Рулонный газон");
?>
<link rel="stylesheet" href="/promo/style.css">

<section class="text">
    <div class="text__content text__content--image-flow-center">
                <div class="text__text">
                    </div>
                    <div class="text__img">
                <!--ToDO::get image description to be used with alt-->
                <img src="./banner.png">
            </div>
            </div>
</section>
<section class="text">
    <div class="text__content text__content--image-flow-left">
                    <style>
                @media all and (min-width: 800px) {
                    .text__img--769dfe61eb8ee42c9223c91ba286f14c {
                        max-width: 45% !important;
                    }

                    .text__text--769dfe61eb8ee42c9223c91ba286f14c {
                        max-width: 50% !important;
                    }
                }
            </style>
                <div class="text__text text__text--769dfe61eb8ee42c9223c91ba286f14c">
            <p><strong>Партерный (де люкс)</strong></p>
<p>Видовой состав:<br>
100% мятлик (Poa pratensis).<br>
Сортовой состав: 25% Award, 25% Sudden Impact, 25% NuGlade, 25% Full Moon.</p>

	<img alt="140 р. за кв. м" src="140.png">

<p><strong>Теневой</strong></p>
<p>Видовой состав: 80% мятлик, (Poa pratensis)<br>
2 сорта, овсянница красная (Festuca rubra).<br>
Сортовой состав: мятлик луговой сорт 40% баририс, мятлик луговой сорт<br>
40% барониал, овсяница красная жесткая сорт 20% баргрин.</p>

        <img alt="160 р. за кв. м" src="160.png">

<p><strong>Универсальный</strong></p>
<p>Видовой состав:<br>
100% мятлик луговой (Poa pratensis).<br>
Сортовой состав: 30% Sudden Impact Kentucky, 30% SR 2284, 15% Jumpstart, 25% Skye.</p>
	<img alt="180 р. за кв. м" src="180.png">        </div>
                    <div class="text__img text__img--769dfe61eb8ee42c9223c91ba286f14c">
                <!--ToDO::get image description to be used with alt-->
                <img src="left.png">
            </div>
            </div>
</section>
<section class="text">
    <div class="text__content text__content--image-flow-right">
                    <style>
                @media all and (min-width: 800px) {
                    .text__img--d6f4a770ff0e94f8bad0da035ae013cf {
                        max-width: 45% !important;
                    }

                    .text__text--d6f4a770ff0e94f8bad0da035ae013cf {
                        max-width: 50% !important;
                    }
                }
            </style>
                <div class="text__text text__text--d6f4a770ff0e94f8bad0da035ae013cf">
            <p><strong>ПАРТЕРНЫЙ ГАЗОН</strong></p>
<p>Наиболее элегантный и роскошный вид газона с правильно сформированным
дерном, который можно укладывать на городских территориях с ощутимым
недостатком солнца (парки, сады, аллеи), а также на приусадебных участках,
где газон находится в тени дома, под тентом, в сени деревьев. Возраст газона
3 года.</p>
<p><strong>ТЕНЕВОЙ ГАЗОН</strong></p>
<p>В одинаковых по площади рулонах — двухлетняя дернина, устойчивая к
вытаптыванию и непродолжительным засухам. После пересадки партерный
зеленый газон быстро приживается, имеет густой травостой, насыщенный
цвет. В партерном газоне преобладают однородные по цвету и фактуре
травинки. Трава быстро восстанавливается после скашивания и
механического воздействия.</p>
<p><strong>УНИВЕРСАЛЬНЫЙ ГАЗОН</strong></p>
<p>Универсальный рулонный газон обладает упругой дерниной, не боится
засух, морозных зим и различных механических нагрузок. Благодаря
оптимальному соотношению четырех сортов мятлика лугового он имеет
красивый внешний вид как на участках со слабым освещением, так и в
местах с прямыми лучами. Возраст газона 2 года.</p>        </div>
                    <div class="text__img text__img--d6f4a770ff0e94f8bad0da035ae013cf">
                <!--ToDO::get image description to be used with alt-->
                <img src="right.png">
            </div>
            </div>
</section>
<?$APPLICATION->IncludeComponent(
	"ig:contacts",
	"",
Array()
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>