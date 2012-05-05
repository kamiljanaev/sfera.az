<?php
class Core_Db_Complains_Row extends Core_Db_Table_Row
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

    public function getItem()
    {
        $complainItem = null;
        switch ($this->type_id) {
            case Core_Db_Complains::C_NEWS:
                $newsModel = new Core_Db_Content_News;
                $complainItem = $newsModel->getRowInfo($this->item_id);
                if ($complainItem instanceof Core_Db_Content_News_Row) {
                    return $complainItem;
                } else {
                    return null;
                }
            case Core_Db_Complains::C_COMMENTS:
                $commentModel = new Core_Db_Comments();
                $complainItem = $commentModel->getRowInfo($this->item_id);
                if ($complainItem instanceof Zend_Db_Table_Mptt_Row) {
                    return $complainItem;
                } else {
                    return null;
                }
/*            case Core_Db_Complains::C_BLOGS:
                $blogsModel = new Core_Db_Content_Blogs;
                $complainItem = $blogsModel->getById($this->item_id);
                if ($complainItem instanceof Core_Db_Content_Blogs_Row) {
                    return $complainItem;
                } else {
                    return null;
                }*/
            default:
                return null;
        }
    }
}