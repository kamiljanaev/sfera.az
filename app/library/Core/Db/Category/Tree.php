<?php
class Core_Db_Category_Tree extends Zend_Db_Table_Mptt
{
	const
        CATEGORY_NEWS = 1,
        CATEGORY_ADS = 2,
        CATEGORY_NEWS_ALIAS = 'news-root-category',
        CATEGORY_NEWS_BAKU_ALIAS = 'news-baku-category',
        CATEGORY_NEWS_WORLD_ALIAS = 'news-world-category',
        CATEGORY_NEWS_AJERBAIJAN_ALIAS = 'news-ajerbaijan-category',
        CATEGORY_ADS_REALTY = 'ads-realty-category',
        CATEGORY_ADS_VACANCY = 'ads-vacancy-category',
        CATEGORY_ADS_ALIAS = 'ads-root-category';

	protected
		$_name = 'view_category_tree',
		$_primary = 'id',
//		$_rowClass = 'Core_Db_Catalog_Tree_Row',
		$_traversal = array(
			'left'          => 'left_id',
			'right'         => 'right_id',
			'column'        => 'id',
			'refColumn'     => 'parent_id',
			'order'			=> 'order'
		);

    public function fetchByParent($parentId = null, $is_active = null, $show_on_home = null)
    {
        if ($parentId) {
            $select = $this->select()->where('parent_id = ?', $parentId)->order('order');
        } else {
            $select = $this->select()->where('isnull(parent_id)')->order('order');
        }
        if ($is_active !== null) {
            $select->where('is_active = ?', $is_active);
        }
        if ($show_on_home !== null) {
            $select->where('show_on_home = ?', $show_on_home);
        }
        return $this->fetchAll($select);
    }

    public function getByAlias($alias = null)
    {
        if ($alias) {
            $select = $this->select()->where('alias = ?', $alias);
            return $this->fetchRow($select);
        } else {
            return null;
        }
    }

	public function moveNode($nodeId = null, $parentId = null, $position = 0)
	{
		if ($nodeId) {
			$select = $this->select()->where('`parent_id` = ?', $parentId)->order('order');
			$dataArray = $this->fetchAll($select)->fetchArray();
			$counter = 0;
			foreach ($dataArray as $dataItem) {
				$dataItem->order = $counter;
				$dataItem->save();
				$counter ++;
			}
			$currentNode = $this->getRowInfo($nodeId);
			$currentNode->parent_id = $parentId;
			$currentNode->order = $position;
			$currentNode->save();
			$select = $this->select()->where('`parent_id` = ?', $parentId)->where('`order` >= ?', $position)->where('`id` <> ?', $nodeId);
			$dataArray = $this->fetchAll($select)->fetchArray();
			foreach ($dataArray as $dataItem) {
				$dataItem->order = $dataItem->order + 1;
				$dataItem->save();
			}
			$this->_rebuildTreeTraversal();
			return true;
		} else {
			return false;
		}
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
