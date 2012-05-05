<?php
class Core_Db_Content_TagsLinks_Row extends Core_Db_Table_Row
{
    private
        $_currentTag = null;
    
    public function getTaggedItem()
    {
        $taggedItem = null;
        switch ($this->type) {
            case Core_Db_Content_Tags::TAG_TYPE_NEWS:
                $newsModel = new Core_Db_Content_News;
                $taggedItem = $newsModel->getById($this->item_id);
                if ($taggedItem instanceof Core_Db_Content_News_Row) {
                    return $taggedItem;
                } else {
                    return null;
                }
/*            case Core_Db_Content_Tags::TAG_TYPE_BLOGS:
                $blogsModel = new Core_Db_Content_Blogs;
                $taggedItem = $blogsModel->getById($this->item_id);
                if ($favItem instanceof Core_Db_Content_Blogs_Row) {
                    return $taggedItem;
                } else {
                    return null;
                }*/
            default:
                return null;
        }
    }

    public function getTag()
    {
        if (!($this->_currentTag instanceof Core_Db_Content_Tags_Row)) {
            $this->_currentTag = $this->findParentRow('Core_Db_Content_Tags', 'Ref_To_Tag');
        }
        return $this->_currentTag;
    }

}