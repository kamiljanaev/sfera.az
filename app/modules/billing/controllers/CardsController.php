<?php
class Billing_CardsController extends Core_Controller_Action
{
    public function updateAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        if ($this->_currentUser) {
            if ($this->_request->isPost()) {
                $scratchCardNumber = $this->_getParam('number', null);
                $scratchCardCode = $this->_getParam('code', null);
//                if ($scratchCardNumber&&$scratchCardCode) {
                if ($scratchCardNumber) {
                    $scratchCardModel = new Core_Db_ScratchCards();
                    $scratchCardItem = $scratchCardModel->getByNumberCode($scratchCardNumber, $scratchCardCode);
                    if ($scratchCardItem && !$scratchCardItem->checkUsed()) {
                        if ($scratchCardItem->useCard($currentProfile)) {
                            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile'));
                        }
                    }
                }
            }
        } else {
            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
    }
}