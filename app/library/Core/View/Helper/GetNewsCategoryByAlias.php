<?php
class Core_View_Helper_GetNewsCategoryByAlias extends Core_View_Helper_GetNewsCategory
{
    protected
        $_currentCategoryAlias = null;

	function getNewsCategoryByAlias($alias)
	{
        $this->_currentCategoryAlias = $alias;
        $this->getNewsCategory();
        return $this->_view->render('newsCategoryById.phtml');
	}
}
