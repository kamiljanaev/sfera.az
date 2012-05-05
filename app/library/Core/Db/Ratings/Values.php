<?php
class Core_Db_Ratings_Values extends Core_Db_Table
{
	protected
		$_name = 'view_ratings_values',
		$_primary = 'item_id',
		$_rowClass = 'Core_Db_Ratings_Values_Row',
		$_rowsetClass = 'Core_Db_Ratings_Values_Rowset';

    public function getVotes($itemId = null, $typeId = null)
    {
        $select = $this->select()->from($this);
        if ($itemId) {
            $select->where( '`item_id` = ?', $itemId);
        }
        if ($typeId) {
            $select->where( '`type_id` = ?', $typeId);
        }
        return $this->fetchRow($select);
    }
}