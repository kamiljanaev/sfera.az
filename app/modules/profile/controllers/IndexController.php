<?php
class Profile_IndexController extends Core_Controller_Action
{
    public function indexAction()
    {
        $profilesModel = new Core_Db_Profiles();
        $this->view->profileData = null;
        if ($this->_currentUser) {
            $currentProfile = $this->_currentUser->getProfile();
            if (!$currentProfile) {
                $currentProfile = $profilesModel->createRow();
                $currentProfile->user_id = $this->_currentUser->id;
                $currentProfile->save();
            }
            $this->view->currentProfileData = $currentProfile;
            $this->view->currentUserData = $this->_currentUser;
//            $statusesModel = new Core_Db_Statuses();
//            $this->view->statusesData = $statusesModel->getByUserId($this->_currentUser->id);
//        } else {
//            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
        $profileId = $this->_getParam('id', null);
        if ($profileId) {
            $this->view->profileData = $profilesModel->getRowInfo($profileId);
            if (!$this->view->profileData) {
                throw new Zend_Controller_Dispatcher_Exception($this->_translate->translate('message-profile-not-found'));
            }
            $this->view->userData = $this->view->profileData->getUser();
        } elseif (!$profileId&&$this->_currentUser) {
            $this->view->profileData = $currentProfile;
            $this->view->userData = $this->_currentUser;
        } else {
            throw new Zend_Controller_Dispatcher_Exception($this->_translate->translate('message-user-not-logged'));
        }
        $statusesModel = new Core_Db_Statuses();
        $this->view->statusesData = $statusesModel->getByUserId($this->view->userData->id);
    }

    public function savedAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
    }
    
