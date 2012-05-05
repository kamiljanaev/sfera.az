<?php
class Core_Response
{
	const
		RESPONSE_OK    = 1,
		RESPONSE_ERROR = 0;

	public
		$session,
		$code = 0,
		$status = 0,
		$command = null,
		$request = array(),
		$answer = array(),
		$messages = array();

	public function __construct()
	{
		$this->setCode(Core_Response::RESPONSE_ERROR);
		$this->setCommand(null);
		$this->setRequest(null);
		$this->setAnswer(null);
		$this->setMessages(null);
		$this->session = Zend_Session::getId();
	}

	public function setValues($code = null, $command= null, $request=null, $answer = null, $messages = null)
	{
		$this->setCode($code);
		$this->setCommand($command);
		$this->setRequest($request);
		$this->setAnswer($answer);
		$this->setMessages($messages);
	}

	public function setCode($value = null)
	{
		if ($value !== null) {
			$this->code = $value;
		}
	}
	
	public function getCode()
	{
		return $this->code;
	}

	public function setStatus($value = null)
	{
		if ($value !== null) {
			$this->status = $value;
		}
	}
	public function getStatus()
	{
		return $this->status;
	}

	public function updateSession()
	{
		$this->session = Zend_Session::getId();
	}
	
	public function getSession()
	{
		return $this->session;
	}

	public function setCommand($value = null)
	{
		if ($value !== null) {
			$this->command = $value;
		}
	}

	public function getCommand()
	{
		return $this->command;
	}

	public function setRequest($value = null)
	{
		if ($value !== null) {
			$this->request = $value;
		}
	}

	public function getRequest()
	{
		return $this->setRequest;
	}

	public function setAnswer($value = null)
	{
		if ($value !== null) {
			$this->answer = $value;
		}
	}

	public function getAnswer()
	{
		return $this->answer;
	}

	public function addMessage($value = null)
	{
		if ($value !== null) {
			array_push($this->messages, $value);
		}
	}

	public function setMessages($value = null)
	{
		if ($value !== null) {
			$this->messages = $value;
		}
	}

	public function getMessages()
	{
		return $this->messages;
	}

	public function setReply($value, $message)
	{
	}
}