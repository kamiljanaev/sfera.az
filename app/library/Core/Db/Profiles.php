<?php
class Core_Db_Profiles extends Core_Db_Table
{
	protected
		$_name = 'view_profiles',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Profiles_Row',
		$_rowsetClass = 'Core_Db_Profiles_Rowset',
		$_referenceMap	= array(
			'Ref_To_User' => array(
							'columns' => array('user_id'),
							'refTableClass'	=> 'Core_Db_Users',
							'refColumns' => array('id')
			)
		);

    public function getByAlias($alias = null)
    {
        if (!$alias) {
            return null;
        }
        $select = $this->select()->from($this)->where('alias = ?', $alias);
        return $this->fetchRow($select);
    }
}