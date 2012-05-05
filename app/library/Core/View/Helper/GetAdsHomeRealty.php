<?php
class Core_View_Helper_GetAdsHomeRealty extends Core_View_Helper_GetAdsHome
{
    protected
        $_currentCategory = Core_Db_Category_Tree::CATEGORY_ADS_REALTY;
    
	function getAdsHomeRealty()
	{
        $this->getAdsHome();
        return $this->_view->render('adsHomeRealty.phtml');
	}
}
