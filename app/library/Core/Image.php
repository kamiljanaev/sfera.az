<?php
class Core_Image
{
	const
		FULL = 1,
		LISTS = 2,
		PARAGRAPH_FLOAT = 3,
		PARAGRAPH_NORMAL = 4,
		MATCHING = 5,
		USERINFO = 6,
		PROFILE = 7,
		FEED = 8,
        ICONS = 9,
        AVATAR_100 = 10,
        AWARDS = 11,
        NEWS_HOME_LAST = 12,
        HOT_NEWS_FIRST = 13,
        HOT_NEWS_SECOND = 14,
        HOT_NEWS_THIRD = 15,
        HOT_NEWS_FORTH = 16,
        HOT_NEWS_FIFTH = 17,
        NEWS_HOME_LIST = 18,
        ADS_HOME_PAYED = 19,
        ADS_HOME_REALTY = 20,
        AVATAR_70 = 21,
        AVATAR_40 = 22,
        NEWS_SMALL = 23,
        NEWS_MEDIUM = 24,
        NEWS_BIG = 25,
        NEWS_LIST = 26

        ;
	private
		$_config;

	public function __construct()
	{
		$this->_config = Zend_Registry::get('systemconfig')->images;
	}

	private static function getSettingObjectByType($type)
	{
		$config = Zend_Registry::get('systemconfig')->images;
		$out = '';
		switch ($type) {
			default:
			case self::FULL:
				$out = $config;
				break;
			case self::LISTS:
				$out = $config->thumbnail->types->lists;
				break;
            case self::PARAGRAPH_FLOAT:
                $out = $config->thumbnail->types->paragraphsFloat;
                break;
            case self::AVATAR_100:
                $out = $config->thumbnail->types->avatar_100;
                break;
            case self::AVATAR_70:
                $out = $config->thumbnail->types->avatar_70;
                break;
            case self::AVATAR_40:
                $out = $config->thumbnail->types->avatar_40;
                break;
            case self::AWARDS:
                $out = $config->thumbnail->types->awards;
                break;
            case self::NEWS_HOME_LAST:
                $out = $config->thumbnail->types->newsHomeLast;
                break;
            case self::HOT_NEWS_FIRST:
                $out = $config->thumbnail->types->hotNewsFirst;
                break;
            case self::HOT_NEWS_SECOND:
                $out = $config->thumbnail->types->hotNewsSecond;
                break;
            case self::HOT_NEWS_THIRD:
                $out = $config->thumbnail->types->hotNewsThird;
                break;
            case self::HOT_NEWS_FORTH:
                $out = $config->thumbnail->types->hotNewsForth;
                break;
            case self::HOT_NEWS_FIFTH:
                $out = $config->thumbnail->types->hotNewsFifth;
                break;
            case self::NEWS_HOME_LIST:
                $out = $config->thumbnail->types->newsHomeList;
                break;
            case self::ADS_HOME_PAYED:
                $out = $config->thumbnail->types->adsHomePayed;
                break;
            case self::ADS_HOME_REALTY:
                $out = $config->thumbnail->types->adsHomeRealty;
                break;
            case self::NEWS_SMALL:
                $out = $config->thumbnail->types->news_small;
                break;
            case self::NEWS_MEDIUM:
                $out = $config->thumbnail->types->news_medium;
                break;
            case self::NEWS_BIG:
                $out = $config->thumbnail->types->news_big;
                break;
            case self::NEWS_LIST:
                $out = $config->thumbnail->types->news_list;
                break;
/*			case self::PARAGRAPH_NORMAL:
				$out = $config->thumbnail->types->paragraphsNormal;
				break;
			case self::MATCHING:
				$out = $config->thumbnail->types->matching;
				break;
			case self::USERINFO:
				$out = $config->thumbnail->types->userInfo;
				break;
			case self::PROFILE:
				$out = $config->thumbnail->types->profile;
				break;
			case self::FEED:
				$out = $config->thumbnail->types->feed;
				break;
*/			case self::ICONS:
				$out = $config->thumbnail->types->icons;
				break;
		}
		return $out;
	}

