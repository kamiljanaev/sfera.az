<?php
class Core_Db_Profiler_Firebug extends Zend_Db_Profiler_Firebug
{
	private
		$useBackTrace = false;

	public function setBackTrace($useBackTrace = true)
	{
		$this->useBackTrace = $useBackTrace;
	}

	public function getBackTrace()
	{
		return $this->useBackTrace;
	}

	public function queryStart($queryText, $queryType = null)
	{
		$result = parent::queryStart($queryText, $queryType);
		if ($this->getBackTrace()) {
			$backtrace = debug_backtrace();
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
			$this->getLastQueryProfile()->bindParam('trace', $trace);
		}
		return $result;
	}
}