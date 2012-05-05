<?php
class Core_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
        $translateFilePath = 'config/lang/ru.ini';
        $oTranslate = new Zend_Translate('ini', $translateFilePath, 'ru');
        Zend_Form::setDefaultTranslator($oTranslate);
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->view->locale = 'ru';
        $viewRenderer->view->lang = 'ru';
        $viewRenderer->view->translate = $oTranslate;
	}
}