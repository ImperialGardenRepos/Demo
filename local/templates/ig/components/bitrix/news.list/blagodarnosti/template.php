<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="section section--grey section--article section--fullheight text">
	<div class="container">
		
		<h1><?=$arResult["NAME"]?></h1>
		
		<p>Мы гордимся тем, что мы делаем.
			Потому что мы не только продаем уникальные сорта растений и породы деревьев, мы не только создаем садовые ансамбли на высочайшем уровне. Всей своей деятельностью мы помогаем природе. Вместе с вами. Каждый посаженный вами росток, каждый разбитый сад - это помощь окружающему нас миру и всей планете. Если каждый человек позаботится о том, чтобы посадить свое дерево, весь мир станет лучше. Давайте это делать вместе!</p><?
		if(!empty($arResult["ITEMS"])) {?>
		<div class="thanksgivings">
			<div class="thanksgivings__inner" id="container-<?=$arParams["PAGER_TITLE"]?>"><?
				foreach($arResult["ITEMS"] as $arItem) {?>
				<div class="thanksgiving">
					<div class="thanksgiving__inner">
						
						<a class="thanksgiving__image img-to-bg" href="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" data-fancybox>
							<img data-lazy-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
						</a>
					
					</div>
				</div><?
				}?>
			</div>
		</div><?
			echo $arResult["NAV_STRING"];
		}?>
	</div>
</div>