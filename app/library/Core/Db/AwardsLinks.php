<?php
class Core_Db_AwardsLinks extends Core_Db_Table
{
	protected
		$_name = 'site_awardslinks',
		$_primary = 'id',
		$_rowClass = 'Core_Db_AwardsLinks_Row',
		$_rowsetClass = 'Core_Db_AwardsLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			),
			'Ref_To_Award' => array(
							'columns' => array('award_id'),
							'refTableClass'	=> 'Core_Db_Awards',
							'refColumns' => array('id')
			)
		);

	public function getByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('profile_id = ?', $profileId);
		return $this->fetchAll($select);
	}

	public function getByAwardId($awardId = null)
	{
		if (!$awardId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('award_id = ?', $awardId);
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