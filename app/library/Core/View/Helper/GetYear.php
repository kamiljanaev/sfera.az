<?php
class Core_View_Helper_GetYear extends Core_View_Helper
{
	public function getYear($date)
	{
        return (int)substr($date, 6, 4);
	}
}