	public static function getImagePath($path, $type)
	{
		global $rootPublic;
		$config = Zend_Registry::get('systemconfig')->images;
		$outPath = '';
		if (strlen($path) == 0) {
			return $outPath;
		}
		$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
		$path = rtrim($rootPublic, DIRECTORY_SEPARATOR).$path;
		$configObject = self::getSettingObjectByType($type);
		if ($configObject != null && isset($configObject->subPath)) {
			$thumbnailPath = str_replace($config->uploadBasePath, $config->thumbnailPath, $path);
			$pathInfo = pathinfo($thumbnailPath);
			//echo $path.' '.$type;
			$thumbnailPath = $pathInfo['dirname'].DIRECTORY_SEPARATOR.$configObject->subPath.$pathInfo['basename'];
			if ($type == self::FULL) {
				$outPath = $path;
			} else {
				$outPath = $thumbnailPath;
			}
			if (!file_exists($outPath)) {
				$img = new Core_Image();
				$img->createImages($path, $configObject);
			}
			$imagePath = mb_substr($outPath, mb_strlen($rootPublic)-1);
			if (strstr(PHP_OS, 'WIN')) {
				$imagePath = str_replace('\\', '/', $imagePath);
			}
			return $imagePath;
		}
		return null;
	}

	public static function getImageHtmlCode($path, $type, $atributes=array())
	{
		if (trim($path)=="") return "";
		$html = '<img src="'.Core_Image::getImagePath($path, $type).'" ';
		foreach ($atributes as $name=>$value) {
			$html .= $name.'="'.$value.'" ';
		}
		$html .= '/>';
		return $html;
	}

	public function createImages($path, $configObject = null)
	{
		if (file_exists($path) && is_file($path)) {
			if (Core_Image::isImage($path)) {
				$pathInfo = pathinfo($path);
				$folder = $pathInfo['dirname'];
				$this->createSubFolders($path);
				$this->createImage($path,
						$path,
						$this->_config->maxWidth,
						$this->_config->maxHeight,
						$this->getJpegQuality(self::FULL)
				);
				if ($configObject == null) {
					foreach ($this->_config->thumbnail->types as $configObject) {
						$thumbnailPath = str_replace($this->_config->uploadBasePath, $this->_config->thumbnailPath, $path);
						$pathInfo = pathinfo($thumbnailPath);
						$thumbnailPath = $pathInfo['dirname'].'/'.$configObject->subPath.$pathInfo['basename'];
						$this->createImage($path,
								$thumbnailPath,
								$configObject->maxWidth,
								$configObject->maxHeight,
								$this->getJpegQuality($configObject),
                                isset($configObject->crop)?$configObject->crop:false
						);
					}
				} else {
					$thumbnailPath = str_replace($this->_config->uploadBasePath, $this->_config->thumbnailPath, $path);
					$pathInfo = pathinfo($thumbnailPath);
					$thumbnailPath = $pathInfo['dirname'].'/'.$configObject->subPath.$pathInfo['basename'];
					$this->createImage($path,
							$thumbnailPath,
							$configObject->maxWidth,
							$configObject->maxHeight,
							$this->getJpegQuality($configObject),
                            isset($configObject->crop)?$configObject->crop:false
					);
				}
			}
		}
	}

	public function uploadImages($path)
	{
		if (!is_array($_FILES)) {
			return false;
		}
		$resultArray = array();
		foreach ($_FILES as $key=>$uploadedFile) {
			while (file_exists($_SERVER["DOCUMENT_ROOT"] . $path . $uploadedFile['name'])) {
				$uploadedFile['name'] = time().$uploadedFile['name'];
			}
			$targetWebFileName = $path . $uploadedFile['name'];
			$targetFileName = $_SERVER["DOCUMENT_ROOT"] . $targetWebFileName;
			if (!move_uploaded_file($uploadedFile['tmp_name'], $targetFileName)) {
				continue;
			}
			chmod($targetFileName, 0755);
			Core_Image::createImages($targetFileName);
			$imageInfo = array(	"icon" => Core_Image::getImagePath($targetWebFileName, self::PROFILE),
					"path" => Core_Image::getImagePath($targetWebFileName, self::FULL),
					"name" => $uploadedFile['name']);
			$resultArray[$key] = $imageInfo;
		}
		return $resultArray;
	}

