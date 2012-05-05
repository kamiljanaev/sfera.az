<?php
class Core_Db_Content_Ads_Row extends Core_Db_Table_Row
{
    protected
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
		return Core_View_Helper_Link :: contentLink('ads', $this->id, $action);
	}

    public function activateNew()
    {
        $this->activated = 1;
        $this->save();
    }
}