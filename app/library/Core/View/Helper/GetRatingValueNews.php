<?php
class Core_View_Helper_GetRatingValueNews extends Core_View_Helper_GetRatingValue
{
	function getRatingValueNews($id = null)
	{
        return $this->getValue($id, Core_Db_Ratings::R_NEWS);
	}
}
