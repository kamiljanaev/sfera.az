<?php
class Core_View_Helper_RenderFavoriteItem extends Core_View_Helper
{
	public function renderFavoriteItem($favoriteItem = null)
	{
        if (!($favoriteItem instanceof Core_Db_Favorites_Row)) {
            return $this->_view->render('favItemEmpty.phtml');
        }
        $this->_view->favItem = $favoriteItem->getFavItem();
        switch ($favoriteItem->type) {
            case Core_Db_Favorites::FW_NEWS:
                return $this->_view->render('favItemNews.phtml');
            case Core_Db_Favorites::FW_ADS:
                return $this->_view->render('favItemAds.phtml');
            case Core_Db_Favorites::FW_BLOGS:
                return $this->_view->render('favItemEmpty.phtml');
            default:
                return $this->_view->render('favItemEmpty.phtml');
        }
	}
}
