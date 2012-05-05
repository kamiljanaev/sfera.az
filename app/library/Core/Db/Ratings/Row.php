<?php
class Core_Db_Ratings_Row extends Core_Db_Table_Row
{
	private
		$_currentItem = null,
		$_currentProfile = null;

	public function getProfile()
	{
		if ($this->_currentProfile instanceof Core_Db_Profiles_Row) {
			$this->_currentProfile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
		}
		return $this->_currentProfile;
	}

	public function getItem()
	{
        if ($this->_currentItem === null) {
            switch ($this->type_id) {
                case Core_Db_Ratings::R_NEWS:
                    $newsModel = new Core_Db_Content_News;
                    $this->_currentItem = $newsModel->getById($this->item_id);
                    break;
    /*            case Core_Db_Ratings::R_BLOGS:
                    $blogsModel = new Core_Db_Content_Blogs;
                    $this->_currentItem = $blogsModel->getById($this->item_id);*/
                default:
                    break;
            }
		}
		return $this->_currentItem;
	}

}