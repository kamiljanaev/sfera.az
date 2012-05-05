<?php
class Core_View_Helper_GetRatingValue extends Core_View_Helper
{
	function getValue($id = null, $type = null)
	{
        $ratingValueModel = new Core_Db_Ratings_Values;
        $ratingValueItem = $ratingValueModel->getVotes($id, $type);
        if ($ratingValueItem) {
            return $ratingValueItem->rating;
        } else {
            return 0;
        }
	}
}
