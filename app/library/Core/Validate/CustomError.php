<?php
class Core_Validate_CustomError extends Zend_Validate_Abstract
{
	const
		SOME_ERROR = 'someError';

	protected
		$_messageTemplates = array(
				self::SOME_ERROR => "is not valid"
			);

	public function isValid($value)
	{
		$valueString = (string) $value;
		$this->_setValue($valueString);
		$this->_error();
		return false;
	}
}