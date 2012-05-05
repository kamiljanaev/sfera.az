<?php
class Core_View_Helper_GetNewsRoll extends Core_View_Helper
{
	function getNewsRoll()
	{
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultOrderField('public_date');
        $newsModel->setDefaultOrderSequence('desc');
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $this->_view->newsList = $newsModel->getAllList();
        return $this->_view->render('newsRoll.phtml');
	}
}
