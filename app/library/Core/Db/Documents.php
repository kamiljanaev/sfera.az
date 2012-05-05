<?php
class Core_Db_Documents extends Core_Db_Table
{
	protected
		$_name = 'view_documents',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Documents_Row',
		$_rowsetClass = 'Core_Db_Documents_Rowset',
		$_referenceMap	= array(
			'Ref_To_User' => array(
							'columns' => array('user_id'),
							'refTableClass'	=> 'Core_Db_Users',
							'refColumns' => array('id')
			),
            'Ref_To_Profile' => array(
                            'columns' => array('profile_id'),
                            'refTableClass'	=> 'Core_Db_Profiles',
                            'refColumns' => array('id')
            )
		);

}