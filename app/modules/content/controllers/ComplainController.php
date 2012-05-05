<?php
class Content_ComplainController extends Core_Controller_Action
{
    public function addAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $data['type_id'] = $this->_getParam('type_id', null);
        $data['item_id'] = $this->_getParam('item_id', null);
        $data['profile_id'] = $currentProfile->id;
        $data['complain'] = $this->_getParam('complain', '');
        if ($data['type_id'] && $data['item_id']) {
            $complainsModel = new Core_Db_Complains;
            $newComplainRow = $complainsModel->createRow($data);
            $newComplainRow->save();
            $this->_helper->json->sendJson(array('result' => 1));
        } else {
            $this->_helper->json->sendJson(array('result' => 0));
        }
    }

}