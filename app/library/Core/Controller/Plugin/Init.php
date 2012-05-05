<?php
class Core_Controller_Plugin_Init extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		$systemconfig = Zend_Registry::get('systemconfig');
		$sitesettings = Zend_Registry::get('sitesettings');
		$viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer');
		if (is_null($viewRenderer->view)) {
			$viewRenderer->init();
		}
		$this->_view = $viewRenderer->view;
		$prefix = 'Core_View_Helper';
		$dirHelper = 'Core/View/Helper';
		$dirScript = $systemconfig->path->views;
		$this->_view->addHelperPath($dirHelper, $prefix);
		$this->_view->setScriptPath(array($dirScript));
		$this->_view->baseUrl = $request->getBaseUrl();
		$this->_view->doctype('XHTML1_TRANSITIONAL');
		$this->_view->headMeta()->prependHttpEquiv('Content-Type', 'text/html; charset=' . Zend_Registry::get('systemconfig')->common->charset);
		$siteTitle = array_key_exists('site_title', $sitesettings) ? $sitesettings['site_title'] : $systemconfig->site->title;
		$siteTitleSeparator = array_key_exists('site_title_separator', $sitesettings) ? $sitesettings['site_title_separator'] : $systemconfig->site->title_separator;
		$siteKeywords = array_key_exists('site_keywords', $sitesettings) ? $sitesettings['site_keywords'] : '';
		$siteDescription = array_key_exists('site_title_separator', $sitesettings) ? $sitesettings['site_descriptions'] : '';
		$this->_view->headTitle($siteTitle);
		$this->_view->headTitle()->setSeparator($siteTitleSeparator);
		$this->_view->headMeta()->setName('keywords', $siteKeywords);
		$this->_view->headMeta()->setName('description', $siteDescription);
	}
}