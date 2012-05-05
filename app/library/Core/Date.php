<?php

class Core_Date extends Zend_Date
{
    public function toString($format = null, $type = null, $locale = null)
    {
        if ($format === null) {
            if (Zend_Registry::isRegistered('DateFormat')) {
                $format = Zend_Registry::get('DateFormat');
            }
        }
        if ($locale === null) {
            if (Zend_Registry::isRegistered('Zend_Layout')) {
                $locale = Zend_Registry::get('Zend_Layout');
            }
        }
        return parent::toString($format, $type, $locale);
    }

}
