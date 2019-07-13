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
		<div class="cols-wrapper cols-wrapper--h1-stabs">
			<div class="cols">
				<div class="col col--heading">
					<h1><?=$arResult["NAME"]?></h1>
				</div>
				<div class="col col--stabs">
					<div class="nav stabs">
						<div class="stabs__list">
							<div class="stabs__item<?=($arParams["PARENT_SECTION"]<=0?' active':'')?>">
								<a class="stabs__link" href="<?=$arParams["IBLOCK_URL"]?>">
									Все
								</a>
							</div><?
							foreach($arResult["SECTIONS"] as $arSection) {?>
							<div class="stabs__item<?=($arParams["PARENT_SECTION"]==$arSection["ID"]?' active':'')?>">
								<a class="stabs__link" href="<?=$arSection["SECTION_PAGE_URL"]?>">
									<?=$arSection["NAME"]?>
								</a>
							</div><?
							}?>
						</div>
					</div>
				</div>
			</div>
		</div><?
		if(!empty($arResult["ITEMS"])) {?>
		<div class="testimonials">
			<div class="testimonials__inner" id="container-<?=$arParams["PAGER_TITLE"]?>">
				<div class="testimonials__line"><?
					$intCnt = 0;
					foreach($arResult["ITEMS"] as $arItem) {
						if($intCnt>0 && $intCnt%2===0) {
							echo '</div><div class="testimonials__line">';
						}
						
						if($arItem["PROPERTIES"]["VIDEO_FILE"]["VALUE"]) { ?>
							<div class="testimonial testimonial--video">
								<div class="testimonial__inner">
									<div class="testimonial__video">
										<a class="ivideo" href="<?=CFile::GetPath($arItem["PROPERTIES"]["VIDEO_FILE"]["VALUE"])?>" target="_blank" data-fancybox>
											<div class="ivideo__image">
												<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="">
											</div>
											<div class="ivideo__icon">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-play-circle"></use>
												</svg>
											</div>
										</a>
									</div>
								
								</div>
							</div>
							<?
						} elseif($arItem["PROPERTIES"]["VIDEO_LINK"]["VALUE"]) { ?>
							<div class="testimonial testimonial--video">
								<div class="testimonial__inner">
									
									<div class="testimonial__video">
										<a class="ivideo" href="<?=$arItem["PROPERTIES"]["VIDEO_LINK"]["VALUE"]?>" target="_blank" data-fancybox>
											<div class="ivideo__image">
												<img data-lazy-src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="">
											</div>
											<div class="ivideo__icon">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-play-circle"></use>
												</svg>
											</div>
										</a>
									</div>
								
								</div>
							</div><?
						} else { ?>
							<div class="testimonial">
								<div class="testimonial__inner">
									<div class="testimonial__icon">
										<svg class="icon icon--quote">
											<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-quote"></use>
										</svg>
									</div>
									<div class="testimonial__summary">
										<p><?=$arItem["PREVIEW_TEXT"]?></p>
									</div>
									<div class="testimonial__author">
										<div class="testimonial__author-image">
											<div class="testimonial__author-image-inner">
												<img data-lazy-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
											</div>
										</div>
										<div class="testimonial__author-content">
											<div class="testimonial__author-title h4"><?=$arItem["NAME"]?></div>
											<div class="testimonial__author-date"><a class="link--bordered" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" target="_blank"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></a></div>
										</div>
									</div><?
									if($arItem["PROPERTIES"]["LINK"]["VALUE"]) {
										if(strpos($arItem["PROPERTIES"]["LINK"]["VALUE"], 'facebook') !== false) {
											$strIcon = 'facebook';
										} else if(strpos($arItem["PROPERTIES"]["LINK"]["VALUE"], 'vk') !== false) {
											$strIcon = 'vkontakte';
										} ?>
									<div class="testimonial__social">
										<div class="csocials">
											<a class="csocial csocial--grey csocial--<?=$strIcon?>" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>">
												<svg class="icon icon--<?=$strIcon?>">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-<?=$strIcon?>"></use>
												</svg>
											</a>
										</div>
									</div><?
									}?>
								</div>
							</div><?
						}
						
						$intCnt++;
					}?>
				</div>
			</div>
		</div>
		<?=$arResult["NAV_STRING"];?><?
		}?>
	</div>
</div>