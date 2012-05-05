<?php
class Core_Db_Awards_Row extends Core_Db_Table_Row
{
	public function hasAssignedProfiles()
	{
        return 0;
		return count($this->findDependentRowset('Core_Db_AwardsLinks', 'Ref_To_Award'));
	}

	public function link($action='view')
	{
		return Core_View_Helper_Link :: award($this->id, $action);
	}
}