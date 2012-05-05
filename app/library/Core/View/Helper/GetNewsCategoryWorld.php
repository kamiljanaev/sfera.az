<?php
class Core_View_Helper_GetNewsCategoryWorld extends Core_View_Helper_GetNewsCategory
{
    protected
        $_currentCategoryAlias = Core_Db_Category_Tree::CATEGORY_NEWS_WORLD_ALIAS;

	function getNewsCategoryWorld()
	{
        $this->getNewsCategory();
        return $this->_view->render('newsCategoryWorld.phtml');
	}
}
