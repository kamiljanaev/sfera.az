<?php

class Files_ElfinderController extends Zend_Controller_Action
{
	public function connectorAction()
	{
		require_once('library/elFinder/elFinder.class.php');
		$opts = $GLOBALS['systemconfig']['elFinder'];
		$fm = new elFinder($opts);
		return $fm->run();
	}
}