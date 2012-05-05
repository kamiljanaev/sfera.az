<?php
class Core_View_Helper_GetCountTagSubscribers extends Core_View_Helper
{
	function getCountTagSubscribers($tagId = null)
	{
        $result = 0;
        if ($tagId) {
            $subscribeLinksModel = new Core_Db_Content_Tags_SubscribeLinks;
            return $subscribeLinksModel->getByTagId($tagId)->count();
        }
		return $result;
	}
}

