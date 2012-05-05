<?php
class Core_Db_Ratings_Values_Row extends Core_Db_Table_Row
{
	private
		$_currentItem = null;

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