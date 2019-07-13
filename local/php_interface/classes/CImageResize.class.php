<?php
namespace ig;

class CImageResize {
	private static $intMaxWidth = 1280,
		$intMaxHeight = 1280;
	
	/**
	 * Масштабирует фото, сохраняет копию файла и возвращает путь к нему
	 * либо возвращает ссылку на картинку-заглушку
	 *
	 * ---
	 *
	 * Водяной знак - если существует файл /upload/watermark/watermark_original.png - он будет
	 * смасштабирован под фото и нанесен на всю поверхность с небольшим отступом от края.
	 * watermark_original.png - должен быть большого размера, чтобы не терялось качество.
	 *
	 * @param $imgId
	 * @param $width int
	 * @param $height int Если не задано, будет пропорционально ширине
	 * @param $proportional bool false - Обрезать жестко по заданному размеру (удобно для мини картинок). true - пропорционально (для больших)
	 *
	 * @throws Exception File dimensions can not be a null
	 *
	 *
	 * @return string Путь к измененному файлу
	 *
	 * @see https://dermanov.ru/exp/bitrix-resize-image-and-watermark/ - Примеры работы функции
	 */
	public function getResizedImgOrPlaceholder($imgId, $width, $height = "auto", $arParams = array()) {
		if(isset($arParams["PROPORTIONAL"])) {
			$proportional = $arParams["PROPORTIONAL"];
		}
		
		if (!$width)
			throw new \Exception( "File dimensions can not be a null" );
		$resizeType = BX_RESIZE_IMAGE_EXACT;
		$autoHeightMax = 768;
		//
		if ($height == "auto") {
			$height = $autoHeightMax;
			$resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
		}
		if (!$height)
			$height = $width;
		
		if ($proportional)
			$resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
		
		// если картинка не существует (например, пустое значение некотрого св-ва) - вернем заглушку нужного размера
		if (!$imgId) {
//			// тут можно положить собственную заглушку под стиль сайта
			$customNoImg = SITE_TEMPLATE_PATH . "/upload/img_placeholder.jpg";
			// есть ограничение на размер заглушки на сайте dummyimage.com. можно еще задать цвет фона и текста.
			$height = $height == $autoHeightMax ? $width : $height;
			return file_exists($_SERVER["DOCUMENT_ROOT"] . $customNoImg) ? $customNoImg : "http://dummyimage.com/{$width}x{$height}/5C7BA4/fff";
		}
		
		$arFilters = [];
		/*
		 * <watermark>
		 * 1) получаем размер ($arDestinationSize) итоговой картинки (фото товара) после ресайза, с учетом типа ресайза ($resizeType)
		 * 2) создаем водяной знак под этот размер фото (он должен быть чуть меньше самого фото)
		 * 3) формируем фильтр для наложения знака
		 * */
		if($arParams["WATERMARK"] != 'N') {
			$watermark = $_SERVER['DOCUMENT_ROOT']."/upload/watermark/watermark_original.png";
			if(is_readable($watermark)) {
//				$bNeedCreatePicture = $arSourceSize = $arDestinationSize = false;
//				$imgSize = \CFile::GetImageSize($_SERVER["DOCUMENT_ROOT"].\CFile::GetPath($imgId));
//				\CFile::ScaleImage($imgSize["0"], $imgSize["1"], array("width"  => $width,
//				                                                       "height" => $height
//				), $resizeType, $bNeedCreatePicture, $arSourceSize, $arDestinationSize);
				
				$watermarkResized = $watermark;

//				$koef = 0.95;
//				$watermarkResized = $_SERVER['DOCUMENT_ROOT']."/upload/watermark/watermark_".$arDestinationSize["width"] * $koef.".png";
//				if(!is_readable($watermarkResized)) {
//					\CFile::ResizeImageFile($watermark, $watermarkResized, ["width"  => $arDestinationSize["width"] * $koef,
//					                                                        "height" => $arDestinationSize["height"] * $koef
//					], BX_RESIZE_IMAGE_PROPORTIONAL, false, 95, []);
//				}
				if(is_readable($watermarkResized)) {
					$arFilters[] = [
						"name"     => "watermark",
						"position" => "center",
						"size"     => "real",
						"file"     => $watermarkResized
					];
				}
			}
		}
		/*
		 * </watermark>
		 * */
		
		$resizedImg = \CFile::ResizeImageGet($imgId, [ "width" => $width, "height" => $height ], $resizeType, false, $arFilters, false, 20);
		
		// если файл по каким-то причинам не создался - вернем заглушку
		if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $resizedImg['src'])) {
			if ($height == $autoHeightMax)
				$height = $width;
			return self::getResizedImgOrPlaceholder(false, $width, $height, $proportional);
		}
		
		return $resizedImg['src'];
	}
	
	public static function initEvents() {
		// элементы перехватываем только при апдейте из админки. импорт обрабатываем локально
		if(isset($GLOBALS["APPLICATION"])) {
			if(strpos($GLOBALS["APPLICATION"] -> GetCurDir(), '/bitrix/') === 0) {
				AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("\\ig\\CImageResize", "processSaveElement"));
				AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("\\ig\\CImageResize", "processSaveElement"));
			}
		}
	}
	
	function processSaveElement(&$arFields) {
		if(
			(
				$arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode('catalog')
					||
				$arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode('offers')
			)
		) {
			$arResizeProps = array("PHOTO_WINTER", "PHOTO_AUTUMN", "PHOTO_SUMMER", "PHOTO_FLOWER", "PHOTO_FRUIT", "PHOTO_10YEARS");
			foreach($arResizeProps as $strPropCode) {
				$intPropertyID = CHelper::getPropertyIDByCode($strPropCode);
				
				foreach($arFields["PROPERTY_VALUES"][$intPropertyID] as $strKey => $arPropertyValue) {
					if(isset($arPropertyValue["VALUE"])) {
						$arFile = $arPropertyValue["VALUE"];
					} else {
						$arFile = $arPropertyValue;
					}
					
					if($arFile["error"] == 0 && $arFile["size"]>0) {
						$arResizeParams = array(
							"WIDTH" => self::$intMaxWidth,
							"HEIGHT" => self::$intMaxHeight,
							"METHOD" => "resample"
						);
						$arResizedFile = \CIBlock::ResizePicture($arFile, $arResizeParams);
						
						if(isset($arPropertyValue["VALUE"])) {
							$arFields["PROPERTY_VALUES"][$intPropertyID][$strKey]["VALUE"] = $arResizedFile;
						} else {
							$arFields["PROPERTY_VALUES"][$intPropertyID][$strKey] = $arResizedFile;
						}
					}
				}
			}
		}
	}
	
	private static function resizeImage($arFile) {
		if($arFile["error"] == 0 && $arFile["size"]>0) {
			self::_resizeImage($arFile["tmp_name"]); // результат ресайза пишем в тот же файл
			$arFile["size"] = filesize($arFile["tmp_name"]); // новый размер
		}
		
		return $arFile;
	}
	
	private static function _resizeImage($srcFile, $dstFile = '') {
		if(strlen($dstFile) == 0) $dstFile = $srcFile;
		
		$io = \CBXVirtualIo::GetInstance();
		
		if (!$io->FileExists($srcFile))
			return false;
		
		if (!\CFile::IsGD2()) return false;
		
		$arSourceFileData = \CFile::GetImageSize($srcFile);
		if (!in_array($arSourceFileData[2], array(IMAGETYPE_PNG, IMAGETYPE_JPEG)))
			return false;
		
		$intOriginalWidth = $arSourceFileData[0];
		$intOriginalHeight = $arSourceFileData[1];
		
		$floatWidthRatio = $intOriginalWidth / self::$intMaxWidth;
		$floatHeightRatio = $intOriginalHeight / self::$intMaxHeight;
		
		if($floatHeightRatio<=1 && $floatWidthRatio<=1) {
			return false;
		} {
			$floatMinRatio = $floatHeightRatio > $floatWidthRatio ? $floatWidthRatio : $floatHeightRatio;
			
			$intScaledWidth = intval($intOriginalWidth / $floatMinRatio);
			$intScaledHeight = intval($intOriginalHeight / $floatMinRatio);
		}
		
		$intDstX = $intDstHalfWidth - $intScaledHalfWidth;
		if($intDstX<0) {
			$intDstWidth = self::$intMaxWidth;
			$intSrcX = intval(-1*$intDstX * $floatMinRatio);
			$intSrcWidth = intval($intDstWidth * $floatMinRatio);
			$intDstX = 0;
		} else {
			$intDstWidth = $intScaledWidth;
			$intSrcWidth = intval($intDstWidth * $floatMinRatio);
			$intSrcX = 0;
		}
		
		$intDstY = $intDstHalfHeight - $intScaledHalfHeight;
		if($intDstY<0) {
			$intDstHeight = self::$intMaxHeight;
			$intSrcY = intval(-1*$intDstY * $floatMinRatio);
			$intSrcHeight = intval($intDstHeight * $floatMinRatio);
			$intDstY = 0;
		} else {
			$intDstHeight = $intScaledHeight;
			$intSrcHeight = intval($intDstHeight * $floatMinRatio);
			$intSrcY = 0;
		}
		
		switch ($arSourceFileData[2])
		{
			case IMAGETYPE_PNG:
				$rsSourceImage = imagecreatefrompng($io->GetPhysicalName($srcFile));
				break;
			default:
				$rsSourceImage = imagecreatefromjpeg($io->GetPhysicalName($srcFile));
				break;
		}
		
		$rsPicture = ImageCreateTrueColor(self::$intMaxWidth, self::$intMaxHeight);
		if($arSourceFileData[2] == IMAGETYPE_PNG) {
			$transparentColor = imagecolorallocatealpha($rsPicture, 0, 0, 0, 127);
			imagefilledrectangle($rsPicture, 0, 0, self::$intMaxWidth, self::$intMaxHeight, $transparentColor);
			imagealphablending($rsPicture, false);
		} else {
			$whiteColor = imagecolorallocate($rsPicture, 255, 255, 255);
			imagefilledrectangle($rsPicture, 0, 0, self::$intMaxWidth, self::$intMaxHeight, $whiteColor);
		}
		
		imagecopyresampled(
			$rsPicture,
			$rsSourceImage,
			$intDstX,
			$intDstY,
			$intSrcX,
			$intSrcY,
			$intDstWidth,
			$intDstHeight,
			$intSrcWidth,
			$intSrcHeight
		);
		
		if($arSourceFileData[2] == IMAGETYPE_PNG) {
			imagealphablending($rsPicture, true);
		}
		
		if($io->FileExists($dstFile))
			$io->Delete($dstFile);
		
		switch ($arSourceFileData[2])
		{
			case IMAGETYPE_PNG:
				imagealphablending($rsPicture, false );
				imagesavealpha($rsPicture, true);
				imagepng($rsPicture, $io->GetPhysicalName($dstFile));
				break;
			default:
				$jpgQuality = intval(\COption::GetOptionString('main', 'image_resize_quality', '20'));
			if($jpgQuality <= 0 || $jpgQuality > 100 || true)
					$jpgQuality = 20;
				
				imagejpeg($rsPicture, $io->GetPhysicalName($dstFile), $jpgQuality);
				break;
		}
		
		imagedestroy($rsPicture);
		
		return true;
	}
}