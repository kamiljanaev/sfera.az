<?php

class Core_Controller_Action_Helper_Captcha extends Zend_Controller_Action_Helper_Abstract
{
    public $id;
    public $html;
    public $captcha;

    public function __construct()
    {
        $config = Zend_Registry::get('systemconfig');
//        Core_Vdie::_($config->path->rootPublic.'fonts/Tahome-Bold.ttf');
        $this->captcha = new Zend_Captcha_Image(array(
            "imgDir"    => $config->path->rootPublic.'upload/captcha',
            "imgUrl"    => '/upload/captcha/',
            "imgAlt"    => 'captcha',
            "width"     => 150,
            "height"    => 80,
            "fsize"     => 21,
            "font"      => $config->path->rootPublic.'fonts/Tahoma-Bold.ttf',
            "dotNoiseLevel" => 30,
            "lineNoiseLevel"=> 3,
            "name"      => "captcha",
            "timeout"   => 1800,
            "wordlen"   => 5
        ));
    }
    
    public function direct($view) 
    {
        $this->generate($view);
    }
    
    public function generate($view) {
        $this->id = $this->captcha->generate();
        $this->html = $this->captcha->render($view);
    }
    
    public function isValid($value, $context)
    {
        return $this->captcha->isValid($value, $context);
    }
}