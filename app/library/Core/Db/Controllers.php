<?php
class Core_Db_Controllers extends Core_Db_Table
{
	protected
		$_name = 'sys_controllers',
		$_rowClass = 'Core_Db_Controllers_Row',
		$_rowsetClass = 'Core_Db_Controllers_Rowset';
	protected
		$_referenceMap	= array(
			'Ref_To_Module' => array(
							'columns' => array('module_id'),
							'refTableClass'	=> 'Core_Db_Modules',
							'refColumns' => array('id')
			)
		);

    public function getByModuleId($moduleId = null)
    {
        $select = $this->getSelect();
        if ($moduleId) {
            $select->where('module_id = ?', $moduleId);
        }
        return $this->fetchAll($select);
    }
}