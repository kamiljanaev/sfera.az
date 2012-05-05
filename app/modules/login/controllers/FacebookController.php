<?php
class Login_FacebookController extends Zend_Controller_Action
{
    protected
        $_fbAppID = '',
        $_fbAppSecret = '';

    public function init()
    {
        $this->_config = Zend_Registry::get('systemconfig');
        $this->_fbAppID = $this->_config->facebook->appID;
        $this->_fbAppSecret = $this->_config->facebook->appSecret;
    }
    
	public function indexAction()
	{
        require_once('library/Facebook/facebook.php');
        $facebookObject = new Facebook(array(
            'appId'  => $this->_fbAppID,
            'secret' => $this->_fbAppSecret,
            'cookie' => true,
        ));
        $fbSession = $facebookObject->getSession();

        if ($fbSession) {
            try {
                $uid = $facebookObject->getUser();
                $me = $facebookObject->api('/me');
            } catch (FacebookApiException $e) {
                $loginUrl = $facebookObject->getLoginUrl();
                $this->_redirect($loginUrl);
            }
        }
        if (!$me) {
            $loginUrl = $facebookObject->getLoginUrl();
            $this->_redirect($loginUrl);
        }

        $usersModel = new Core_Db_Users();
        $fbUserItem = $usersModel->getByFBID($me['id']);
        if ($fbUserItem) {
            $result = $this->loginUser($fbUserItem);
        } else {
            $result = $this->createUser($me);
        }
        if ($result) {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile/edit'));
        } else {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('/'));
        }
	}

    protected function loginUser($userRow)
    {
        $authAdapter = new Core_Auth_Adapter('login', 'password', '?');
        $authAdapter->setIdentity($userRow->login)->setCredential($userRow->password);
        $auth = Core_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            if (Zend_Registry::isRegistered('sessionCache')) {
                $sessionCache = Zend_Registry::get('sessionCache');
                $sessionCache->remove('coreAclModules');
            }
            Core_Acl_Factory::createAcl(null, Core_Acl_Factory::CORE_ACL_TYPE_MODULES);
            return true;
        }
        return false;
    }

    protected function createUser($userData)
    {
        $usersModel = new Core_Db_Users();
        $rolesModel = new Core_Db_Roles();
        $rolesLinkModel = new Core_Db_RolesLinks();
        $profilesModel = new Core_Db_Profiles();
        $newUserData = array(
            'login' => 'fb_login_'.$userData['username'],
            'email' => '',
            'password' => Core_Db_Users_Row::generatePassword(8),
            'activated' => 1,
            'fb_id' => $userData['id']
        );
        $newUserRow = $usersModel->createRow($newUserData);
        $newUserRow->save();
        $userId = $newUserRow->id;
        $rolesLinkModel->deleteByUserId($userId);
        $roles[] = $rolesModel->getByName();
        $roles[] = $rolesModel->getByName('member');
        foreach ($roles as $role) {
            if ($role instanceof Core_Db_Roles_Row) {
                $newRoleLink = $rolesLinkModel->createRow();
                $newRoleLink->user_id = $userId;
                $newRoleLink->role_id = $role->id;
                $newRoleLink->save();
            }
        }
        $currentProfile = $profilesModel->createRow();
        $currentProfile->user_id = $newUserRow->id;
        $currentProfile->firstname = $userData['first_name'];
        $currentProfile->lastname = $userData['last_name'];
        $currentProfile->alias = $userData['username'];
        $currentProfile->facebook = $userData['username'];
        if (array_key_exists('gender', $userData)) {
            if ($userData['gender'] == 'male') {
                $currentProfile->gender = 1;
            }
        }
        $currentProfile->user_id = $userId;
        $currentProfile->save();
        $this->loginUser($newUserRow);
    }
}
