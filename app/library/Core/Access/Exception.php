<?php
class Core_Access_Exception extends Exception
{
	const 
		EXCEPTION_ACCESS = 0,
		EXCEPTION_SECTION_ACCESS = 1,
		EXCEPTION_ARTICLE_ACCESS = 2;

	public function __construct($message, $code =  self::EXCEPTION_ACCESS)
	{
		parent::__construct($message, $code);
	}
}