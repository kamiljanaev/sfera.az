<?php
class Core_View_Helper_ErrorContainer extends Core_View_Helper
{
	protected static $errors;

	public function ErrorContainer($message = null)
	{
		if (!is_array(self::$errors)) {
			self::$errors  = array();
		}

		if ($message !== null) {
			self::$errors[] = $message;
		} else {
			return self::$errors;
		}
	}
}