<?php
class IndexController extends Core_Controller_Action
{
	public function indexAction()
	{
        $this->_helper->layout()->setLayout('layout');
	}

    public function pingAction()
    {
        $this->_helper->_layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_currentUser) {
            $this->_currentUser->setOnline();
        }
    }

    public function userGrabberAction()
    {
        $this->_helper->_layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $usersModel = new Core_Db_Users();
        $usersModel->grabOnlineUsers();
    }
}