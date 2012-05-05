<?php
class Core_View_Helper_GetNewsCategoryById extends Core_View_Helper_GetNewsCategory
{
    protected
        $_currentCategoryAlias = null;

	function getNewsCategoryById($categoryId)
	{
        $categoryModel = new Core_Db_Category_Tree;
        $newsCategory = $categoryModel->getRowInfo($categoryId);
        if ($newsCategory) {
            $this->_currentCategoryAlias = $newsCategory->alias;
            $this->getNewsCategory();
        }
        return $this->_view->render('newsCategoryById.phtml');
	}
}
