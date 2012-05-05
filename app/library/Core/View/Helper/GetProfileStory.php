<?php
class Core_View_Helper_GetProfileStory extends Core_View_Helper
{
	function getProfileStory($profileId = null)
	{
        $this->_view->currentProfile = null;
        $this->_view->profileStoryList = array();
        if ($profileId) {
            $profileModel = new Core_Db_Profiles;
            $this->_view->currentProfile = $profileModel->getRowInfo($profileId);
            $storyModel = new Core_Db_Story;
            $this->_view->profileStoryList = $storyModel->getByProfileId($profileId)->fetchArray();
        }
        return $this->_view->render('getProfileStory.phtml');
	}
}
