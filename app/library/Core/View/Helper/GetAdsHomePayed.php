<?php
class Core_View_Helper_GetAdsHomePayed extends Core_View_Helper
{

	function getAdsHomePayed()
	{
        $this->_view->adsDataArray = array();
        $adsModel = new Core_Db_Content_Ads;
        $adsModel->setDefaultOrderField('public_date');
        $adsModel->setDefaultOrderSequence('desc');
        $adsModel->setDefaultStatus(Core_Db_Content_Ads::NEWS_VISIBLE);
        $adsModel->setPayedStatus(Core_Db_Content_Ads::ADS_PAYED);
        $this->_view->adsDataArray = $adsModel->getActualAds();
        return $this->_view->render('adsHomePayed.phtml');
	}
}
