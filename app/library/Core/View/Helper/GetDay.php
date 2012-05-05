<?php
class Core_View_Helper_GetDay extends Core_View_Helper
{
	public function getDay($date)
	{
        return (int)substr($date, 0, 2);
	}
}