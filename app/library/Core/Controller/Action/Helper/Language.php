<?php
class Core_Controller_Action_Helper_Language extends Zend_Controller_Action_Helper_Abstract
{
    protected $_sDefaultLanguage;
    protected $_aLocales;
    protected $_sLanguagesDirectoryPath;
    
    public function __construct(array $aLocales, $sLanguagesDirectoryPath)
    {
        $this->_sLanguagesDirectoryPath = $sLanguagesDirectoryPath;
        $this->_aLocales = $aLocales;
        $this->_sDefaultLanguage = key($aLocales);
    }

    public function init()
    {
        $sLang = $this->getRequest()->getParam('lang');
        if(! array_key_exists($sLang, $this->_aLocales)) {
            $sLang = $this->_sDefaultLanguage;
        }
        $sLanguageFilePath = $this->_sLanguagesDirectoryPath . 'config/lang/' . $sLang . '.ini';
        if(! file_exists($sLanguageFilePath)) {
            $sLanguageFilePath = $this->_sLanguagesDirectoryPath . 'config/lang/' . $this->_sDefaultLanguage . '.ini';
            $sLang = $this->_sDefaultLanguage;
        }
        $sLocale = $this->_aLocales[$sLang];
        $oTranslate = new Zend_Translate('ini', $sLanguageFilePath, $sLang);
        Zend_Registry::set('Zend_Translate', $oTranslate);

        Zend_Form::setDefaultTranslator($oTranslate);
        $this->_actionController->_locale = $sLocale;
        $this->_actionController->_lang = $sLang;
        $this->_actionController->_translate = $oTranslate;
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->view->locale = $sLocale;
        $viewRenderer->view->lang = $sLang;
        $viewRenderer->view->translate = $oTranslate;
    }
}