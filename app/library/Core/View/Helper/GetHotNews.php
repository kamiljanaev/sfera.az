<?php
class Core_View_Helper_GetHotNews extends Core_View_Helper
{
	function getHotNews()
	{
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultOrderField('public_date');
        $newsModel->setDefaultOrderSequence('desc');
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $this->_view->hotNewsList = $newsModel->getHotNews();
        $this->_view->hotNewsConfig = array(
            1 => array(
                'class' => 'first',
                'img_type' => Core_Image::HOT_NEWS_FIRST,
                'img_width' => 378,
                'img_height' => 239,
            ),
            2 => array(
                'class' => 'second',
                'img_type' => Core_Image::HOT_NEWS_SECOND,
                'img_width' => 196,
                'img_height' => 239,
            ),
            3 => array(
                'class' => 'third',
                'img_type' => Core_Image::HOT_NEWS_THIRD,
                'img_width' => 157,
                'img_height' => 158,
            ),
            4 => array(
                'class' => 'forth',
                'img_type' => Core_Image::HOT_NEWS_FORTH,
                'img_width' => 219,
                'img_height' => 158,
            ),
            5 => array(
                'class' => 'fifth',
                'img_type' => Core_Image::HOT_NEWS_FIFTH,
                'img_width' => 196,
                'img_height' => 158,
            )
        );
        return $this->_view->render('hotNews.phtml');
	}
}
