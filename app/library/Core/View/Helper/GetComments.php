<?php
class Core_View_Helper_GetComments extends Core_View_Helper
{
    private
        $profileModel = null;
	function getComments($item_id = null, $type_id = null)
	{
        $commentsModel = new Core_Db_Comments();
        $commentsTree = $commentsModel->fetchTree($item_id, $type_id);
        $this->profileModel = new Core_Db_Profiles();
        $this->_view->commentsArray = array();
        foreach ($commentsTree as $commentItem) {
            $this->_view->commentsArray[] = $this->getCommentData($commentItem);
        }
        return $this->_view->render('commentsTree.phtml');
	}

    private function getCommentData($commentItem)
    {
        $resultData = $commentItem->toArray();
        $resultData['profile'] = $this->profileModel->getRowInfo($commentItem->profile_id);
        return $resultData;
    }
}
