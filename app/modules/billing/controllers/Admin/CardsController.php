<?php
class Billing_Admin_CardsController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
		$this->_msgAddItem = $this->_translate->translate('cards-message-added');
		$this->_msgDelItem = $this->_translate->translate('cards-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/billing/cards/');
		$this->_controllerEditUrl = '/admin/module/billing/cards/edit';
		$this->_dataModel = new Core_Db_ScratchCards;
		$this->_dataModel->setDefaultOrderField('number');
        $this->_listItems[] = 'number';
        $this->_listItems[] = 'amount';
        $this->_listItems[] = 'is_used';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();

        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/billing/cards/');
        $this->view->msgGridListTitle = $this->_translate->translate('cards-grid-list-title');
        $this->view->msgCardsAdminTitle = $this->_translate->translate('cards-admin-title');
        $this->view->msgCardsAdminEditTitle = $this->_translate->translate('cards-admin-edit-title');
        $this->view->msgGridColumnNumber = $this->_translate->translate('cards-grid-column-number');
        $this->view->msgGridColumnAmount = $this->_translate->translate('cards-grid-column-amount');
        $this->view->msgGridColumnIsUsed = $this->_translate->translate('cards-grid-column-is-used');
        $this->view->msgCardAlreadyExist = $this->_translate->translate('cards-message-already-exist');
    }

	protected function getForm($type = 'edit')
	{
		$config = new Zend_Config_Ini('config/forms/admin/scratchcards.ini');
		$form = new Zend_Form($config);
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl('/admin/module/billing/cards/edit/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/billing/cards/add/format/json'));
		}
		if ($type == 'edit') {
			$dbRecordNotExists = new Core_Validate_Billing_Card_NoRecordExists($this->_params->id);
		} else {
			$dbRecordNotExists = new Core_Validate_Billing_Card_NoRecordExists();
		}
		$dbRecordNotExists->setMessage($this->view->msgCardAlreadyExist, 'recordFound');
		$form->number->addValidator($dbRecordNotExists);
		$form->removeDecorator('HtmlTag');

		$form->setDisplayGroupDecorators(array (
				'FormElements',
				array ('HtmlTag', array ('tag' => 'ul', 'class' => 'form')),
		));
		$displayGroups = $form->getDisplayGroups();
		foreach ($displayGroups as $displayGroup) {
			foreach ($displayGroup as $element) {
				$element->setDecorators(array(
						'ViewHelper',
						array (array('elUL'=>'Errors'), array ('class' => 'error')),
						array ('HtmlTag', array ('tag' => 'span', 'class' => 'field')),
						'Label',
						array (array('elLI'=>'HtmlTag'), array ('tag' => 'li', 'class' => 'panel')),
				));
			}
		}
		return $form;
	}

    protected function exceptionEditData($dataRow = null)
    {
        return true;
    }

}