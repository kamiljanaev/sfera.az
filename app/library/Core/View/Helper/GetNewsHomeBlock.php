<?php
class Core_View_Helper_GetNewsHomeBlock extends Core_View_Helper
{
	function getNewsHomeBlock()
	{
        $categoryModel = new Core_Db_Category_Tree;
        $newsCategory = $categoryModel->getByAlias(Core_Db_Category_Tree::CATEGORY_NEWS_ALIAS);
        $this->_view->newsDataArray = array();
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        if ($newsCategory) {
            $newsCategoriesList = $categoryModel->fetchByParent($newsCategory->id, 1, 1)->fetchArray();
            foreach ($newsCategoriesList as $newsCategoryItem) {
                $this->_view->newsDataArray[] = array(
                    'id' => $newsCategoryItem->id,
                    'title' => $newsCategoryItem->title,
                    'category_id' => $newsCategoryItem->id,
                    'news_list' => $newsModel->getList(1, 5, 'category_id', 'eq', $newsCategoryItem->id, 'public_date', 'desc')->fetchArray()
                );
            }
        }
        return $this->_view->render('newsHomeBlock.phtml');
	}
}
