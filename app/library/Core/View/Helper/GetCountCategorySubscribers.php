<?php
class Core_View_Helper_GetCountCategorySubscribers extends Core_View_Helper
{
	function getCountCategorySubscribers($categoryId = null)
	{
        $result = 0;
        if ($categoryId) {
            $subscribeLinksModel = new Core_Db_SubscribeLinks;
            return $subscribeLinksModel->getByCategoryId($categoryId)->count();
        }
		return $result;
	}
}

