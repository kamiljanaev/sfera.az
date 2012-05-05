<?php
class Core_View_Helper_GetTagsBlock extends Core_View_Helper
{
	function getTagsBlock()
	{
        $tagsModel = new Core_Db_Content_Tags;
        $tagsList = $tagsModel->fetchAll();
        $this->_view->tagsArray = array();
        foreach ($tagsList as $tagItem) {
            switch ($tagItem->cnt) {
                case 1:
                    $fontSize = false;
                    break;
                case 2:
                    $fontSize = '8pt';
                    break;
                case 3:
                    $fontSize = '9pt';
                    break;
                case 4:
                    $fontSize = '10pt';
                    break;
                case 5:
                    $fontSize = '11pt';
                    break;
                case 6:
                    $fontSize = '12pt';
                    break;
                case 7:
                    $fontSize = '13pt';
                    break;
                case 8:
                    $fontSize = '14pt';
                    break;
                case 9:
                    $fontSize = '15pt';
                    break;
                case 10:
                    $fontSize = '16pt';
                    break;
                default:
                    $fontSize = '17pt';
                    break;
            }
            if ($fontSize) {
                $this->_view->tagsArray[] = array(
                    'id' => $tagItem->id,
                    'title' => $tagItem->title,
                    'logo' => $tagItem->logo,
                    'count' => $tagItem->cnt,
                    'font' => $fontSize
                );
            }
        }
        return $this->_view->render('getTagsBlock.phtml');
	}
}
