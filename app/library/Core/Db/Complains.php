<?php
class Core_Db_Complains extends Core_Db_Table
{
	const
        C_NEWS = 1,
        C_BLOGS = 2,
        C_COMMENTS = 3;

	protected
		$_name = 'view_complains',
		$_primary = 'id',
        $_rowClass = 'Core_Db_Complains_Row',
        $_rowsetClass = 'Core_Db_Complains_Rowset';

    protected
        $_referenceMap	= array(
            'Ref_To_Profile' => array(
                            'columns' => array('profile_id'),
                            'refTableClass'	=> 'Core_Db_Profiles',
                            'refColumns' => array('id')
            )
        );

    public function isExist($profileId = null, $typeId = null, $itemId = null)
    {
        $select = $this->select()->from($this);
        $select->where('type_id = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        $select->where('profile_id = ?', $profileId);
        if ($this->fetchRow($select) instanceof Core_Db_Favorites_Row) {
            return true;
        } else {
            return false;
        }
    }

    public function getByProfileIdTypeIdItemId($profileId = null, $typeId = null, $itemId = null)
    {
        $select = $this->select()->from($this);
        $select->where('type_id = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        $select->where('profile_id = ?', $profileId);
        return $this->fetchRow($select);
    }

    public function getByItemId($itemId = null, $typeId = null)
    {
        if (!$itemId) {
            return null;
        }
        if (!$typeId) {
            return null;
        }
        $select = $this->getSelect();
        $select->where('type_id = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        return $this->fetchAll($select);
    }

}
