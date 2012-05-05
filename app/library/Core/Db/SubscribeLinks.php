<?php
class Core_Db_SubscribeLinks extends Core_Db_Table
{
	protected
		$_name = 'site_subscribes',
		$_primary = 'id',
		$_rowClass = 'Core_Db_SubscribeLinks_Row',
		$_rowsetClass = 'Core_Db_SubscribeLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			),
			'Ref_To_Category' => array(
							'columns' => array('category_id'),
							'refTableClass'	=> 'Core_Db_Category_Tree',
							'refColumns' => array('id')
			)
		);

    public function isExist($profileId = null, $catId)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('category_id = ?', $catId);
        if ($this->fetchRow($select) instanceof Core_Db_SubscribeLinks_Row) {
            return true;
        } else {
            return false;
        }
    }

    public function getByProfileIdCategoryId($profileId = null, $catId)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('category_id = ?', $catId);
        return $this->fetchRow($select);
    }

	public function getByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('profile_id = ?', $profileId);
		return $this->fetchAll($select);
	}

	public function getByCategoryId($catId = null)
	{
		if (!$catId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('category_id = ?', $catId);
		return $this->fetchAll($select);
	}

	public function deleteByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		return $this->delete('profile_id = '.$profileId);
	}
}