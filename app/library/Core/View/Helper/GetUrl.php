<?php
class Core_View_Helper_GetUrl extends Core_View_Helper
{
	protected static $baseUrl;

	public function getUrl($url = null, $isUri = false)
	{
		return self::getCorrectUrl($url, $isUri);
	}

	public static function getCorrectUrl($url = null, $isUri = false)
	{
		$front = Zend_Controller_Front::getInstance();
		$request = $front->getRequest();

		if ($isUri) {
			$host = $_SERVER['HTTP_HOST'];
			$proto = (empty($_SERVER['HTTPS'])) ? 'http' : 'https';
			$port = $_SERVER['SERVER_PORT'];
			$uri = $proto . '://' . $host;
		} else {
			$uri = '';
		}

		if (self::$baseUrl === null) {
			$root = '/' . trim($request->getBaseUrl(), '/');
			if ($root == '/') {
				$root = '';
			}
			self::$baseUrl = $root . '/';
		}

		if ($url === null) {
			$return = '';
		} else {
			$return = ltrim($url,"/");
		}
		$params = array();
		return $uri . self::$baseUrl . $return;
	}
}