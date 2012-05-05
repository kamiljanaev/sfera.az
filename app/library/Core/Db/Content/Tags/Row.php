<?php
class Core_Db_Content_Tags_Row extends Core_Db_Table_Row
{
	public function hasAssignedProfiles()
	{
		return count($this->findDependentRowset('Core_Db_TagsLinks', 'Ref_To_Tag'));
	}

	public function link($action='view')
	{
		return Core_View_Helper_Link :: content_tag($this->id, $action);
	}

    public function getLogo($logoSize = Core_Image::AVATAR_100)
    {
        if ($this->logo) {
            return Core_Image::getImagePath($this->logo, $logoSize);
        } else {
            return "";
        }
    }

    public function getSubscribers()
    {
        $tagsSubscribesModel = new Core_Db_Content_Tags_SubscribeLinks;
        return $tagsSubscribesModel->getByTagId($this->id);
    }

    public function getCountSubscribers()
    {
        $tagsSubscribesModel = new Core_Db_Content_Tags_SubscribeLinks;
        return $this->getSubscribers()->count();
    }

}