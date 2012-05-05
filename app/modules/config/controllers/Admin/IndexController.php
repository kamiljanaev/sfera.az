<?php
class Config_Admin_IndexController extends Zend_Controller_Action
{
	private
		$_configTable;

	public function init()
	{
		$this->settingsModel = new Core_Db_Settings();
		$this->settingsModel->setDefaultOrderField('position');
		$this->settingsModel->getDefaultOrderSequence('asc');
	}

	public function indexAction()
	{
		$form = $this->view->form = $this->getIndexForm();
		$this->render('index');
	}

	public function getIndexForm($id = 0)
	{
		$action = $this->view->getUrl("admin/module/config/index/save");
		$form = new Zend_Form();
		$form->setAction($action);
		$select = $this->settingsModel->select();
		$settings = $this->settingsModel->fetchAll($select);
		foreach ($settings AS $setting) {
			if ($setting->type != 'delimiter') {
				$elementName = $setting->name;
				$form->addElement($setting->type, $elementName,array(
						'required' => false
				));
				if ($setting->type == 'textarea') {
					$form->$elementName->setAttrib('rows','5');
				}
				$form->$elementName->removeDecorator('HtmlTag');
				$form->$elementName->removeDecorator('Label');
				$value = $setting->value;
				$form->$elementName->setValue($value);
			}
		}
		$this->view->list = $settings;
		$form->clearDecorators();
		return $form;
	}

	public function saveAction()
	{
		$err = FALSE;
		$form = $this->view->form = $this->getIndexForm();
		if ($this->_request->isPost()) {
			if (!$form->isValid($_POST)) {
				$this->view->form = $form;
			} else {
				$values = $form->getValues();
				foreach ($values  AS $key=>$val) {
					if ($current = $this->settingsModel->getByName($key)->current()) {
						if (!$val) {
							$current->value = '';
						} else {
							$current->value = $val;
						}
						$current->save();
					}
				}
			}
		}
		$this->_redirect($this->view->getUrl("admin/module/config/"));
	}
}