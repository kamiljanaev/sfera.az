<?php
class Core_Db_ScratchCards extends Core_Db_Table
{
	protected
		$_name = 'view_scratch_cards',
		$_primary = 'id',
		$_rowClass = 'Core_Db_ScratchCards_Row',
		$_rowsetClass = 'Core_Db_ScratchCards_Rowset';

	public function getByNumberCode( $number = '', $code = '' )
	{
		$select = $this->select()->where( '`number` = ?', $number );
		return $this->fetchRow($select);
	}

}