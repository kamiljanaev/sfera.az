<?php
class Core_Db_Content_News_Row extends Core_Db_Table_Row
{
    protected
        $_currentTagsList = null,
        $_currentCategoryName = null,
        $_currentCategory = null,
        $_currentUser = null;

    public function getUser()
    {
        if (!($this->_currentUser instanceof Core_Db_Users_Row)) {
            $this->_currentUser = $this->findParentRow('Core_Db_Users', 'Ref_To_User');
        }
        return $this->_currentUser;
    }

    public function getCategory()
    {
        if (!($this->_currentCategory instanceof Core_Db_Catalog_Tree_Row)) {
            $this->_currentCategory = $this->findParentRow('Core_Db_Category_Tree', 'Ref_To_Category');
        }
        return $this->_currentCategory;
    }

    public function getCategoryName()
    {
        if ($this->_currentCategoryName === null) {
            $currentCat = $this->getCategory();
            if ($currentCat) {
                $this->_currentCategoryName = $currentCat->title;
            } else {
                $this->_currentCategoryName = '';
            }
        }
        return $this->_currentCategoryName;
    }

	public function link($action = 'edit')
	{
		return Core_View_Helper_Link :: contentLink('news', $this->id, $action);
	}

    public function activateNew()
    {
        $this->activated = 1;
        $this->save();
    }

    public function addViewCounter()
    {
        $this->view_count = $this->view_count + 1;
        $this->save();
    }

    public function getViewCounter()
    {
        return $this->view_count;
    }

    public function recalculateRating()
    {
        $ratingHelper = new Core_View_Helper_GetRatingValueNews;
        $this->rating = $ratingHelper->getRatingValueNews($this->id);
        $this->save();
    }

    public function getTags()
    {
        if (!($this->_currentTagsList instanceof Core_Db_Content_TagsLinks_Rowset)) {
            $tagsLinksModel = new Core_Db_Content_TagsLinks;
            $this->_currentTagsList = $tagsLinksModel->getByItemId($this->id, Core_Db_Content_Tags::TAG_TYPE_NEWS);
        }
        return $this->_currentTagsList;
    }


}