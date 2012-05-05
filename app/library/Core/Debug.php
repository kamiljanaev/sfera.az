<?php
class Core_Debug extends Zend_Debug
{
	protected static $time;
	protected static $memory = 0;
	protected static $memoryPeak = 0;
	protected static $timer = null;
	protected static $vars = null;
	protected static $enabled = false;
	protected static $useBackTrace = false;

	public static function setBackTrace($useBackTrace = true)
	{
		self::$useBackTrace = $useBackTrace;
	}

	public static function getBackTrace()
	{
		return self::$useBackTrace;
	}

	public static function setEnabled($enable)
	{
		self::$enabled = (boolean) $enable;
		if (self::$enabled && (!self::$timer or !self::$vars)) {
			self::$timer = new Zend_Wildfire_Plugin_FirePhp_TableMessage('Timer');
			self::$timer->setBuffered(true);
			self::$timer->setHeader(array('Time (sec)', 'Total (sec)', 'Memory (Kb)', 'Total (Kb)', 'Comment', 'File'));
			self::$timer->setOption('includeLineNumbers', false);
		}
	}

	public static function debug()
	{
		if (!self::$enabled) {
			return ;
		}
		$debug = new Zend_Wildfire_Plugin_FirePhp_TableMessage('Debug');
		$debug->setBuffered(true);
		$debug->setHeader(array('Value and BackTrace'));
		$debug->setOption('includeLineNumbers', false);
		foreach (func_get_args() as $var) {
			$debug->addRow(array($var));
		}
		$backtrace = debug_backtrace();
		$arrLines = file($backtrace[0]["file"]);
		$code = $arrLines[($backtrace[0]["line"] - 1)];
		$arrMatches = array();
		preg_match('/\b\s*Core_Debug::debug\s*\(\s*(.+)\s*\);\s*/i', $code, $arrMatches);
		$varName = isset($arrMatches[1])?$arrMatches[1]:'???';
		if (self::getBackTrace()) {
			$trace = array();
			foreach ($backtrace as $rec) {
				if (isset($rec['function'])) {

					$t['call'] = '';
					if (isset($rec['class'])) {
						$t['call'] .= $rec['class'] . $rec['type'] . $rec['function'];
					} else {
						$t['call'] .= $rec['function'];
					}
					$t['call'] .= '(';
					if (sizeof($rec['args'])) {
						foreach ($rec['args'] as $arg) {
							if (is_object($arg)) {
								$t['call'] .= get_class($arg);
							} else {
								$arg  = str_replace("\n", ' ', (string) $arg);
								$t['call'] .= '"' . (strlen($arg) <= 30 ? $arg : substr($arg, 0, 25) . '[...]') . '"';
							}
							$t['call'] .= ', ';
						}
						$t['call'] = substr($t['call'], 0, -2);
					}
					$t['call'] .= ")";
				}
				$t['file'] = @$rec['file'] . ':' . @$rec['line'];
				$trace[] = $t;
			}
			$debug->addRow(array($trace));
			$where = basename($backtrace[0]["file"]) .':'. $backtrace[0]["line"];
		} else {
			$where = '';
		}
		$debug->setLabel("Debug: {$varName} ({$where})");
		Zend_Wildfire_Plugin_FirePhp::getInstance()->send($debug);
	}

	public static function vdie()
	{
		if (!self::$enabled) {
			return ;
		}
		$debug = new StdClass();
		foreach (func_get_args() as $var) {
			$debug->$var = array($var);
		}
		$backtrace = debug_backtrace();
		$arrLines = file($backtrace[0]["file"]);
		$code = $arrLines[($backtrace[0]["line"] - 1)];
		$arrMatches = array();
		preg_match('/\b\s*Core_Debug::debug\s*\(\s*(.+)\s*\);\s*/i', $code, $arrMatches);
		$varName = isset($arrMatches[1])?$arrMatches[1]:'???';
		if (self::getBackTrace()) {
			$trace = array();
			foreach ($backtrace as $rec) {
				if (isset($rec['function'])) {
					$t['call'] = '';
					if (isset($rec['class'])) {
						$t['call'] .= $rec['class'] . $rec['type'] . $rec['function'];
					} else {
						$t['call'] .= $rec['function'];
					}
					$t['call'] .= '(';
					if (sizeof($rec['args'])) {
						foreach ($rec['args'] as $arg) {
							if (is_object($arg)) {
								$t['call'] .= get_class($arg);
							} else {
								$arg  = str_replace("\n", ' ', (string) $arg);
								$t['call'] .= '"' . (strlen($arg) <= 30 ? $arg : substr($arg, 0, 25) . '[...]') . '"';
							}
							$t['call'] .= ', ';
						}
						$t['call'] = substr($t['call'], 0, -2);
					}
					$t['call'] .= ")";
				}
				$t['file'] = @$rec['file'] . ':' . @$rec['line'];
				$trace[] = $t;
			}
			$debug->trace = array($trace);
			$where = basename($backtrace[0]["file"]) .':'. $backtrace[0]["line"];
		} else {
			$where = '';
		}
		self::dump($debug);
		die();
	}

	public static function getGenerateTime ($aComment = "")
	{
		if (!self::$enabled) {
			return ;
		}
		list ($msec, $sec)     = explode(chr(32), microtime());
		list ($mTotal, $mSec)  = self::getMemoryUsage();
		if (! isset(self::$time)) {
			self::$time["start"] = $sec + $msec;
			self::$time["section"] = $sec + $msec;
			self::$timer->addRow(array(
					sprintf("%01.4f", 0),
					sprintf("%01.4f", 0),
					$mSec,$mTotal,
					$aComment,
					basename(@$back[0]["file"]) . ':' . @$back[0]["line"],
			));
		} else {
			$start = self::$time["section"];
			self::$time["section"] = $sec + $msec;
			self::$timer->addRow(array(
					sprintf("%01.4f", round(self::$time["section"] - $start, 4)),
					sprintf("%01.4f", round(self::$time["section"] - self::$time["start"], 4)),
					$mSec,$mTotal,
					$aComment,
					basename(@$back[0]["file"]) . ':' . @$back[0]["line"],
			));
		}
		self::updateMessageLabel();
		Zend_Wildfire_Plugin_FirePhp::getInstance()->send(self::$timer);
	}

	protected static function getMemoryUsage ()
	{
		if (! function_exists('memory_get_usage')) {
			if (substr(PHP_OS, 0, 3) == 'WIN') {
				$output = array();
				exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
				$memory = preg_replace('/[\D]/', '', $output[5]) * 1024;
			} else {
				$pid = getmypid();
				exec("ps -eo%mem,rss,pid | grep $pid", $output);
				$output = explode("  ", $output[0]);
				$memory = @$output[1] * 1024;
			}
		} else {
			$memory = memory_get_usage();
		}
		$memorySection = $memory - self::$memory;
		$memoryTotal   = sprintf("%08s", $memory);
		$memorySection = sprintf("%08s", $memorySection);
		self::$memory = $memory;
		return array($memoryTotal, $memorySection);
	}

	protected static function getMemoryPeak ()
	{
		if (function_exists('memory_get_peak_usage')) {
			self::$memoryPeak = sprintf("%07s", memory_get_peak_usage());
		}
		return self::$memoryPeak;
	}

	protected static function updateMessageLabel()
	{
		self::$timer->setLabel(sprintf('Timer (%s sec @  %s Kb)', round(self::$time["section"] - self::$time["start"], 4), number_format(self::$memory / 1024, 2, '.', ' ') ));
	}
}