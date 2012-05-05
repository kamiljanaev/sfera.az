<?php
class Core_Db_Bookmarks_Row extends Core_Db_Table_Row
{
    protected
        $_Category = null;

    public function getCategory()
    {
        if (!($this->_Category instanceof Core_Db_Bookmarks_Categories_Row)) {
            $this->_Category = $this->findParentRow('Core_Db_Bookmarks_Categories', 'Ref_To_Category');
        }
        return $this->_Category;
    }

}