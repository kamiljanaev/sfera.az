<?php
class Core_View_Helper_GetCommentsCount extends Core_View_Helper
{
	function getCommentsCount($item_id = null, $type_id = null)
	{
        $commentsModel = new Core_Db_Comments();
        return $commentsModel->fetchTree($item_id, $type_id)->count();
	}
}
