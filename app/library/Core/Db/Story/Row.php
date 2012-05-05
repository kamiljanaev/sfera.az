<?php
class Core_Db_Story_Row extends Core_Db_Table_Row
{
    private
        $_Profile = null;

    public function getProfile()
    {
        if ($this->_Profile==null) {
            $this->_Profile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
        }
        return $this->_Profile;
    }

    public function getStoryItem()
    {
        $favItem = null;
        switch ($this->type) {
            case Core_Db_Story::STORY_STATUS:
                $statusModel = new Core_Db_Statuses;
                $storyItem = $statusModel->getById($this->item_id);
                if ($storyItem instanceof Core_Db_Statuses_Row) {
                    return $storyItem;
                } else {
                    return null;
                }
            case Core_Db_Story::STORY_FRIEND_ADD:
                $profilesModel = new Core_Db_Profiles;
                $storyItem = $profilesModel->getById($this->item_id);
                if ($storyItem instanceof Core_Db_Profiles_Row) {
                    return $storyItem;
                } else {
                    return null;
                }
/*            case Core_Db_Favorites::FW_BLOGS:
                $blogsModel = new Core_Db_Content_Blogs;
                $favItem = $blogsModel->getById($this->item_id);
                if ($favItem instanceof Core_Db_Content_Blogs_Row) {
                    return $favItem;
                } else {
                    return null;
                }*/
            default:
                return null;
        }
    }
}