	public static function deleteImages($path)
	{
		try {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::LISTS))) {
				$_unlink_list = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::LISTS));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PARAGRAPH_FLOAT))) {
				$_unlink_float = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PARAGRAPH_FLOAT));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PARAGRAPH_NORMAL))) {
				$_unlink_normal = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PARAGRAPH_NORMAL));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::MATCHING))) {
				$_unlink_list = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::MATCHING));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::USERINFO))) {
				$_unlink_list = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::USERINFO));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PROFILE))) {
				$_unlink_list = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::PROFILE));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::FEED))) {
				$_unlink_full = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::FEED));
			}
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::ICONS))) {
				$_unlink_full = unlink($_SERVER['DOCUMENT_ROOT'] . Core_Image::getImagePath($path, self::ICONS));
			}
			return true;
		} catch (Exception $e) {
			return true;
		}
	}

	private static function isImage($path)
	{
		$pathInfo = pathinfo($path);
		$extension = $pathInfo['extension'];
		$imageExtensions = array('gif', 'jpg', 'jpeg', 'png');
		return in_array(strtolower($extension), $imageExtensions);
	}

	private function createSubFolders($path)
	{
		$config = Zend_Registry::get('systemconfig')->images;
		$thumbnailPath = str_replace($config->uploadBasePath, $config->thumbnailPath, $path);
		$pathInfo = pathinfo($thumbnailPath);
		$folder = $pathInfo['dirname'];
		foreach ($config->thumbnail->types as $configObject) {
			$subfolder = $folder.'/'.$configObject->subPath;
			if (!is_dir($subfolder))
				mkdir($subfolder, 0777, true);
		}
	}

	private function getJpegQuality($configObject)
	{
		$outQuality = $this->_config->thumbnail->quality;
		if (isset ($configObject->quality)) {
			$outQuality = $configObject->quality;
		}
		return $outQuality;
	}

	private function createImage($sourceFile, $targetFile, $maxWidth, $maxHeight, $quality, $crop = false)
	{
		$sourceImageAttr = @getimagesize($sourceFile);
		if ($sourceImageAttr === false) {
			return false;
		}
		$sourceImageWidth = isset ($sourceImageAttr[0])?$sourceImageAttr[0]:0;
		$sourceImageHeight = isset ($sourceImageAttr[1])?$sourceImageAttr[1]:0;
		$sourceImageMime = isset ($sourceImageAttr["mime"])?$sourceImageAttr["mime"]:"";
		$sourceImageBits = isset ($sourceImageAttr["bits"])?$sourceImageAttr["bits"]:8;
		$sourceImageChannels = isset ($sourceImageAttr["channels"])?$sourceImageAttr["channels"]:3;

		if (!$sourceImageWidth || !$sourceImageHeight || !$sourceImageMime) {
			return false;
		}

		if ($sourceImageWidth <= $maxWidth && $sourceImageHeight <= $maxHeight) {
			if ($sourceFile != $targetFile) {
				copy($sourceFile, $targetFile);
			}
			return true;
		}

		$oSize = Core_Image::getRequestedSize($maxWidth, $maxHeight, $sourceImageWidth, $sourceImageHeight, $crop);
		Core_Image::setMemoryForImage($sourceImageWidth, $sourceImageHeight, $sourceImageBits, $sourceImageChannels);
		switch ($sourceImageAttr['mime']) {
			case 'image/gif': {
					if (@imagetypes() & IMG_GIF) {
						$oImage = @imagecreatefromgif ($sourceFile);
					}
					else {
						$ermsg = 'GIF images are not supported';
					}
				}
				break;
			case 'image/jpeg': {
					if (@imagetypes() & IMG_JPG) {
						$oImage = @imagecreatefromjpeg($sourceFile);
					}
					else {
						$ermsg = 'JPEG images are not supported';
					}
				}
				break;
			case 'image/png': {
					if (@imagetypes() & IMG_PNG) {
						$oImage = @imagecreatefrompng($sourceFile);
					}
					else {
						$ermsg = 'PNG images are not supported';
					}
				}
				break;
			case 'image/wbmp': {
					if (@imagetypes() & IMG_WBMP) {
						$oImage = @imagecreatefromwbmp($sourceFile);
					}
					else {
						$ermsg = 'WBMP images are not supported';
					}
				}
				break;
			default:
				$ermsg = $sourceImageAttr['mime'].' images are not supported';
				break;
		}

		if ( isset ($ermsg) || false === $oImage) {
			return false;
		}

        $dstX = 0;
        $dstY = 0;
		$outImage = imagecreatetruecolor($oSize["Width"], $oSize["Height"]);
        if ($crop&&($oSize["Width"] == $oSize["Height"])) {
            if ($sourceImageWidth > $sourceImageHeight) {
                $dstX = round(($sourceImageWidth-$sourceImageHeight)/2);
                $sourceImageWidth = $sourceImageHeight;
            } elseif ($sourceImageHeight > $sourceImageWidth) {
                $dstY = round(($sourceImageHeight-$sourceImageWidth)/2);
                $sourceImageHeight = $sourceImageWidth;
            }
        }
		imagecopyresampled($outImage, $oImage, 0, 0, $dstX, $dstY, $oSize["Width"], $oSize["Height"], $sourceImageWidth, $sourceImageHeight);

		switch ($sourceImageAttr['mime']) {
			case 'image/gif':
				imagegif ($outImage, $targetFile);
				break;
			case 'image/jpeg':
				imagejpeg($outImage, $targetFile, $quality);
				break;
			case 'image/png':
				imagepng($outImage, $targetFile);
				break;
			case 'image/wbmp':
				imagewbmp($outImage, $targetFile);
				break;
		}

		/*if (file_exists($targetFile)) {
			$oldUmask = umask(0);
			chmod($targetFile, 0755);
			umask($oldUmask);
		}*/

		imageDestroy($oImage);
		imageDestroy($outImage);

		return true;
	}

	private static function setMemoryForImage($imageWidth, $imageHeight, $imageBits, $imageChannels)
	{
		$MB = 1048576;  // number of bytes in 1M
		$K64 = 65536;    // number of bytes in 64K
		$TWEAKFACTOR = 2.4;  // Or whatever works for you
		$memoryNeeded = round( ( $imageWidth * $imageHeight
						* $imageBits
						* $imageChannels / 8
						+ $K64
				) * $TWEAKFACTOR
				) + 3*$MB;

		//ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
		//Default memory limit is 8MB so well stick with that.
		//To find out what yours is, view your php.ini file.
		$memoryLimit = Core_Image::returnBytes(@ini_get('memory_limit'))/$MB;
		if (!$memoryLimit) {
			$memoryLimit = 8;
		}

		$memoryLimitMB = $memoryLimit * $MB;
		if (function_exists('memory_get_usage')) {
			if (memory_get_usage() + $memoryNeeded > $memoryLimitMB) {
				$newLimit = $memoryLimit + ceil( ( memory_get_usage()
								+ $memoryNeeded
								- $memoryLimitMB
						) / $MB
				);
				if (@ini_set( 'memory_limit', $newLimit . 'M' ) === false) {
					return false;
				}
			}
		} else {
			if ($memoryNeeded + 3*$MB > $memoryLimitMB) {
				$newLimit = $memoryLimit + ceil(( 3*$MB
								+ $memoryNeeded
								- $memoryLimitMB
						) / $MB
				);
				if (false === @ini_set( 'memory_limit', $newLimit . 'M' )) {
					return false;
				}
			}
		}

		return true;
	}

	private static function returnBytes($val)
	{
		$val = trim($val);
		if (!$val) {
			return 0;
		}
		$last = strtolower($val[strlen($val)-1]);
		switch ($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

	private static function getRequestedSize($maxWidth, $maxHeight, $actualWidth, $actualHeight, $crop = false)
	{
		$outSize = array ("Width"=>$maxWidth, "Height"=>$maxHeight);
        if (!$crop) {
            $xFactor = (float)$maxWidth/(float)$actualWidth;
            $yFactor = (float)$maxHeight/(float)$actualHeight;

            if ($xFactor != 1 || $yFactor != 1) {
                // Uses the lower Factor to scale the oposite size
                if ($xFactor < $yFactor) {
                    $outSize["Height"] = (int)round($actualHeight*$xFactor);
                }
                else if ($xFactor > $yFactor) {
                    $outSize["Width"] = (int)round($actualWidth*$yFactor);
                }
            }

            $outSize["Height"] = (int)max($outSize["Height"], 1);
            $outSize["Width"] = (int)max($outSize["Width"], 1);
        }
		return $outSize;
	}
}
