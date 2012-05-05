<?php
class Content_CommentController extends Core_Controller_Action
{
    public function addAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $data['type_id'] = $this->_getParam('type_id', null);
        $data['item_id'] = $this->_getParam('item_id', null);
        $data['parent_id'] = $this->_getParam('parent_id', null);
        $data['profile_id'] = $currentProfile->id;
        $data['comment'] = $this->_getParam('comment', null);
        $commentsModel = new Core_Db_Comments;
        $newCommentRow = $commentsModel->createRow($data);
        $newCommentRow->save();
        $this->_redirect($_SERVER['HTTP_REFERER']);
        $this->_helper->json->sendJson(array('result' => $rating));
    }

    public function rebuildAction()
    {
        $this->_helper->_layout->disableLayout();
        $commentsViewHelper = new Core_View_Helper_GetComments();
        $commentsViewHelper->getComments(26, 1);
        $commentsModel = new Core_Db_Comments;
        $commentsModel->rebuildTreeTraversal();
        $this->_helper->json->sendJson(array('result' => 'ok'));
    }

}