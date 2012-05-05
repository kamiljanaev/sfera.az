<?php
class Core_Db_Favorites_Row extends Core_Db_Table_Row
{
    public function getFavItem()
    {
        $favItem = null;
        switch ($this->type) {
            case Core_Db_Favorites::FW_NEWS:
                $newsModel = new Core_Db_Content_News;
                $favItem = $newsModel->getById($this->item_id);
                if ($favItem instanceof Core_Db_Content_News_Row) {
                    return $favItem;
                } else {
                    return null;
                }
            case Core_Db_Favorites::FW_ADS:
                $adsModel = new Core_Db_Content_Ads();
                $favItem = $adsModel->getById($this->item_id);
                if ($favItem instanceof Core_Db_Content_Ads_Row) {
                    return $favItem;
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