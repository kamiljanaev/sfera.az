<?php
class Core_View_Helper_GetNewsRollPopular extends Core_View_Helper
{
	function getNewsRollPopular()
	{
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultOrderField('view_count');
        $newsModel->setDefaultOrderSequence('desc');
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $this->_view->newsList = $newsModel->getAllList();
        return $this->_view->render('newsRollPopular.phtml');
	}
}