    public function editAction()
    {
        $this->view->profileData = null;
        $this->view->formData = array();
        $this->view->isEditProfile = true;
        if ($this->_currentUser) {
            $currentProfile = $this->_currentUser->getProfile();
            if (!$currentProfile) {
                $profilesModel = new Core_Db_Profiles();
                $currentProfile = $profilesModel->createRow();
                $currentProfile->user_id = $this->_currentUser->id;
                $currentProfile->save();
            }
            $this->view->currentProfileData = $this->view->profileData = $currentProfile;
            $this->view->currentUserData = $this->view->userData = $this->_currentUser;
            $this->view->profileForm = $this->getProfileForm($currentProfile);
            if ($this->_request->isPost()) {
                $profileData = $_POST;
                $profileData['birthdate'] = $profileData['birthday-day'].'.'.$profileData['birthday-month'].'.'.$profileData['birthday-year'];
                $imageObject = new Core_Image();
                $result = $imageObject->uploadImages('/upload/');
                if (array_key_exists('avatar', $result)) {
                    $profileData['avatar'] = $result['avatar']['path'];
                }
                if ($this->view->profileForm->isValid($profileData)) {
                    $formValid = true;
                    $email = $this->_getParam('email', null);
                    $emailValidator = new Zend_Validate_EmailAddress();
                    $this->view->formData['email_correct'] = array('value' => $email, 'valid' => true, 'message' => '');
                    if (!$emailValidator->isValid($email)) {
                        $formValid = false;
                        $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-email'));
                        $this->view->formData['email_correct'] = array('value' => $email, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-email'));
                    }
                    $emailValidator = new Core_Validate_Users_Email_NoRecordExists($this->_currentUser->id);
                    $this->view->formData['email_exist'] = array('value' => $email, 'valid' => true, 'message' => '');
                    if (!$emailValidator->isValid($email)) {
                        $formValid = false;
                        $this->_flashMessenger->addMessage($this->_translate->translate('validation-email-already-exist'));
                        $this->view->formData['email_exist'] = array('value' => $email, 'valid' => false, 'message' => $this->_translate->translate('validation-email-already-exist'));
                    }
                    $alias = $this->_getParam('alias', null);
                    $this->view->formData['alias'] = array('value' => $alias, 'valid' => true, 'message' => '');
                    if (!strlen($alias)) {
                        $formValid = false;
                        $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-phone'));
                        $this->view->formData['alias'] = array('value' => $alias, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-nickname'));
                    }
                    $firstname = $this->_getParam('firstname', null);
                    $this->view->formData['firstname'] = array('value' => $firstname, 'valid' => true, 'message' => '');
                    if (!strlen($firstname)) {
                        $formValid = false;
                        $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-nickname'));
                        $this->view->formData['firstname'] = array('value' => $firstname, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-nickname'));
                    }
                    $lastname = $this->_getParam('lastname', null);
                    $this->view->formData['lastname'] = array('value' => $lastname, 'valid' => true, 'message' => '');
                    if (!strlen($lastname)) {
                        $formValid = false;
                        $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-lastname'));
                        $this->view->formData['lastname'] = array('value' => $lastname, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-lastname'));
                    }
                    $newpassword = $this->_getParam('newpassword', null);
                    $this->view->formData['newpassword'] = array('value' => $newpassword, 'valid' => true, 'message' => '');
                    if (strlen($newpassword)) {
                        $retypepassword = $this->_getParam('retypepassword', null);
                        $this->view->formData['retypepassword'] = array('value' => $retypepassword, 'valid' => true, 'message' => '');
                        if ($retypepassword != $newpassword) {
                            $formValid = false;
                            $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-retypepassword'));
                            $this->view->formData['retypepassword'] = array('value' => $retypepassword, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-retypepassword'));
                        }
                        $currentpassword = $this->_getParam('currentpassword', null);
                        $this->view->formData['currentpassword'] = array('value' => $currentpassword, 'valid' => true, 'message' => '');
                        if (!$this->_currentUser->checkPassword($currentpassword)) {
                            $formValid = false;
                            $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-currentpassword'));
                            $this->view->formData['currentpassword'] = array('value' => $currentpassword, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-currentpassword'));
                        }
                    }
                    if ($formValid) {
                        if ($currentProfile->saveData($profileData)) {
                            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile/saved'));
                        } else {
                            $this->_flashMessenger->addMessage($this->_translate->translate('error-saving-data'));
                        }
                    }
                }
            }
        } else {
            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
    }

    public function registrationAction()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Core_Controller_Action_Helper_Captcha);
        $this->view->formData = array();
        if ($this->_request->isPost() && !$this->_currentUser) {
            $formValid = true;
            $login = $this->_getParam('login', null);
            $this->view->formData['login'] = array('value' => $login, 'valid' => true, 'message' => '');
            $loginValidator = new Core_Validate_Users_Login_NoRecordExists();
            if (!$loginValidator->isValid($login)) {
                $formValid = false;
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-login-already-exist'));
                $this->view->formData['login'] = array('value' => $login, 'valid' => false, 'message' => $this->_translate->translate('validation-login-already-exist'));
            }
            $email = $this->_getParam('email', null);
            $emailValidator = new Zend_Validate_EmailAddress();
            $this->view->formData['email'] = array('value' => $email, 'valid' => true, 'message' => '');
            if (!$emailValidator->isValid($email)) {
                $formValid = false;
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-email'));
                $this->view->formData['email'] = array('value' => $email, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-email'));
            }
            $emailValidator = new Core_Validate_Users_Email_NoRecordExists();
            if (!$emailValidator->isValid($email)) {
                $formValid = false;
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-email-already-exist'));
                $this->view->formData['email'] = array('value' => $email, 'valid' => false, 'message' => $this->_translate->translate('validation-email-already-exist'));
            }
            $phone = $this->_getParam('phone', null);
            $phoneValidator = new Zend_Validate_Digits();
            $this->view->formData['phone'] = array('value' => $phone, 'valid' => true, 'message' => '');
            if (!$phoneValidator->isValid($phone)) {
                $formValid = false;
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-phone'));
                $this->view->formData['phone'] = array('value' => $phone, 'valid' => false, 'message' => $this->_translate->translate('validation-incorrect-phone'));
            }
            $showpassword = $this->_getParam('showpassword', null);
            if (!$showpassword) {
                $password = $this->_getParam('password', null);
                $this->view->formData['password'] = array('value' => '', 'valid' => true, 'message' => '');
                if (strlen($password) < 8) {
                    $formValid = false;
                    $this->_flashMessenger->addMessage($this->_translate->translate('validation-password-too-short'));
                    $this->view->formData['password'] = array('value' => '', 'valid' => false, 'message' => $this->_translate->translate('validation-password-too-short'));
                }
                $retypepassword = $this->_getParam('retypepassword', null);
                $this->view->formData['retypepassword'] = array('value' => '', 'valid' => true, 'message' => '');
                if (!$showpassword && ($password != $retypepassword)) {
                    $formValid = false;
                    $this->_flashMessenger->addMessage($this->_translate->translate('validation-retypepassword-not-match'));
                    $this->view->formData['password'] = array('value' => '', 'valid' => false, 'message' => $this->_translate->translate('validation-retypepassword-not-match'));
                }
            } else {
                $password = $this->_getParam('password-clone', null);
                $this->view->formData['password'] = array('value' => '', 'valid' => true, 'message' => '');
                if (strlen($password) < 8) {
                    $formValid = false;
                    $this->_flashMessenger->addMessage($this->_translate->translate('validation-password-too-short'));
                    $this->view->formData['password'] = array('value' => '', 'valid' => false, 'message' => $this->_translate->translate('validation-password-too-short'));
                }
            }
            $this->view->formData['captcha'] = array('value' => '', 'valid' => true, 'message' => '');
            if (!$this->_helper->captcha->isValid($_POST['captcha'], $_POST)) {
                $formValid = false;
                $this->_helper->captcha->generate($this->view);
                $this->view->assign("captcha", $this->_helper->captcha->html);
                $this->view->assign("captcha_id", $this->_helper->captcha->id);
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-captcha-is-incorrect'));
                $this->view->formData['captcha'] = array('value' => '', 'valid' => false, 'message' => $this->_translate->translate('validation-captcha-is-incorrect'));
            }
//            Core_Vdie::_($formValid);
            if ($formValid) {
                $usersModel = new Core_Db_Users();
                $rolesModel = new Core_Db_Roles();
                $rolesLinkModel = new Core_Db_RolesLinks();
                if ($usersModel->getUserByEmail($email)) {
                    $this->_flashMessenger->addMessage($this->_translate->translate('message-user-already-exist'));
                } else {
                    $verifyCode = Core_Db_Users_Row::generatePassword(4);
                    $newUser = $usersModel->createRow();
                    $newUser->login = $login;
                    $newUser->email = $email;
                    $newUser->verify_code = $verifyCode;
                    $newUser->setPassword($password);
                    $newUser->save();
                    $userId = $newUser->id;
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
                    $this->view->verifyCode = $verifyCode;
                }
            }
        } else {
            $this->_helper->captcha->generate($this->view);
            $this->view->assign("captcha", $this->_helper->captcha->html);
            $this->view->assign("captcha_id", $this->_helper->captcha->id);
        }
    }

    public function confirmationAction()
    {
        if ($this->_request->isPost() && !$this->_currentUser) {
            $formValid = true;
            $login = $this->_getParam('login', null);
            $verifyCode = $this->_getParam('confirmation', null);
            if ($formValid) {
                $usersModel = new Core_Db_Users();
                if ($currentUser = $usersModel->getUserByLogin($login)) {
                    if ($currentUser->checkVerifyCode($verifyCode, true)) {
                        $profilesModel = new Core_Db_Profiles();
                        $currentProfile = $profilesModel->createRow();
                        $currentProfile->user_id = $currentUser->id;
                        $currentProfile->save();
//                        $newPassword = Core_Db_Users_Row::generatePassword(8);
//                        $currentUser->setPassword($newPassword);
//                        $currentUser->save();
                        $this->SendMail(array('template' => 'registration', 'login' => $currentUser->login));
                        $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('login'));
                    }
                } else {
                    $this->view->verifyCode = $verifyCode;
                    $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-exist'));
                }
            }
        }
        $this->_redirect('/confirmation');
    }

    public function resetAction()
    {
        if ($this->_request->isPost()) {
            $formValid = true;
            $email = $this->_getParam('email', null);
            $emailValidator = new Zend_Validate_EmailAddress();
            if (!$emailValidator->isValid($email)) {
                $formValid = false;
                $this->_flashMessenger->addMessage($this->_translate->translate('validation-incorrect-email'));
            }
            if ($formValid) {
                $usersModel = new Core_Db_Users();
                if ($currentUser = $usersModel->getUserByEmail($email)) {
                    if ($currentUser->checkVerifyCode($verifyCode, true)) {
                        $newPassword = Core_Db_Users_Row::generatePassword(8);
                        $currentUser->setPassword($newPassword);
                        $currentUser->save();
                        $this->SendMail(array('template' => 'reset', 'email' => $currentUser->email, 'password' => $newPassword));
                        $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('login'));
                    }
                } else {
                    $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-exist'));
                }
            }
        }
        $this->render('reset');
    }

    public function friendAction()
    {
        $this->_helper->_layout->disableLayout();
        $profilesModel = new Core_Db_Profiles();
        $profileData = $this->checkCurrentProfile();
        $friendProfileId = $this->_getParam('id', null);
        if ($friendProfileId) {
            $friendProfileData = $profilesModel->getRowInfo($friendProfileId);
            if ($friendProfileData) {
                $friendsLinksModel = new Core_Db_FriendsLinks;
                $friendsLinksModel->addFriendsLink($profileData->id, $friendProfileId);
            }
        }
        $headers = getallheaders();
        if (array_key_exists('X-Requested-With', $headers) && ($headers['X-Requested-With'] == 'XMLHttpRequest')) {
            $this->_helper->json->sendJSON(array('result' => 1));
        } else {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile'));
        }
    }

    public function unfriendAction()
    {
        $this->_helper->_layout->disableLayout();
        $profileData = $this->checkCurrentProfile();
        $friendProfileId = $this->_getParam('id', null);
        $friendsLinksModel = new Core_Db_FriendsLinks;
        $friendLink = $friendsLinksModel->getFriendsLink($profileData->id, $friendProfileId);
        if ($friendLink) {
            $friendLink->delete();
        }
        $headers = getallheaders();
        if (array_key_exists('X-Requested-With', $headers) && ($headers['X-Requested-With'] == 'XMLHttpRequest')) {
            $this->_helper->json->sendJSON(array('result' => 1));
        } else {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile'));
        }
    }

    public function documentAction()
    {
        if ($this->_currentUser) {
            if ($currentProfile = $this->_currentUser->getProfile()) {
                $this->view->profileData = $currentProfile;
                $this->view->userData = $this->_currentUser;
                $this->view->documentForm = $this->getDocumentForm();
                if ($this->_request->isPost()) {
                    if ($this->view->documentForm->isValid($_POST)) {
                        $imageObject = new Core_Image();
                        $result = $imageObject->uploadImages('/upload/');
                        if ($result) {
                            $documentData = $_POST;
                            $documentData['user_id'] = $this->_currentUser->id;
                            $documentData['profile_id'] = $currentProfile->id;
                            $documentData['image'] = array_key_exists('image', $result)?$result['image']['path']:'';
                            $documentModel = new Core_Db_Documents();
                            $documentRow = $documentModel->createRow();
                            $documentRow->saveData($documentData);
                            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile'));
                        } else {
                            $this->_flashMessenger->addMessage($this->_translate->translate('message-file-not-uploaded'));
                        }
                    }
                }
            } else {
                $this->_flashMessenger->addMessage($this->_translate->translate('message-profile-not-exist'));
            }
        } else {
            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
    }

    protected function getForm($configFile = '', $actionUrl = '')
    {
        $form = parent::getForm($configFile, $actionUrl);
//        $equalInputs = new Core_Validate_EqualInputs('password');
//        $equalInputs->setMessage($this->view->msgValidationPwdConfirmError);
//        $form->password_conf->addValidator($equalInputs);
        return $form;
    }

    protected function getProfileForm($populateData = null)
    {
        $form = $this->getForm('config/forms/front/profile.ini', 'profile/edit');
        if ($populateData) {
            $form->populate($populateData->toArray());
        }
        return $form;
    }
    protected function getDocumentForm()
    {
        $form = parent::getForm('config/forms/front/document.ini', 'profile/senddocument');
        return $form;
    }
}