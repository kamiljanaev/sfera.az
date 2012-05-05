<?php
class Core_Vdie
{
	protected function dump_array($array)
	{
        $_str = "<table bgcolor = '%s'><tr><td>%s</td></tr></table><br>";
		if (is_array($array)) {
			$size = count($array);
			$string = "";
			if ($size) {
				$string .= "{ <br>";
				foreach ($array as $a => $b) {
					$string .= "&nbsp;&nbsp;&nbsp;$a = '$b'<br>";
				}
				$string .= " }<br>";
			}
			$r = sprintf($_str, '#DACE0B', $string);
			return $r;
		} else {
			return $array;
		}
	}

	protected function dump($res)
	{
		$_print = $res;
		if (is_array($res)) {
			$_print = dump_array($res);
		}
		die(var_dump($_print));
	}

	public static function _()
	{
		$vars = func_get_args();
		$is_die = false;
		foreach ($vars as $var) {
			$funct = isset($funct) ? $funct : (is_scalar($var) || is_null($var) ? 'var_dump' : 'print_r');
			print("<pre>");
			$funct($var);
			print("</pre>");
		}
		exit();
	}

	public static function __()
	{
		$vars = func_get_args();
		$is_die = false;
		foreach ($vars as $var) {
			$funct = isset($funct) ? $funct : (is_scalar($var) || is_null($var) ? 'var_dump' : 'print_r');
			print("<pre>");
			$funct($var);
			print("</pre>");
		}
	}
}