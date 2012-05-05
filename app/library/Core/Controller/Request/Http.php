<?php
class Core_Controller_Request_Http extends Zend_Controller_Request_Http
{
public function isXmlHttpRequest()
    {
        return (($this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest') || isset($_REQUEST['ajax']));
    }
    
    public function __set($key, $value)
    {
        $this->$key = $value;
    }
}