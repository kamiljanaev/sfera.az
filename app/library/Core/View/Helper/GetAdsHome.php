<?php
class Core_View_Helper_GetAdsHome extends Core_View_Helper
{
    protected
        $_currentCategory = null;
    
	function getAdsHome()
	{
        $categoryModel = new Core_Db_Category_Tree;
        $adsCategory = $categoryModel->getByAlias($this->_currentCategory);
        $this->_view->adsDataArray = array();
        if ($adsCategory) {
            $adsModel = new Core_Db_Content_Ads;
            $adsModel->setDefaultOrderField('public_date');
            $adsModel->setDefaultOrderSequence('desc');
            $adsModel->setDefaultStatus(Core_Db_Content_Ads::NEWS_VISIBLE);
            $this->_view->adsDataArray = $adsModel->getActualAds($adsCategory->id);
        }
	}
}
