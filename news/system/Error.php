<?php
class Error
{
	public static function catchException(Exception $exception)
	{
		$message = $exception->getMessage();
		$trace = $exception->getTraceAsString();
		$str = 'ERROR: ' . $message . "\n" . $trace;
		$systemconfig = Zend_Registry::get('systemconfig');
		if ($systemconfig->debug->on) {
			Zend_Debug::dump($str);
		} else {
			die('System error! Please try later');
		}
	}
}