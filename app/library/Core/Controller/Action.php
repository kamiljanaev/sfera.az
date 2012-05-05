<?php
class Core_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        $this->_config = Zend_Registry::get('systemconfig');
		$this->_currentUser = Core_Auth::getInstance()->getIdentity();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    protected function SendMail($emailData = null)
    {
        if($emailData !== null) {
            $sitesettings = Zend_Registry::get('sitesettings');
            $config = Zend_Registry::get('systemconfig');
			$templateName = $emailData['template'];
			unset($emailData['template']);
            if(isset($sitesettings[$templateName.'_from_email'])&&($sitesettings[$templateName.'_from_email'] != '')){
                $from_email = $sitesettings[$templateName.'_from_email'];
            } else {
                $from_email = $config->mail->default->from_email;
            }
            if(isset($sitesettings[$templateName.'_from_name'])&&($sitesettings[$templateName.'_from_name'] != '')){
                $from_name = $sitesettings[$templateName.'_from_name'];
            } else {
				$from_name = $config->mail->default->from_name;
            }
            $subject = isset($sitesettings[$templateName.'_subject'])?$sitesettings[$templateName.'_subject']:'';
			$parametersArray = array(
				'templateFile' => $config->path->templates.$templateName.'.txt',
				'templateAlias' => $templateName,
				'toMail' => $emailData['email'],
				'toName' => '',
				'fromMail' => $from_email,
				'fromName' => $from_name,
				'mailSubject' => $subject,
				'templateSymbol' => '%',
				'aditionalData' => $emailData,
			);
	        $mail = new Core_Mail('UTF-8');
			$mail->sendMail($parametersArray);
        }
    }

    protected function getForm($configFile = '', $actionUrl = '')
    {
        $formConfig = new Zend_Config_Ini($configFile);
        $form = new Zend_Form($formConfig);
        $form->setAction(Core_View_Helper_GetUrl::getCorrectUrl($actionUrl));
        $form->addElementPrefixPath('Core_Validate_',  'Core/Validate/', 'validate');
        return $form;
    }

    protected function checkCurrentProfile() {
        $profilesModel = new Core_Db_Profiles();
        $profileData = null;
        if ($this->_currentUser) {
            $currentProfile = $this->_currentUser->getProfile();
            if (!$currentProfile) {
                $currentProfile = $profilesModel->createRow();
                $currentProfile->user_id = $this->_currentUser->id;
                $currentProfile->save();
            }
            $profileData = $currentProfile;
        }
        return $profileData;
    }

    protected function validateField($field, $validator = null)
    {
        $value = $this->_getParam($field, null);
        $result = array('value' => $value, 'valid' => true, 'message' => '');
        if ($validator instanceof Zend_Validate_Abstract) {
            $valid = $validator->isValid($value);
        } else {
            $valid = strlen($value);
        }
        if (!$valid) {
            $result['valid'] = false;
        }
        return $result;
    }
}
 
