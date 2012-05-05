<?php
class Core_View_Helper_GetNewsCategoryAjerbaijan extends Core_View_Helper_GetNewsCategory
{
    protected
        $_currentCategoryAlias = Core_Db_Category_Tree::CATEGORY_NEWS_AJERBAIJAN_ALIAS;

	function getNewsCategoryAjerbaijan()
	{
        $this->getNewsCategory();
        return $this->_view->render('newsCategoryAjerbaijan.phtml');
	}
}
