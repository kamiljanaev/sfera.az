<?php
class Profile_Admin_AwardsController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('awards-message-added');
        $this->_msgDelItem = $this->_translate->translate('awards-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/profile/awards/');
		$this->_controllerEditUrl = '/admin/module/profile/awards/edit';
        $this->_formIniFileName = 'config/forms/admin/awards.ini';
		$this->_dataModel = new Core_Db_Awards;
        $this->_listItems[] = 'title';
        $this->_listItems[] = 'logo';
		$this->_listItems[] = 'status';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/profile/awards');
        $this->view->msgGridListTitle = $this->_translate->translate('awards-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('awards-admin-title');
        $this->view->msgAdminViewTitle = $this->_translate->translate('awards-admin-view-title');
        $this->view->msgGridColumnTitle = $this->_translate->translate('awards-grid-column-title');
        $this->view->msgGridColumnLogo = $this->_translate->translate('awards-grid-column-logo');
        $this->view->msgGridColumnStatus = $this->_translate->translate('awards-grid-column-status');
        $this->view->msgStatusActive = $this->_translate->translate('awards-status-active');
        $this->view->msgStatusNotActive = $this->_translate->translate('awards-status-not-active');
    }

    public function indexAction()
    {
        $statusIdNameList = array('' => '---', '1' => $this->view->msgStatusActive, '0' => $this->view->msgStatusNotActive);
        $statusItemsList = array();
        $this->view->statusList = '';
        foreach ($statusIdNameList as $key=>$value) {
            $statusItemsList[] = "$key:$value";
        }
        $this->view->statusList = implode(';', $statusItemsList);
    }

    public function addAction()
    {
        $this->_redirect('edit');
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
                    $result = $this->view->msgStatusActive;
                } else {
                    $result = $this->view->msgStatusNotActive;
                }
                break;
            case 'logo':
                if ($itemsRow) {
                    $result = Core_Image :: getImageHtmlCode($itemsRow->logo, Core_Image :: LISTS);
                }
                break;
		}
		return $result;
	}

    public function viewAction()
    {
        return;
    }
}