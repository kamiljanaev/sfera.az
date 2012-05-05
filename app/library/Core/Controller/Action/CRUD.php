<?php
abstract class Core_Controller_Action_CRUD extends Zend_Controller_Action
{
	protected
		$_parentKeyName = 'id',
		$_listItems = array(),
		$_controllerUrl = '',
		$_controllerEditUrl = '',
		$_msgAddItem = 'core-abstract-item-added',
		$_msgDelItem = 'core-abstract-item-deleted',
		$_msgNotDelItem = 'core-abstract-item-not-deleted',
		$_params,
		$_formIniFileName,
		$_dataModel,
		$_session;

	public function init()
	{
		$this->_msgAddItem = 'Элемент добавлен';
		$this->_msgDelItem = 'Элемент удален';
		$this->_msgNotDelItem = 'Элемент не удален';
		$this->view->response = $this->getResponseArray();
		$this->_params = $this->getParams();
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('view','html')
				->setAutoDisableLayout(true)
				->setAutoJsonSerialization(false)->initContext();
		$this->_session = new Zend_Session_Namespace(__CLASS__);
	}

    protected function getMessages()
    {
        $this->view->msgLabelEdit = $this->_translate->translate('label-edit');
        $this->view->msgLabelDelete = $this->_translate->translate('label-delete');
        $this->view->msgLabelView = $this->_translate->translate('label-view');
        $this->view->msgButtonAdd = $this->_translate->translate('button-add');
        $this->view->msgButtonBack = $this->_translate->translate('button-back');
        $this->view->msgButtonSave = $this->_translate->translate('button-save');
        $this->view->msgButtonDelete = $this->_translate->translate('button-delete');
        $this->view->msgGridBaseUrl = $this->_controllerUrl;
        $this->view->msgGridColumnAction = $this->_translate->translate('grid-column-action');
        $this->view->msgConfirmDeleteItem = $this->_translate->translate('message-confirm-del-item');
        $this->view->msgValidationEmptyValue = $this->_translate->translate('validation-empty-value');
        $this->view->msgValidationShortValue = $this->_translate->translate('validation-short-value');
        $this->view->msgValidationIncorrectEmail = $this->_translate->translate('validation-incorrect-email');
        $this->view->msgValidationShortPwd = $this->_translate->translate('validation-short-pwd');
        $this->view->msgValidationEmptyPwd = $this->_translate->translate('validation-empty-pwd');
        $this->view->msgValidationPwdConfirmError = $this->_translate->translate('validation-pwd-confirm-error');
        $this->view->msgValidationIncorrectDigits = $this->_translate->translate('validation-incorrect-digits');

        $this->view->msgItemIsUsed = $this->_translate->translate('message-item-used');
    }

	public function indexAction()
	{
		$this->view->form = $this->getForm('add');
	}

	public function listAction()
	{
		$this->_helper->_layout->disableLayout();
		if ($this->_params->sortname) {
			$this->_dataModel->setDefaultOrderField($this->_params->sortname);
			if ($this->_params->sortorder) {
				$this->_dataModel->setDefaultOrderSequence($this->_params->sortorder);
			} else {
				$this->_dataModel->setDefaultOrderSequence('asc');
			}
		}
		$total = $this->_dataModel->getListCount($this->_params->searchField, $this->_params->searchOper, $this->_params->searchString);
		$total_pages = ceil($total/$this->_params->rp);
		$list = $this->_dataModel->getList($this->_params->page, $this->_params->rp, $this->_params->searchField, $this->_params->searchOper, $this->_params->searchString, $this->_params->sortname, $this->_params->sortorder);
		$rows = array();
		$parentKeyName = $this->_parentKeyName;
		foreach ($list as $itemsRow) {
			if ($this->onListIteration($itemsRow)) {
				$cell = array();
				$cell = $this->getListItemsValues($itemsRow);
				$cell[] = '';
				$rows[] = array(
						'id' => $itemsRow->$parentKeyName,
						'cell' => $cell
				);
			};
		}
		$json_result = array(
				'page' => $this->_params->page,
				'total' => $total_pages,
				'records' => $total,
				'rows' => $rows
		);
		$this->_helper->json->sendJson($json_result);
	}

