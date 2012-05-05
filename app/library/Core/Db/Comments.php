<?php
class Core_Db_Comments extends Zend_Db_Table_Mptt
{
	const
        C_NEWS = 1,
        C_BLOGS = 2;

	protected
		$_name = 'view_comments_tree',
		$_primary = 'id',
//		$_rowClass = 'Core_Db_Catalog_Tree_Row',
		$_traversal = array(
			'left'          => 'left_id',
			'right'         => 'right_id',
			'column'        => 'id',
			'refColumn'     => 'parent_id',
			'order'			=> 'order'
		);

    public function getRowInfo($id)
    {
        if ($id) {
            $key_field = $this->getKeyField();
            $select = $this->select()->from($this);
            $select->where($key_field.' = ?', $id);
            return $this->fetchRow($select);
        }
        return false;
    }
    public function fetchByParent($parentId = null, $itemId = null, $typeId = null)
    {
        if ($parentId) {
            $select = $this->select()->where('parent_id = ?', $parentId)->order('order');
        } else {
            $select = $this->select()->where('isnull(parent_id)')->order('order');
        }
        if ($itemId) {
            $select->where('item_id = ?', $itemId);
        }
        if ($typeId !== null) {
            $select->where('type_id = ?', $typeId);
        }
        return $this->fetchAll($select);
    }

    public function fetchTree($itemId = null, $typeId = null)
    {
        $select = $this->select();
        if ($itemId) {
            $select->where('node.item_id = ?', $itemId);
        }
        if ($typeId !== null) {
            $select->where('node.type_id = ?', $typeId);
        }
        return parent::fetchTree(null, $select);
    }

	public function hasDescendent($nodeId = null)
	{
		if ($nodeId) {
			return (int) $this->fetchRow($this->select()->from($this, array('cnt' => 'count(id)'))->where('parent_id = ?', $nodeId))->cnt;
		} else {
			return false;
		}
	}

    public function fetchTreeById($nodeId = null, $depth = 2)
    {
        $treeLayerRowset = $this->fetchByParent($nodeId)->fetchArray();
        $treeLayer = array();
        foreach ($treeLayerRowset as $treeLayerRow) {
            $treeLayerItem = $treeLayerRow->toArray();
            $treeLayerItem['url'] = $treeLayerRow->getItemUrl();
            $treeLayerItem['children'] = $this->fetchTreeById($treeLayerRow->id, $depth);
            $treeLayer[] = $treeLayerItem;
        }
        return $treeLayer;
    }

}
