<?php
class Core_View_Helper_FormatDate extends Core_View_Helper
{
	public function formatDate($date, $format = 'dd MMMM yyyy HH:mm')
	{
        if ($date instanceof Zend_Date) {
            return $date->toString($format);
        } else {
            return $date;
        }
	}
}