	public function addAction()
	{
		$this->_helper->_layout->disableLayout();
		$response = $this->getResponseArray();
		$this->view->form = $this->getForm('add');
		if ($this->_request->isPost()) {
			if (!$this->view->form->isValid($_POST)) {
				$responseMsg = $this->view->form->getMessages();
				foreach ($responseMsg as $key=>&$messages) {
					$messages = join('. ', $messages);
				}
				$response->setCode(Core_Response::RESPONSE_ERROR);
				$response->addMessage($responseMsg);
			} else {
				try {
					$this->beforeAddData();
					$dataRow = $this->loadDataRow();
					$this->beforeAddRow($dataRow);
					foreach ($this->view->form->getElements() as $element) {
						if ($this->checkRowItem($element)) {
							$elName = $element->getName();
							if (isset($dataRow->$elName)) {
								$onAddRowResult = $this->onAddRowItem($elName, $element->getValue());
								if ($onAddRowResult['result']) {
									$this->addRowItem($dataRow, $onAddRowResult['name'], $onAddRowResult['value']);
								}
							}
						}
					}
					$this->afterAddRow($dataRow);
					$this->saveDataRow($dataRow);
					$this->afterAddData($dataRow);
					$response->setCode(Core_Response::RESPONSE_OK);
					$response->addMessage($this->_msgAddItem);
				}catch (Exception $ex) {
					$this->exceptionAddData($dataRow);
					$response->setCode(Core_Response::RESPONSE_ERROR);
					$response->addMessage($ex->getMessage());
				}
			}
		}
		$this->_helper->json->sendJson($response);
	}

	public function deleteAction()
	{
		$this->_helper->_layout->disableLayout();
		$response = $this->getResponseArray();
		try {
			if ($this->onDelRow($this->_params->id)) {
				$this->beforeDeleteData();
				$this->_dataModel->removeData($this->_params->id);
				$this->afterDeleteData();
				$response->setCode(Core_Response::RESPONSE_OK);
				$response->addMessage($this->_msgDelItem);
			} else {
				$response->setCode(Core_Response::RESPONSE_ERROR);
				$response->addMessage($this->_msgNotDelItem);
			}
		}catch (Zend_Exception $ex) {
			$this->exceptionDeleteData();
			$response->setCode(Core_Response::RESPONSE_ERROR);
			$response->addMessage($ex->getMessage());
		}
		$this->_helper->json->sendJson($response);
	}

	public function editAction()
	{
		$dataRow = $this->editFindDataRow();
		$this->view->itemRow = $dataRow;
		$this->view->form = $this->getForm();
		if ($this->_request->isPost()) {
			$isCancel = $this->_request->getParam('cancel');
			if (isset($isCancel)) {
				$this->redirectToIndex();
			} else {
				if ($this->view->form->isValid($_POST)) {
					try {
						$this->beforeEditData();
						$this->beforeEditRow($dataRow);
						foreach ($this->view->form->getElements() as $element) {
							if ($this->checkRowItem($element)) {
								$elName = $element->getName();
								if (isset($dataRow->$elName)) {
									$onEditRowResult = $this->onEditRowItem($elName, $element->getValue());
									if ($onEditRowResult['result']) {
										$this->editRowItem($dataRow, $onEditRowResult['name'], $onEditRowResult['value']);
									}
								}
							}
						}
						$this->afterEditRow($dataRow);
						$this->saveDataRow($dataRow);
						$this->afterEditData($dataRow);
						$this->redirectToIndex();
					}catch (Exception $ex) {
						$this->exceptionEditData($dataRow);
					}
				}
			}
		} else {
			$this->defineReturnPath();
			$this->beforePrepareEdit($dataRow);
			foreach ($dataRow->toArray() as $property => $value) {
				$el = $this->view->form->getElement($property);
				if ($this->checkPrepareEditItem($property, $el)) {
					$el->setValue($value);
				}
			}
			$this->afterPrepareEdit($dataRow);
		}
	}

	public function defineReturnPath()
	{
		if (array_key_exists("HTTP_REFERER", $_SERVER)) {
			$this->_session->returnPath = $_SERVER["HTTP_REFERER"];
		}
		$this->view->returnUrl = $this->getReturnPath();
	}

