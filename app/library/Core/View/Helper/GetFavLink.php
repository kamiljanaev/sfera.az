<?php
class Core_View_Helper_GetFavLink extends Core_View_Helper
{
	function getFavLink($type = null, $id = null)
	{
        $auth = Core_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $currentUser = $auth->getIdentity();
            $currentProfile = $currentUser->getProfile();
            $favoritesModel = new Core_Db_Favorites;
            if ($favoritesModel->isExist($currentProfile->id, $type, $id)) {
                return 'In favorites';
            } else {
                return '<a href="'.$this->_view->getUrl('favorites/add').'" item-id="'.$id.'" item-type="'.$type.'" class="fav-link favorites">Add to Favorites</a>';
            }
        } else {
            return 'Add to Favorites';
        }
	}
}
