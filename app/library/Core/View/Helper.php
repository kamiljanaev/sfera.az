<?php
class Core_View_Helper extends Zend_View_Helper_Abstract
{
	protected
		$_view;

	public function setView(Zend_View_Interface $view)
	{
		$this->_view = $view;
	}
}