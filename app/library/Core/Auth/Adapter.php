<?php
class Core_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	const
		IDENTITY_COLUMN = 'email',
		CREDENTIAL_COLUMN = 'password',
		CREDENTIAL_TREATMENT = 'md5(?)';
	private
		$_conditions = array(),
		$_usersModel = null,
		$_identityColumn = null,
		$_credentialColumn = null,
		$_identity = null,
		$_credential = null,
		$_credentialTreatment = null,
		$_authenticateResultInfo = null,
		$_resultRow = null;

	public function __construct( $identityColumn = '',  $credentialColumn = '', $credentialTreatment = '')
	{
		$this->_usersModel = new Core_Db_Users();
		$this->_identityColumn = ($identityColumn == '')?self::IDENTITY_COLUMN:$identityColumn;
		$this->_credentialColumn = ($credentialColumn == '')?self::CREDENTIAL_COLUMN:$credentialColumn;
		$this->_credentialTreatment = ($credentialTreatment == '')?self::CREDENTIAL_TREATMENT:$credentialTreatment;

	}

	public function authenticate()
	{
		$this->_authenticateSetup();
		$select = $this->_authenticateCreateSelect();
		$resultIdentities = $this->_usersModel->fetchAll($select);
		if ( ($authResult = $this->_authenticateValidateResultset($resultIdentities)) instanceof Zend_Auth_Result) {
			return $authResult;
		}
		$authResult = $this->_authenticateValidateResult($resultIdentities->current());
		return $authResult;
	}

	public function addAuthenticateConditions($where)
	{
		$this->_conditions[] = $where;
		return $this->_conditions;
	}

	protected function _authenticateValidateResult($resultIdentity)
	{
		$this->_resultRow = $resultIdentity->id;
		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
		$this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
		$this->_authenticateResultInfo['identity'] = $this->_resultRow;
		return $this->_authenticateCreateAuthResult();
	}

	protected function _authenticateCreateAuthResult()
	{
		return new Zend_Auth_Result(
				$this->_authenticateResultInfo['code'],
				$this->_authenticateResultInfo['identity'],
				$this->_authenticateResultInfo['messages']
		);
	}

	protected function _authenticateValidateResultSet($resultIdentities)
	{
		if (count($resultIdentities) < 1) {
			$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
			$this->_authenticateResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
			return $this->_authenticateCreateAuthResult();
		} elseif (count($resultIdentities) > 1) {
			$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
			$this->_authenticateResultInfo['messages'][] = 'More than one record matches the supplied identity.';
			return $this->_authenticateCreateAuthResult();
		}

		return true;
	}

	protected function _authenticateCreateSelect()
	{
		if (empty($this->_credentialTreatment) || (strpos($this->_credentialTreatment, '?') === false)) {
			$this->_credentialTreatment = '?';
		}
		$dbSelect = $this->_usersModel->select();
		foreach ($this->_conditions as $condition) {
			$dbSelect->where($condition);
		}
		$dbSelect->where($this->_usersModel->getAdapter()->quoteIdentifier($this->_identityColumn, true) . ' = ?', $this->_identity)
				->where($this->_usersModel->getAdapter()->quoteIdentifier($this->_credentialColumn, true) . ' = '. $this->_credentialTreatment, $this->_credential);
		return $dbSelect;
	}

	protected function _authenticateSetup()
	{
		$exception = null;
		if ($this->_usersModel == null) {
			$exception = 'A users data model must be supplied for the Core_Auth_Adapter authentication adapter.';
		} elseif ($this->_identityColumn == '') {
			$exception = 'An identity column must be supplied for the Core_Auth_Adapter authentication adapter.';
		} elseif ($this->_credentialColumn == '') {
			$exception = 'A credential column must be supplied for the Core_Auth_Adapter authentication adapter.';
		} elseif ($this->_identity == '') {
			$exception = 'A value for the identity was not provided prior to authentication with Core_Auth_Adapter.';
		} elseif ($this->_credential === null) {
			$exception = 'A credential value was not provided prior to authentication with Core_Auth_Adapter.';
		}
		if (null !== $exception) {
			throw new Zend_Auth_Adapter_Exception($exception);
		}
		$this->_authenticateResultInfo = array(
				'code'     => Zend_Auth_Result::FAILURE,
				'identity' => $this->_identity,
				'user' => null,
				'messages' => array()
		);
		return true;
	}

	public function setIdentityColumn($identityColumn)
	{
		$this->_identityColumn = $identityColumn;
		return $this;
	}

	public function setCredentialColumn($credentialColumn)
	{
		$this->_credentialColumn = $credentialColumn;
		return $this;
	}

	public function setCredentialTreatment($treatment)
	{
		$this->_credentialTreatment = $treatment;
		return $this;
	}

	public function setIdentity($value)
	{
		$this->_identity = $value;
		return $this;
	}

	public function setCredential($credential)
	{
		$this->_credential = $credential;
		return $this;
	}
}