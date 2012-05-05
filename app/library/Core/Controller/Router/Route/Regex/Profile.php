<?php
class Core_Controller_Router_Route_Regex_Profile extends Zend_Controller_Router_Route_Regex
{
	public function match($path, $partial = false)
    {
    	$return = parent::match($path, $partial);
    	if ($return !== false){
	        $model = new Core_Db_Profiles();
			$element = $model->getByAlias($return['alias']);
			if ($element == null) $return = false;
    	}
        return $return;
    }
}