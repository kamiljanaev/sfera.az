<?php
class Profile_Admin_DocumentController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
		$this->_controllerUrl = $this->view->getUrl('/admin/module/profile/document/');
		$this->_controllerEditUrl = '/admin/module/profile/document/edit';
		$this->_dataModel = new Core_Db_Documents;
        $this->_listItems[] = 'user_id';
		$this->_listItems[] = 'status';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/profile/document');
        $this->view->msgGridListTitle = $this->_translate->translate('document-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('document-admin-title');
        $this->view->msgAdminViewTitle = $this->_translate->translate('document-admin-view-title');
        $this->view->msgGridColumnUser = $this->_translate->translate('document-grid-column-user');
        $this->view->msgGridColumnName = $this->_translate->translate('document-grid-column-name');
        $this->view->msgGridColumnStatus = $this->_translate->translate('document-grid-column-status');
        $this->view->msgItemNew = $this->_translate->translate('document-message-item-new');
        $this->view->msgItemNotNew = $this->_translate->translate('document-message-item-not-new');
    }

    public function indexAction()
    {
        parent::indexAction();
        $usersModel = new Core_Db_Users();
        $authorsIdEmailList = array('' => '---')+$usersModel->getIdEmailList();
        $authorsItemsList = array();
        $this->view->authorsList = '';
        foreach ($authorsIdEmailList as $key=>$value) {
            $authorsItemsList[] = "$key:$value";
        }
        $this->view->usersList = implode(';', $authorsItemsList);

        $newIdNameList = array('' => '---', '1' => $this->view->msgItemNew, '0' => $this->view->msgItemNotNew);
        $newItemsList = array();
        $this->view->newList = '';
        foreach ($newIdNameList as $key=>$value) {
            $newItemsList[] = "$key:$value";
        }
        $this->view->newList = implode(';', $newItemsList);
    }

    public function addAction()
    {
        return;
    }

    public function editAction()
    {
        return;
    }

    protected function getForm($type = 'edit')
    {
        return null;
    }

	protected function editFindDataRow()
	{
		return $this->loadDataRow($this->_params->id);
	}

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		$result = parent::getListItemValue($itemsRow, $itemName);
		switch($itemName) {
            case 'status':
                if ($result) {
                    $result = $this->view->msgItemNew;
                } else {
                    $result = $this->view->msgItemNotNew;
                }
                break;
            case 'user_id':
                if ($itemsRow) {
                    $user = $itemsRow->getUser();
                    $profile = $itemsRow->getProfile();
                    $result = $user->email . ' (' . $profile->getFullName() . ')';
                }
                break;
		}
		return $result;
	}

    public function viewAction()
    {
        if (null === $dataRow = $this->getDataRow($this->_params->id)) {
            return;
        }
        $this->view->dataRow = $dataRow;
        $this->view->controller = $this;
    }

    public function approveAction()
    {
        $this->_helper->_layout->disableLayout();
        $response = $this->getResponseArray();
        try {
            $dataRow = $this->getDataRow($this->_params->id);
            if ($dataRow) {
                $dataRow->status = 0;
                $dataRow->save();
                $currentProfile = $dataRow->getProfile();
                if ($currentProfile) {
                    $currentProfile->is_real = 1;
                    $currentProfile->save();
                }
                $response->setCode(Core_Response::RESPONSE_OK);
                $response->addMessage($this->_translate->translate('document-message-item-approved'));
            } else {
                $response->setCode(Core_Response::RESPONSE_ERROR);
                $response->addMessage($this->_translate->translate('document-message-item-not-approved'));
            }
        } catch (Zend_Exception $ex) {
            $response->setCode(Core_Response::RESPONSE_ERROR);
            $response->addMessage($ex->getMessage());
        }
        $this->_helper->json->sendJson($response);
    }

    public function cancelAction()
    {
        $this->_helper->_layout->disableLayout();
        $response = $this->getResponseArray();
        try {
            $dataRow = $this->getDataRow($this->_params->id);
            if ($dataRow) {
                $dataRow->status = 0;
                $dataRow->save();
                $response->setCode(Core_Response::RESPONSE_OK);
                $response->addMessage($this->_translate->translate('document-message-item-canceled'));
            } else {
                $response->setCode(Core_Response::RESPONSE_ERROR);
                $response->addMessage($this->_translate->translate('document-message-item-not-canceled'));
            }
        } catch (Zend_Exception $ex) {
            $response->setCode(Core_Response::RESPONSE_ERROR);
            $response->addMessage($ex->getMessage());
        }
        $this->_helper->json->sendJson($response);
    }
}