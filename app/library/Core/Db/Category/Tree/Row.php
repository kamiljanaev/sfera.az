<?php
class Core_Db_Catalog_Tree_Row extends Zend_Db_Table_Mptt_Row
{
	public function getItemUrl()
	{
		if (strlen($this->url)) {
			return $this->url;
		} else {
			return Core_View_Helper_GetUrl::getCorrectUrl('category/'.$this->id);
		}
	}
}