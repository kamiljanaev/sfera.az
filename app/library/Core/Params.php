<?php
class Core_Params
{
	public function __construct()
	{
		$args = func_get_args();
		foreach ($args as $arg) {
			foreach ($arg as $param_name=>$param_value) {
				$this->$param_name = $param_value;
			}
		}
	}

	public function toArray()
	{
		return (array)$this;
	}

	public function __get($name)
	{
		if (!isset($this->$name)) {
			return null;
		} else {
			$this->$name;
		}
	}
}