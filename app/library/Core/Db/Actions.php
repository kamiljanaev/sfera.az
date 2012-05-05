<?php
class Core_Db_Actions extends Core_Db_Table
{
	protected
		$_name = 'sys_actions',
		$_rowClass = 'Core_Db_Actions_Row',
		$_rowsetClass = 'Core_Db_Actions_Rowset';
	protected
		$_referenceMap	= array(
			'Ref_To_Controller' => array(
							'columns' => array('controller_id'),
							'refTableClass'	=> 'Core_Db_Controllers',
							'refColumns' => array('id')
			)
		);

    public function getByControllerId($controllerId = null)
    {
        $select = $this->getSelect();
        if ($controllerId) {
            $select->where('controller_id = ?', $controllerId);
        }
        return $this->fetchAll($select);
    }
}