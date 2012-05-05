<?php
class Core_View_Helper_GetCategoryFollowLink extends Core_View_Helper
{
	function getCategoryFollowLink($category = null, $text = '')
	{
        $auth = Core_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $currentUser = $auth->getIdentity();
            $currentProfile = $currentUser->getProfile();
            $subsModel = new Core_Db_SubscribeLinks;
            if ($subsModel->isExist($currentProfile->id, $category->id)) {
                $type = 'unfollow';
                $linkText = 'Unfollow '.$text;
            } else {
                $type = 'follow';
                $linkText = 'Follow '.$text;
            }
            return '<a href="'.$this->_view->getUrl('content/news/subscribe').'" item-id="'.$category->id.'" item-type="'.$type.'" class="ajax_follow_tag">'.$linkText.'</a>';
        } else {
            return '<span>follow '.$text.'</span>';
        }
	}
}
