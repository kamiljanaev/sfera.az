<?php
class Core_View_Helper_Link extends Core_View_Helper
{
	public static function makeLink($title, $url, $options = array())
	{
		$class = array_key_exists('class', $options)?' class = "'.$options['class'].'"':'';
		$id = array_key_exists('id', $options)?' id = "'.$options['id'].'"':'';
		return '<a'.$class.$id.' href="'.Core_View_Helper_GetUrl::getCorrectUrl($url).'">'.$title.'</a>';
	}

    public static function award($id, $action='view')
    {
        $result = '/admin/module/profile/awards/%s/id/%d';
        return sprintf($result, $action, $id);
    }

    public static function content_tag($id, $action='view')
    {
        $result = '/admin/module/content/tags/%s/id/%d';
        return sprintf($result, $action, $id);
    }

    public static function user($id, $action='view')
    {
        $result = '/admin/module/users/index/%s/id/%d';
        return sprintf($result, $action, $id);
    }

    public static function profile($id, $action='view')
    {
        $result = '/admin/module/profiles/index/%s/id/%d';
        return sprintf($result, $action, $id);
    }

    public static function document($id, $action='view')
    {
        $result = '/admin/module/profiles/documents/%s/id/%d';
        return sprintf($result, $action, $id);
    }

	public static function role($id, $action='view')
	{
		$result = '';
		switch($action) {
			default:
				$result = '/admin/module/permissions/index/index/role_id/'.$id;
				break;
		}
		return sprintf($result, $action, $id);
	}

	public static function dictionaryLink($type, $id, $action='view')
	{
		$result = '';
		switch($action) {
			default:
				$result = '/admin/module/dictionary/%s/%s/id/%d';
				break;
		}
		return sprintf($result, $type, $action, $id);
	}

	public static function commonLink($module = '', $controller = '', $action='', $param, $value)
	{
		$result = '';
		switch($action) {
			default:
				$result = '/admin/module/%s/%s/%s/%s/%s';
				break;
		}
		return sprintf($result, $module, $controller, $action, $param, $value);
	}

    public static function contentLink($type, $id, $action='view')
    {
        $result = '';
        switch($action) {
            default:
                $result = '/admin/module/content/%s/%s/id/%d';
                break;
        }
        return sprintf($result, $type, $action, $id);
    }


}