	public function getReturnPath()
	{
		return ($this->_session->returnPath != null)?$this->_session->returnPath:$this->_controllerUrl;
	}

	public function viewAction()
	{
		if (null === $dataRow = $this->getDataRow($this->_params->id)) {
			return;
		}
		$this->view->dataRow = $dataRow;
	}

	protected function editFindDataRow()
	{
		if (null === $dataRow = $this->getDataRow($this->_params->id)) {
			$this->redirectToIndex();
		}
		return $dataRow;
	}

	protected function redirectToIndex()
	{
		if (strlen($this->_controllerUrl)) {
			$this->_redirect($this->_controllerUrl);
		} else {
			$this->_redirect($this->getReturnPath());
		}
	}

	protected function formLoadFromIni($iniPath = null)
	{
		$config = new Zend_Config_Ini($iniPath);
		return new Zend_Form($config);
	}

	protected function formActionSet($form = null, $type = 'edit')
	{
		if (!($form instanceof Zend_Form)) {
			throw Core_Controller_Action_CRUD_Exception_InstanceNotForm();
		}
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl($this->_controllerEditUrl.'/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl($this->_controllerUrl));
		}
		return $form;
	}

	protected function formFillElements($form = null)
	{
		if (!($form instanceof Zend_Form)) {
			throw Core_Controller_Action_CRUD_Exception_InstanceNotForm();
		}
		return $form;
	}

	protected function formSetPrefixPath($form = null)
	{
		if (!($form instanceof Zend_Form)) {
			throw Core_Controller_Action_CRUD_Exception_InstanceNotForm();
		}
		$form->addElementPrefixPath('Core_Validate_',  'Core/Validate/', 'validate');
		return $form;
	}

	protected function formSetDecorators($form = null)
	{
		if (!($form instanceof Zend_Form)) {
			throw Core_Controller_Action_CRUD_Exception_InstanceNotForm();
		}
		$form->removeDecorator('HtmlTag');
		$form->setDisplayGroupDecorators(array (
				'FormElements',
				array ('HtmlTag', array ('tag' => 'ul', 'class' => 'form wide')),
		));
		$displayGroups = $form->getDisplayGroups();
		foreach ($displayGroups as $displayGroup) {
			foreach ($displayGroup as $element) {
				$element->setDecorators(array(
						'ViewHelper',
						array (array('elUL'=>'Errors'), array ('class' => 'error')),
						array ('HtmlTag', array ('tag' => 'span', 'class' => 'field')),
						array ('Label', array('escape' => false)),
						array (array('elLI'=>'HtmlTag'), array ('tag' => 'li', 'class' => 'panel')),
				));
			}
		}
		return $form;
	}

	protected function getForm($type = 'edit')
	{
		$form = $this->formLoadFromIni($this->_formIniFileName);
		$form = $this->formActionSet($form, $type);
		$form = $this->formSetPrefixPath($form);
		$form = $this->formFillElements($form);
		$form = $this->formSetDecorators($form);
		return $form;
	}

