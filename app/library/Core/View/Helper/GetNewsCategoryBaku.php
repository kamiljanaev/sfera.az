<?php
class Core_View_Helper_GetNewsCategoryBaku extends Core_View_Helper_GetNewsCategory
{
    protected
        $_currentCategoryAlias = Core_Db_Category_Tree::CATEGORY_NEWS_BAKU_ALIAS;

	function getNewsCategoryBaku()
	{
        $this->getNewsCategory();
        return $this->_view->render('newsCategoryBaku.phtml');
	}
}
