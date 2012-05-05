<?php
class Core_Db_Content_Tags_SubscribeLinks extends Core_Db_Table
{
	protected
		$_name = 'site_tags_subscribes',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Content_Tags_SubscribeLinks_Row',
		$_rowsetClass = 'Core_Db_Content_Tags_SubscribeLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			),
			'Ref_To_Tag' => array(
							'columns' => array('tag_id'),
							'refTableClass'	=> 'Core_Db_Content_Tags',
							'refColumns' => array('id')
			)
		);

    public function isExist($profileId = null, $tagId)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('tag_id = ?', $tagId);
        if ($this->fetchRow($select) instanceof Core_Db_Content_Tags_SubscribeLinks_Row) {
            return true;
        } else {
            return false;
        }
    }

    public function getByProfileIdTagId($profileId = null, $tagId)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('tag_id = ?', $tagId);
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

    public function getByTagId($tagId = null)
    {
        if (!$tagId) {
            return null;
        }
        $select = $this->select()->from($this);
        $select->where('tag_id = ?', $tagId);
        return $this->fetchAll($select);
    }

    public function getCountByTagId($tagId = null)
    {
        if (!$tagId) {
            return 0;
        }
        return $this->getByTagId($tagId)->count();
    }

    public function getCountByProfileId($profileId = null)
    {
        if (!$profileId) {
            return 0;
        }
        return $this->getByProfileId($profileId)->count();
    }

	public function deleteByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		return $this->delete('profile_id = '.$profileId);
	}
}