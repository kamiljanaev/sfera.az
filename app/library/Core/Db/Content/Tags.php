<?php
class Core_Db_Content_Tags extends Core_Db_Table
{
    const
        TAG_TYPE_NEWS = 1,
        TAG_TYPE_BLOGS = 2;
	protected
		$_name = 'view_content_tags',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Content_Tags_Row',
		$_rowsetClass = 'Core_Db_Content_Tags_Rowset';

    public function getIdTitleList()
    {
        return $this->fetchList('id', 'title');
    }

    public function getByType($typeId = null)
    {
        $select = $this->getSelect();
        if ($typeId !== null) {
            $select->where('type_id = ?', $typeId);
        }
        return $this->fetchAll($select)->fetchArray();
    }

    public function recalculateTags()
    {
        $tagsList = $this->fetchAll()->fetchArray();
        $tagsLinksModel = new Core_Db_Content_TagsLinks;
        foreach ($tagsList as $tagItem) {
            $tagsCount = $tagsLinksModel->getRowsCount('tag_id = '.$tagItem->id);
            $tagItem->cnt = $tagsCount;
            $tagItem->save();
        }
        return true;
    }
}