<?php
class Core_Mail extends Zend_Mail
{
	public function __construct($charset = 'UTF-8')
	{
		$systemconfig = Zend_Registry::get('systemconfig');
		if ($systemconfig->mail->useTransport) {
			$config = $systemconfig->mail->transport->smtp->config->toArray();
			$server = $systemconfig->mail->transport->smtp->server;
			$mailTransport = new Zend_Mail_Transport_Smtp($server, $config);
			Zend_Mail::setDefaultTransport($mailTransport);
		}
		parent::__construct($charset);
	}

	public static function checkMail($email = null)
	{
		if (!$email) {
			return false;
		}
		$email_arr = explode("@" , $email);
		$host = $email_arr[1];
		if (!getmxrr($host, $mxhostsarr)) {
			return false;
		}
		return true;
	}

	public function sendMail($mailData = array('templateContent' => '', 'templateFile' => '', 'templateAlias' => '', 'toMail' => '', 'toName' => '', 'fromMail' => '', 'fromName' => '', 'mailSubject' => '', 'templateSymbol' => '', 'aditionalData' => array(), 'attachmentFiles' => array()))
	{
		$systemconfig = Zend_Registry::get('systemconfig');
		$templateFile = array_key_exists('templateFile', $mailData)?$mailData['templateFile']:'';
		$templateAlias = array_key_exists('templateAlias', $mailData)?$mailData['templateAlias']:'';
		$templateContent = array_key_exists('templateContent', $mailData)?$mailData['templateContent']:'';
		$toMail = array_key_exists('toMail', $mailData)?$mailData['toMail']:'';
		$toName = array_key_exists('toName', $mailData)?$mailData['toName']:'';
		$fromMail = array_key_exists('fromMail', $mailData)?$mailData['fromMail']:$systemconfig->mail->default->from_email;
		$fromName = array_key_exists('fromName', $mailData)?$mailData['fromName']:$systemconfig->mail->default->from_name;
		$mailSubject = array_key_exists('mailSubject', $mailData)?$mailData['mailSubject']:'';
		$templateSymbol = array_key_exists('templateSymbol', $mailData)?$mailData['templateSymbol']:'*';
		$aditionalData = array_key_exists('aditionalData', $mailData)?$mailData['aditionalData']:array();
		$attachmentFiles = array_key_exists('attachmentFiles', $mailData)?$mailData['attachmentFiles']:array();
		if (strlen($templateContent)) {
	        $mailBody = $templateContent;
		} else {
	        $mailBody = $this->getTemplateContent($templateFile, $templateAlias);
		}

		foreach ($attachmentFiles as $attachFile) {
			if ($attachmentItem = $this->createAttachItem($attachFile)) {
				$this->addAttachment($attachmentItem);
			}
		}

        $mailBody = $this->prepareBody($mailBody, $templateSymbol, $aditionalData);
		if (strlen($toMail)) {
			$this->setBodyHtml($mailBody);
			$this->setFrom($fromMail, $fromName);
			$this->addTo($toMail, $toName);
			$this->setSubject($mailSubject);
			$result = $this->send();
			return $result;
		} else {
			return false;
		}
	}

	protected function createAttachItem($filename)
	{
		if (file_exists($filename)) {
			$path_parts = pathinfo($filename);
			$attach = new Zend_Mime_Part(file_get_contents($filename));
			$attach->type = 'application/octet-stream';
			$attach->disposition = Zend_Mime::DISPOSITION_INLINE;
			$attach->encoding = Zend_Mime::ENCODING_BASE64;
			$attach->filename = $path_parts['basename'];
			$attach->id = md5(time());
			$attach->description = $path_parts['basename'];
			return $attach;
		}else{
			return false;
		}
	}

	protected function getTemplateContent($templateFile = '', $templateAlias = '')
	{
/*		$templateModel = new Core_Db_Mail_Template;
		$templateRow = $templateModel->getByAlias($templateAlias);
		if ($templateRow instanceof Core_Db_Mail_Template_Row) {
			return $templateRow->content;
		}*/
		if (file_exists($templateFile)) {
			return file_get_contents($templateFile);
		}
		return '';
	}

	protected function prepareBody($content, $templateSymbol, $aditionalData = array())
	{
		foreach ($aditionalData as $paramKey => $paramItem) {
            $content = str_replace($templateSymbol.$paramKey.$templateSymbol, $paramItem, $content);
		}
		return $content;
	}

}