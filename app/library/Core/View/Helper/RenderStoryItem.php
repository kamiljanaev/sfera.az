<?php
class Core_View_Helper_RenderStoryItem extends Core_View_Helper
{
	public function renderStoryItem($storyItem = null)
	{
        if (!($storyItem instanceof Core_Db_Story_Row)) {
            return '';
        }
        $this->_view->storyItem = $storyItem;
        $this->_view->storyItemValue = $storyItem->getStoryItem();
        $this->_view->currentProfile = $storyItem->getProfile();
        switch ($storyItem->type) {
            case Core_Db_Story::STORY_STATUS:
                return $this->_view->render('storyItemStatus.phtml');
            case Core_Db_Story::STORY_FRIEND_ADD:
                return $this->_view->render('storyItemFriendAdd.phtml');
            default:
                return '';
        }
	}
}
