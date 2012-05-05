<?php
class Core_View_Helper_Paginator extends Core_View_Helper
{
	function paginator($page, $total_pages, $count_on_page, $url, $page_param = 'page', $template = 'paginator.phtml')
	{
		$url = rtrim($url, '/');
		$this->_view->page = $page;
		$this->_view->total_pages = $total_pages;
		$this->_view->count_onpage = $count_on_page;
		$this->_view->url = $url;
        $this->_view->page_param = '';
        if (strlen($page_param)) {
            $this->_view->page_param = $page_param.'/';
        }

		$this->_view->is_first_page = ($page <= 1)?true:false;
		$this->_view->is_last_page = ($page >= $total_pages)?true:false;

		$this->_view->first_page_url = $url.'/'.$this->_view->page_param.'1';
		$this->_view->last_page_url = $url.'/'.$this->_view->page_param.$total_pages;

		$prev_page_number = ($page > 1)?$page-1:1;
		$next_page_number = ($page < $total_pages)?$page+1:$total_pages;
		$this->_view->prev_page_url = $url.'/'.$this->_view->page_param.$prev_page_number;
		$this->_view->next_page_url = $url.'/'.$this->_view->page_param.$next_page_number;

		$this->_view->any_page_url = $url.'/'.$this->_view->page_param;

		return $this->_view->render($template);
	}
}