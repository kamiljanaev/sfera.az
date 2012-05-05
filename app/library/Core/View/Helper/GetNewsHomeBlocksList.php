<?php
class Core_View_Helper_GetNewsHomeBlocksList extends Core_View_Helper
{
	function getNewsHomeBlocksList()
	{
        $categoryModel = new Core_Db_Category_Tree;
        $newsCategory = $categoryModel->getByAlias(Core_Db_Category_Tree::CATEGORY_NEWS_ALIAS);
        $this->_view->newsCategoryDataArray = array();
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        if ($newsCategory) {
            $newsCategoriesList = $categoryModel->fetchByParent($newsCategory->id, 1, 1)->fetchArray();
            foreach ($newsCategoriesList as $newsCategoryItem) {
                $this->_view->newsCategoryDataArray[] = array(
                    'id' => $newsCategoryItem->id,
                    'title' => $newsCategoryItem->title,
                    'alias' => $newsCategoryItem->alias
                );
            }
        }
        return $this->_view->render('newsHomeBlocksList.phtml');
	}
}
