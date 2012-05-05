<?php
class Core_Db_Modules extends Core_Db_Table
{
	protected 
		$_name = 'sys_modules',
		$_rowClass = 'Core_Db_Modules_Row',
		$_rowsetClass = 'Core_Db_Modules_Rowset';
}