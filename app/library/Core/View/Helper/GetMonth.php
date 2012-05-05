<?php
class Core_View_Helper_GetMonth extends Core_View_Helper
{
	public function getMonth($date)
	{
        return (int)substr($date, 3, 2);
	}
}