	protected function getListItemsValues($itemsRow)
	{
		$row = array();
		foreach ($this->_listItems as $itemName) {
			$row[] = $this->getListItemValue($itemsRow, $itemName);
		}
		return $row;
	}

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		if (isset($itemsRow->$itemName)) {
			$itemValue = $itemsRow->$itemName;
			if ($itemValue instanceof Zend_Date) {
				return $itemValue->toString(Zend_Registry::get('DateFormat'));
			}
			return $itemValue;
		}
		return null;
	}

	protected function onListIteration($dataRow = null)
	{
		return true;
	}

	protected function addRowItem($dataRow = null, $itemName = '', $itemValue = null)
	{
		if ( ($dataRow instanceof Zend_Db_Table_Row_Abstract) && strlen($itemName) ) {
			$dataRow->__set($itemName, $itemValue);
		}
	}

	protected function editRowItem($dataRow = null, $itemName = '', $itemValue = null)
	{
		if ( ($dataRow instanceof Zend_Db_Table_Row_Abstract) && strlen($itemName) ) {
			$dataRow->__set($itemName, $itemValue);
		}
	}

	protected function onAddRowItem($itemName = null, $itemValue = null)
	{
		return array('name' => $itemName,'value' => $itemValue, 'result' => true);
	}

	protected function onEditRowItem($itemName = null, $itemValue = null)
	{
		return array('name' => $itemName,'value' => $itemValue, 'result' => true);
	}

	protected function onDelRow($rowId = null)
	{
		return true;
	}

	protected function loadDataRow($rowId = null)
	{
		if (!$rowId) {
			return $this->_dataModel->createRow();
		} else {
            return $dataRow;
		}
	}

	protected function beforeAddData()
	{
		return true;
	}

	protected function afterAddData($dataRow = null)
	{
		return true;
	}

	protected function exceptionAddData($dataRow = null)
	{
		return true;
	}

	protected function beforeEditData()
	{
		return true;
	}

	protected function afterEditData($dataRow = null)
	{
		return true;
	}

	protected function exceptionEditData($dataRow = null)
	{
		return true;
	}

	protected function beforeDeleteData()
	{
		return true;
	}

	protected function afterDeleteData()
	{
		return true;
	}

	protected function exceptionDeleteData()
	{
		return true;
	}

	protected function beforeAddRow($dataRow = null)
	{
		return true;
	}

	protected function afterAddRow($dataRow = null)
	{
		return true;
	}

	protected function beforeEditRow($dataRow = null)
	{
		return true;
	}

	protected function afterEditRow($dataRow = null)
	{
		return true;
	}

	protected function beforePrepareEdit($dataRow = null)
	{
		return true;
	}

	protected function afterPrepareEdit($dataRow = null)
	{
		return true;
	}

	protected function getExcludeFields()
	{
		return array('id', 'save');
	}

	protected function getExcludeFieldsForEdit()
	{
		return array('save');
	}

	protected function checkPrepareEditItem($property, $element)
	{
		if (!in_array($property, $this->getExcludeFieldsForEdit()) && !empty($element) ) {
			return true;
		}
		return false;
	}

	protected function checkRowItem($element)
	{
		if (!in_array($element->getName(), $this->getExcludeFields())) {
			return true;
		}
		return false;
	}

	protected function saveDataRow($dataRow)
	{
		if ($dataRow instanceof Zend_Db_Table_Row_Abstract) {
			try {
				$dataRow->save();
			} catch (Exception $e) {
				if ($e->getMessage() != 'Cannot refresh row as parent is missing') {
					throw $e;
				}
			}
		}
	}

	protected function getParams()
	{
		$id	  	   	    = $this->_getParam('id', null);
		$search         = $this->_getParam('_search', false);
		$page           = $this->_getParam('page', 1);
		$rp	            = $this->_getParam('rows', 10);
		$sortname	    = $this->_getParam('sidx', null);
		$sortorder      = $this->_getParam('sord', null);
		if ($search == 'true') {
			$searchField    = $this->_getParam('searchField', null);
			$searchOper     = $this->_getParam('searchOper', null);
			$searchString   = $this->_getParam('searchString', null);
		} else {
			$searchField    = null;
			$searchOper     = null;
			$searchString   = null;
		}
		return new Core_Params(array('id'=>$id,
						'page'=>$page,
						'rp'=>$rp,
						'search'=>$search,
						'sortname'=>$sortname,
						'sortorder'=>$sortorder,
						'searchField'=>$searchField,
						'searchOper'=>$searchOper,
						'searchString'=>$searchString
		));
	}

	protected function getResponseArray()
	{
		return new Core_Response();
	}

	protected function getDataRow($rowId = null)
	{
		if ($this->_dataModel->isExists(array('id' => $rowId))) {
			return $this->_dataModel->find($rowId)->current();
		} else {
			return null;
		}
	}

	protected function getMultiSearchCondition()
	{
		$whereStr = '1 = 1';
		if (($this->_params->search = 'true') && (!$this->_params->searchField) && (!$this->_params->searchOper) && (!$this->_params->searchString)) {
			$sarr = $_REQUEST;
			foreach ( $sarr as $fieldName=>$fieldValue) {
				if (in_array($fieldName, $this->_listItems)) {
					$whereStr .= " AND `".$fieldName."` LIKE '".$fieldValue."%'";
				}
			}
		}
		return $whereStr;
	}
}
