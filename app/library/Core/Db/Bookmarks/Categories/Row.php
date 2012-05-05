<?php
class Core_Db_Bookmarks_Categories_Row extends Core_Db_Table_Row
{
    protected
        $_BookmarksList = null;

    public function getBookmarks()
    {
        if ($this->_BookmarksList == null) {
            $bookmarksModel = new Core_Db_Bookmarks();
            $this->_BookmarksList = $this->findDependentRowset($bookmarksModel);
        }
        return $this->_BookmarksList;
    }

}