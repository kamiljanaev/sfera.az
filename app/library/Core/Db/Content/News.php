<?php
class Core_Db_Content_News extends Core_Db_Table
{
    const
        NEWS_HIDDEN = 0,
        NEWS_VISIBLE = 1,
        NEWS_ALL = null;
	protected
		$_name = 'view_news',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Content_News_Row',
		$_rowsetClass = 'Core_Db_Content_News_Rowset',
        $_referenceMap	= array(
            'Ref_To_User' => array(
                            'columns' => array('user_id'),
                            'refTableClass'	=> 'Core_Db_Users',
                            'refColumns' => array('id')
            ),
            'Ref_To_Category' => array(
                            'columns' => array('category_id'),
                            'refTableClass'	=> 'Core_Db_Category_Tree',
                            'refColumns' => array('id')
            )
        ),
        $_defaultCategory = null,
        $_currentUser = null,
        $_defaultStatus = self::NEWS_ALL;

    public function setCurrentUser($userId = null)
    {
        $this->_currentUser = $userId;
    }

    public function getCurrentUser()
    {
        return $this->_currentUser;
    }

    public function setDefaultCategory($catId = null)
    {
        $this->_defaultCategory = $catId;
    }

    public function getDefaultCategory()
    {
        return $this->_defaultCategory;
    }

    public function setDefaultStatus($defaultStatus = self::NEWS_ALL)
    {
        if (in_array($defaultStatus, array(self::NEWS_ALL, self::NEWS_HIDDEN, self::NEWS_VISIBLE))) {
            $this->_defaultStatus = $defaultStatus;
        } else {
            $this->_defaultStatus = self::NEWS_ALL;
        }
    }

    public function getDefaultStatus()
    {
        return $this->_defaultStatus;
    }

	public function getById( $id )
	{
		$select = $this->select()->where( '`id` = ?', $id );
		return $this->fetchRow($select);
	}

    public function getByUserId( $userId )
    {
        $select = $this->select()->where( '`user_id` = ?', $userId );
        return $this->fetchAll($select)->fetchArray();
    }

    public function getHotNews()
    {
        $select = $this->getSelect();
        $select->where('is_hot = 1');
        return $this->fetchAll($select)->fetchArray();
    }

    public function getNewsByTag($tagId = null, $page = null, $rp = null, $sortname = null, $sortorder = null)
    {
        if (!$tagId) {
            return null;
        }
        $tagsLinksModel = new Core_Db_Content_TagsLinks;
        $newsTableName = $this->getTableName();
        $tagsLinksTableName = $tagsLinksModel->getTableName();
        $select = parent::getSelect();
        $select->join(array('tl' => $tagsLinksTableName), '`tl`.`item_id` = `'.$newsTableName.'`.`id`', array());
        $select->where('`tl`.`type_id` = ?', Core_Db_Content_Tags::TAG_TYPE_NEWS);
        if (is_array($tagId)) {
            $select->where('`tl`.`tag_id` in (?)', join(',', $tagId));
        } else {
            $select->where('`tl`.`tag_id` = ?', $tagId);
        }
        if ($sortname) {
            if ($sortorder === null) {
                $sortorder = 'desc';
            }
            $select->order($newsTableName.'.'.$sortname." ".$sortorder);
        }
        if (($page !== null)&&($rp !== null)) {
            $start = (($page-1) * $rp);
            $rp = $rp;
            $select->limit($rp, $start);
        }
        return $this->fetchAll($select);
    }

    public function getCountNewsByTag($tagId = null)
    {
        $newsList = $this->getNewsByTag($tagId);
        if (!$newsList) {
            return 0;
        } else {
            return $newsList->count();
        }
    }

    protected function getSelect()
    {
        $select = parent::getSelect();
        if ($this->_currentUser !== null) {
            $select->where('user_id = ?', $this->_currentUser);
        }
        if ($this->_defaultCategory !== null) {
            $select->where('category_id = ?', $this->_defaultCategory);
        }
        if ($this->_defaultStatus !== self::NEWS_ALL) {
            $select->where('activated = ?', $this->_defaultStatus);
        }
        return $select;
    }
}