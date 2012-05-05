<?php
class Core_View_Helper_Date extends Core_View_Helper
{
	public function date(Zend_Date $date)
	{
		return $date->toString(Zend_Registry::get('DateFormat'));
	}
}