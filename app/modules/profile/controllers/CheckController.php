<?php
class Profile_CheckController extends Core_Controller_Action
{
    protected
        $validator = null;
    
    protected function validate() {
        $this->_helper->_layout->disableLayout();
        $login = $this->_getParam('value', null);
        $this->view->result = "false";
        if ($this->validator->isValid($login)) {
            $this->view->result = "true";
        }
        $this->render('result');
    }
    
    public function loginAction()
    {
        $exclude = $this->_getParam('exclude', null);
        $this->validator = new Core_Validate_Users_Login_NoRecordExists($exclude);
        $this->validate();
    }

    public function emailAction()
    {
        $exclude = $this->_getParam('exclude', null);
        $this->validator = new Core_Validate_Users_Email_NoRecordExists($exclude);
        $this->validate();
    }
}