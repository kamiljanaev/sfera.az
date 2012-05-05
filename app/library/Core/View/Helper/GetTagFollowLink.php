<?php
class Core_View_Helper_GetTagFollowLink extends Core_View_Helper
{
	function getTagFollowLink($tag = null, $text = '')
	{
        $auth = Core_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $currentUser = $auth->getIdentity();
            $currentProfile = $currentUser->getProfile();
            $favoritesModel = new Core_Db_Content_Tags_SubscribeLinks;
            if ($favoritesModel->isExist($currentProfile->id, $tag->id)) {
                $type = 'unfollow';
                $linkText = 'Unfollow '.$text;
            } else {
                $type = 'follow';
                $linkText = 'Follow '.$text;
            }
            return '<a href="'.$this->_view->getUrl('content/news/tagsubscribe').'" item-id="'.$tag->id.'" item-type="'.$type.'" class="follow ajax_follow_tag">'.$linkText.'</a>';
        } else {
            return '';
        }
	}
}
