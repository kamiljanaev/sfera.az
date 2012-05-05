<?php
class Core_View_Helper_GetNewsCategory extends Core_View_Helper
{
    protected
        $_currentCategoryAlias = null;

	function getNewsCategory()
	{
        $categoryModel = new Core_Db_Category_Tree;
        $newsCategory = $categoryModel->getByAlias($this->_currentCategoryAlias);
        $this->_view->newsCategory = $newsCategory;
        $this->_view->newsDataArray = array();
        $this->_view->newsHotDataArray = array();
        if ($newsCategory) {
            $newsModel = new Core_Db_Content_News;
            $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
            $newsModel->setDefaultCategory($newsCategory->id);
            $this->_view->newsHotDataArray = $newsModel->getList(1, 2, 'is_hot', 'eq', '1', 'public_date', 'desc')->fetchArray();
            $this->_view->newsDataArray = $newsModel->getList(1, 3, null, null, null, 'public_date', 'desc')->fetchArray();
        }
	}
}
