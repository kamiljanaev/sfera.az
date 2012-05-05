<?php
class Core_View_Helper_GetAdsHomeVacancy extends Core_View_Helper_GetAdsHome
{
    protected
        $_currentCategory = Core_Db_Category_Tree::CATEGORY_ADS_VACANCY;
    
	function getAdsHomeVacancy()
	{
        $this->getAdsHome();
        return $this->_view->render('adsHomeVacancy.phtml');
	}
}
