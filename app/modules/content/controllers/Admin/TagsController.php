<?php
class Content_Admin_TagsController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('tags-message-added');
        $this->_msgDelItem = $this->_translate->translate('tags-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/content/tags/');
		$this->_controllerEditUrl = '/admin/module/content/tags/edit';
        $this->_formIniFileName = 'config/forms/admin/tags.ini';
		$this->_dataModel = new Core_Db_Content_Tags;
        $this->_listItems[] = 'title';
        $this->_listItems[] = 'logo';
        $this->_listItems[] = 'type_id';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/content/tags');
        $this->view->msgGridListTitle = $this->_translate->translate('tags-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('tags-admin-title');
        $this->view->msgAdminViewTitle = $this->_translate->translate('tags-admin-view-title');
        $this->view->msgGridColumnTitle = $this->_translate->translate('tags-grid-column-title');
        $this->view->msgGridColumnLogo = $this->_translate->translate('tags-grid-column-logo');
        $this->view->msgGridColumnType = $this->_translate->translate('tags-grid-column-type-id');
        $this->view->msgTagTypeNews = $this->_translate->translate('tags-type-news');
        $this->view->msgTagTypeBlogs = $this->_translate->translate('tags-type-blogs');
    }

    public function indexAction()
    {
        $typesIdNameList = array('' => '---', Core_Db_Content_Tags::TAG_TYPE_NEWS => $this->view->msgTagTypeNews, Core_Db_Content_Tags::TAG_TYPE_BLOGS => $this->view->msgTagTypeBlogs);
        $typesItemsList = array();
        $this->view->typesList = '';
        foreach ($typesIdNameList as $key=>$value) {
            $typesItemsList[] = "$key:$value";
        }
        $this->view->typesList = implode(';', $typesItemsList);
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
            case 'title':
                if ($itemsRow) {
                    $result = Core_View_Helper_Link :: makeLink($itemsRow->title, $itemsRow->link('edit'));
                }
                break;
            case 'type_id':
                if ($result == Core_Db_Content_Tags::TAG_TYPE_NEWS) {
                    $result = $this->view->msgTagTypeNews;
                }
                if ($result == Core_Db_Content_Tags::TAG_TYPE_BLOGS) {
                    $result = $this->view->msgTagTypeBlogs;
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