<?php
class Core_Db_Awards extends Core_Db_Table
{
	protected
		$_name = 'view_awards',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Awards_Row',
		$_rowsetClass = 'Core_Db_Awards_Rowset';

	public function getByAlias( $alias = null )
	{
        if ($alias !== null) {
            $select = $this->select()->where( '`alias` = ?', $alias );
            return $this->fetchRow($select);
        }
        return null;
	}

    public function getIdTitleList()
    {
        return $this->fetchList('id', 'title');
    }

    public function getIdAliasList()
    {
        return $this->fetchList('id', 'alias');
    }
}