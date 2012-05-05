<?php
require("library/Twitter/twitteroauth.php");
class Login_TwitterController extends Zend_Controller_Action
{
    protected
        $_twConsumerKey = '',
        $_twConsumerSecret = '';

    public function init()
    {
        $this->_config = Zend_Registry::get('systemconfig');
        $this->_twConsumerKey = $this->_config->twitter->key;
        $this->_twConsumerSecret = $this->_config->twitter->secret;
    }

    public function getAction()
    {
        if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
            $twitteroauth = new TwitterOAuth($this->_twConsumerKey, $this->_twConsumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
            $_SESSION['access_token'] = $access_token;
            $userData = $twitteroauth->get('account/verify_credentials');

            $usersModel = new Core_Db_Users();
            $twUserItem = $usersModel->getByTwID($userData->id);
            if ($twUserItem) {
                $result = $this->loginUser($twUserItem);
            } else {
                $result = $this->createUser($userData);
            }
            if ($result) {
                $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile/edit'));
            } else {
                $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('/'));
            }
        } else {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('/login/twitter'));
        }

    }
    
	public function indexAction()
	{
        $twitteroauth = new TwitterOAuth($this->_twConsumerKey, $this->_twConsumerSecret);
        $request_token = $twitteroauth->getRequestToken(Core_View_Helper_GetUrl::getCorrectUrl('/login/twitter/get', true));
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        if ($twitteroauth->http_code == 200) {
            $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
            $this->_redirect($url);
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
            'login' => 'tw_login_'.$userData->screen_name,
            'email' => '',
            'password' => Core_Db_Users_Row::generatePassword(8),
            'activated' => 1,
            'tw_id' => $userData->id
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
        $userName = explode(" ", $userData->name, 2);
        $currentProfile->firstname = $userName[0];
        $currentProfile->lastname = '';
        if (array_key_exists(1, $userName)) {
            $currentProfile->lastname = $userName[1];
        }
        $currentProfile->alias = $userData->screen_name;
        $currentProfile->twitter = $userData->screen_name;
        $currentProfile->user_id = $userId;
        $currentProfile->save();
        $this->loginUser($newUserRow);
    }
}
