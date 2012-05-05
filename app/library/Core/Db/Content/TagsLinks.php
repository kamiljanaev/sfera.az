<?php
class Core_Db_Content_TagsLinks extends Core_Db_Table
{
	protected
		$_name = 'site_content_tagslinks',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Content_TagsLinks_Row',
		$_rowsetClass = 'Core_Db_Content_TagsLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Tag' => array(
							'columns' => array('tag_id'),
							'refTableClass'	=> 'Core_Db_Content_Tags',
							'refColumns' => array('id')
			)
		);

    public function isExist($tagId = null, $typeId = null, $itemId = null)
    {
        $select = $this->select()->from($this);
        $select->where('tag_id = ?', $tagId);
        $select->where('type = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        if ($this->fetchRow($select) instanceof Core_Db_Content_TagsLinks_Row) {
            return true;
        } else {
            return false;
        }
    }

	public function getByItemId($itemId = null, $itemType = null)
	{
        if (!$itemId) {
            return null;
        }
        if (!$itemType) {
            return null;
        }
		$select = $this->select()->from($this);
        $select->where('item_id = ?', $itemId);
        $select->where('type_id = ?', $itemType);
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
            return null;
        }
        $select = $this->select()->from($this);
        $select->where('tag_id = ?', $tagId);
        return $this->fetchAll($select);
    }

    public function deleteByItemId($itemId = null, $itemType = null)
    {
        if (!$itemId) {
            return null;
        }
        if (!$itemType) {
            return null;
        }
        return $this->delete('item_id = '.$itemId.' and type_id = '.$itemType);
    }
}