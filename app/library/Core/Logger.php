<?php
class Core_Logger
{
	public static function init()
	{
		$systemconfig = Zend_Registry::get('systemconfig');
		if ($systemconfig->loging->on) {
			$logger = new Zend_Log();
			$writer = new Zend_Log_Writer_Stream($systemconfig->loging->logFile);
			$logger->addWriter($writer);
			Zend_Registry::set('logger',$logger);
			$logger->log('--------------------------------------------------------------------------------------------------------------------------', Zend_Log::INFO);
			return true;
		}
		return false;
	}

	public static function log($message = '', $type = Zend_Log::INFO)
	{
		if (!Zend_Registry::isRegistered('logger')) {
			return false;
		}
		list ($msec, $sec) = explode(chr(32), microtime());
		$time = sprintf("%01.4f", round($sec+$msec, 4));
/*		$auth = Core_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$userRow = $auth->getIdentity();
			$changerId = $userRow->id.' - '.$userRow->login;
		} else {
			$changerId = 'guest';
		}*/
		$address = (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
		$message = $time.'   ;   '.$address.'   ;   '.$message;
		$logger = Zend_Registry::get('logger');
		$logger->log($message, $type);
	}
}