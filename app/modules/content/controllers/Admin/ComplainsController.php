<?php
class Content_Admin_ComplainsController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('complains-message-added');
        $this->_msgDelItem = $this->_translate->translate('complains-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/content/complains/');
		$this->_controllerEditUrl = '/admin/module/content/complains/edit';
        $this->_formIniFileName = 'config/forms/admin/complains.ini';
		$this->_dataModel = new Core_Db_Complains;
        $this->_listItems[] = 'type_id';
        $this->_listItems[] = 'item_id';
        $this->_listItems[] = 'profile_id';
        $this->_listItems[] = 'complain';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/content/comlpains');
        $this->view->msgGridListTitle = $this->_translate->translate('complains-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('complains-admin-title');
        $this->view->msgGridColumnType = $this->_translate->translate('complains-grid-column-type');
        $this->view->msgGridColumnItem = $this->_translate->translate('complains-grid-column-item');
        $this->view->msgGridColumnProfile = $this->_translate->translate('complains-grid-column-profile');
        $this->view->msgGridColumnComplain = $this->_translate->translate('complains-grid-column-complain');
        $this->view->msgComplainTypeNews = $this->_translate->translate('complains-type-news');
        $this->view->msgComplainTypeBlogs = $this->_translate->translate('complains-type-blogs');
        $this->view->msgComplainTypeComments = $this->_translate->translate('complains-type-comments');
    }

    public function indexAction()
    {
        $typesIdNameList = array('' => '---', Core_Db_Complains::C_NEWS => $this->view->msgComplainTypeNews, Core_Db_Complains::C_BLOGS => $this->view->msgComplainTypeBlogs, Core_Db_Complains::C_COMMENTS => $this->view->msgComplainTypeComments);
        $typesItemsList = array();
        $this->view->typesList = '';
        foreach ($typesIdNameList as $key=>$value) {
            $typesItemsList[] = "$key:$value";
        }
        $this->view->typesList = implode(';', $typesItemsList);
    }

    public function addAction()
    {
        $this->_redirect('index');
    }

    public function editAction()
    {
        $this->_redirect('index');
    }

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		$result = parent::getListItemValue($itemsRow, $itemName);
		switch($itemName) {
            case 'type_id':
                if ($itemsRow) {
                    switch ($itemsRow->type_id) {
                        case Core_Db_Complains::C_NEWS:
                            $result = $this->view->msgComplainTypeNews;
                            break;
                        case Core_Db_Complains::C_BLOGS:
                            $result = $this->view->msgComplainTypeBlogs;
                            break;
                        case Core_Db_Complains::C_COMMENTS:
                            $result = $this->view->msgComplainTypeComments;
                            break;
                        default:
                            $result = '';
                    }
                }
                break;
            case 'item_id':
                if ($itemsRow) {
                    $complainItem = $itemsRow->getItem();
                    switch ($itemsRow->type_id) {
                        case Core_Db_Complains::C_NEWS:
                        case Core_Db_Complains::C_BLOGS:
                            $result = Core_View_Helper_Link :: makeLink($complainItem->title, $complainItem->link('edit'));
                            break;
                        case Core_Db_Complains::C_COMMENTS:
                            $result = $complainItem->comment;
                            break;
                        default:
                            $result = '';
                    }
                }
                break;
            case 'profile_id':
                if ($itemsRow) {
                    $profile = $itemsRow->getProfile();
                    $result = Core_View_Helper_Link :: makeLink($profile->getFullName(), $profile->link('edit'));
                }
                break;
		}
		return $result;
	}

    public function viewAction()
    {
        $this->_redirect('index');
    }
}