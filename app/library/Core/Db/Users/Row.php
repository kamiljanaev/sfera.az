<?php
class Core_Db_Users_Row extends Core_Db_Table_Row
{
	const
		DEFAULT_PASSWORD_LENGTH = 8,
		LOGIN_COLUMN = 'email',
		PASSWORD_COLUMN = 'password',
		RANDOM_PASSWORD_CHARS = '1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';

	private
		$_currentProfile = null,
		$_RolesLinks = null,
		$_RolesList = null;

	public function link($action='view')
	{
		return Core_View_Helper_Link :: user($this->id, $action);
	}

	public function getRoleLinks()
	{
		if ($this->_RolesLinks == null) {
			$roleLinksModel = new Core_Db_RolesLinks();
			$this->_RolesLinks = $this->findDependentRowset($roleLinksModel);
		}
		return $this->_RolesLinks;
	}

	public function getRoles()
	{
		if ($this->_RolesList == null) {
			$rolesLinks = $this->getRoleLinks();
			foreach ($rolesLinks as $linkItem) {
				$this->_RolesList[] = $linkItem->getRole();
			}
		}
		return $this->_RolesList;
	}

	public function getRolesNames()
	{
		$rolesList = $this->getRoles();
		$rolesNames = array();
		foreach ($rolesList as $roleItem) {
			$rolesNames[] = $roleItem->name;
		}
		return $rolesNames;
	}

	public function getRolesIds()
	{
		$rolesList = $this->getRoles();
		$rolesNames = array();
		if ($rolesList) {
			foreach ($rolesList as $roleItem) {
				$rolesNames[] = $roleItem->id;
			}
		}
		return $rolesNames;
	}

	public function getProfile()
	{
		if (!($this->_currentProfile instanceof Core_Db_Profiles_Row)) {
            $profiles = $this->findDependentRowset('Core_Db_Profiles', 'Ref_To_User')->fetchArray();
            if (count($profiles)) {
                $this->_currentProfile = $profiles[0];
            }
		}
		return $this->_currentProfile;
	}

	public function setPassword($password)
	{
		if (strlen($password) >= self::DEFAULT_PASSWORD_LENGTH) {
            parent::__set(self::PASSWORD_COLUMN, self::getPasswordHash($password));
			return true;
		} else {
            return false;
        }
	}

	public function setRandomPassword($length = self::DEFAULT_PASSWORD_LENGTH)
	{
		parent::__set(self::PASSWORD_COLUMN, self::getPasswordHash($password = self::generatePassword($length)));

		return $password;
	}

	public function checkPassword($password)
	{
		return parent::__get(self::PASSWORD_COLUMN) == self::getPasswordHash($password);
	}

	public function __set($name, $value)
	{
		if ($name == self::PASSWORD_COLUMN) {
			return $this->setPassword($value);
		} else {
			return parent::__set($name, $value);
		}
	}

	static public function getPasswordHash($password)
	{
		return md5($password);
	}

	public static function generatePassword($length)
	{
		$password = '';
		$max = strlen(self::RANDOM_PASSWORD_CHARS) - 1;
		while (0 < $length--) {
			$password .= substr(self::RANDOM_PASSWORD_CHARS, rand(0, $max), 1);
		}

		return $password;
	}

	public function getActivationCode()
	{
        return md5($this->id.$this->email);
	}

	public function activateUser()
	{
        $this->activated = 1;
        $this->verify_code = '';
        return $this->save();
	}

    public function checkVerifyCode($value, $autoActivate = false)
    {
        if ($this->verify_code == $value) {
            if ($autoActivate) {
                $this->activateUser();
            }
            return true;
        }
        return false;
    }

    public function setOnline()
    {
        $this->is_online = 1;
        $this->changed = Core_Date::now();
        return $this->save();
    }

}
