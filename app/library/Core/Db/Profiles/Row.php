<?php
class Core_Db_Profiles_Row extends Core_Db_Table_Row
{
    const
        DEFAULT_AVATAR = '/upload/noavatar.png';
    
    protected
        $_NewsSubscribesCategoriesLinks = null,
        $_NewsSubscribesCategoriesList = array(),
        $_AwardsLinks = null,
        $_AwardsList = array(),
        $_currentUser = null;
    
	public function link($action='view')
	{
		return Core_View_Helper_Link :: profile($this->id, $action);
	}

    public function getFullName()
    {
        $nameArray = array();
        if (strlen($this->lastname)) {
            $nameArray[] = $this->lastname;
        }
        if (strlen($this->firstname)) {
            $nameArray[] = $this->firstname;
        }
//        if (strlen($this->middlename)) {
//            $nameArray[] = $this->middlename;
//          }
        return implode(' ', $nameArray);
    }

    public function checkFriend($friendId = null)
    {
        $friendsLinksModel = new Core_Db_FriendsLinks;
        if ($friendsLinksModel->getFriendsLink($this->id, $friendId)) {
            return true;
        } else {
            return false;
        }
    }

    public function getFriends($limit = null)
    {
        $friendsLinksModel = new Core_Db_FriendsLinks;
        return $friendsLinksModel->getByProfileId($this->id, $limit)->fetchArray();
    }

    public function getSubscribers($limit = null)
    {
        $friendsLinksModel = new Core_Db_FriendsLinks;
        return $friendsLinksModel->getByFriendId($this->id, $limit)->fetchArray();
    }

    public function getGender()
    {
        if ($this->gender) {
            return "gender-male-value";
        }
        return "gender-female-value";
    }

    public function getAvatar($avatarSize = Core_Image::AVATAR_100)
    {
        if ($this->avatar) {
            return Core_Image::getImagePath($this->avatar, $avatarSize);
        } else {
            return Core_Image::getImagePath(self::DEFAULT_AVATAR, $avatarSize);
        }
    }

    public function getFacebook()
    {
        if (strlen($this->facebook)) {
            return '<a href="http://www.facebook.com/'.$this->facebook.'">'.$this->facebook.'</a>';
        }
        return '';
    }

    public function getTwitter()
    {
        if (strlen($this->twitter)) {
            return '<a href="http://twitter.com/'.$this->twitter.'">'.$this->twitter.'</a>';
        }
        return '';
    }

    public function getGoogle()
    {
        if (strlen($this->googleplus)) {
            return '<a href="http://plus.google.com.com/'.$this->googleplus.'">'.$this->googleplus.'</a>';
        }
        return '';
    }

    public function getWww()
    {
        if (strlen($this->www)) {
            return '<a href="http://'.$this->www.'">'.$this->www.'</a>';
        }
        return '';
    }

    public function getRatingValue()
    {
        return '6.99';
    }

    public function getRatingVoters()
    {
        return '127';
    }

    public function saveData($data = null)
    {
        try {
            $this->getTable()->getAdapter()->beginTransaction();
            parent::saveData($data);
            if (array_key_exists('newpassword', $data) && strlen($data['newpassword'])) {
                if (array_key_exists('retypepassword', $data) && ($data['newpassword'] == $data['retypepassword'])) {
                    if ($currentUser = $this->getUser()) {
                        if ($currentUser->checkPassword($data['currentpassword'])) {
                            if (!$currentUser->setPassword($data['newpassword'])) {
                                throw new Zend_Exception('password too short');
                            }
                            $currentUser->save();
                        } else {
                            throw new Zend_Exception('incorrect current password');
                        }
                    }
                } else {
                    throw new Zend_Exception('incorrect retype password');
                }
            }
            if (array_key_exists('email', $data) && strlen($data['email'])) {
                $usersModel = new Core_Db_Users();
                if ($usersModel->getUserByEmail($data['email'], $this->user_id)) {
                    throw new Zend_Exception('email already exist');
                }
                if ($currentUser = $this->getUser()) {
                    $currentUser->email = $data['email'];
                    $currentUser->save();
                }
            }
            if (array_key_exists('current_status', $data) && strlen($data['current_status'])) {
                $statusesModel = new Core_Db_Statuses();
                $statusRow = $statusesModel->createRow();
                $statusRow->value = $data['current_status'];
                $statusRow->user_id = $this->user_id;
                $statusRow->save();
            }
            $this->getTable()->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getTable()->getAdapter()->rollBack();
            return false;
        }
        return $this;
    }

    public function isOnline()
    {
        $user = $this->getUser();
        return $user->is_online;
    }

    public function getUser()
    {
        if (!($this->_currentUser instanceof Core_Db_Users_Row)) {
            $this->_currentUser = $this->findParentRow('Core_Db_Users', 'Ref_To_User');
        }
        return $this->_currentUser;
    }

    public function appendAmount($amount = 0)
    {
        try {
            $this->amount += $amount;
            $this->save();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

	public function getAwardsLinks()
	{
		if ($this->_AwardsLinks == null) {
			$awardsLinksModel = new Core_Db_AwardsLinks();
			$this->_AwardsLinks = $this->findDependentRowset($awardsLinksModel);
		}
		return $this->_AwardsLinks;
	}

	public function getAwards()
	{
		if ($this->_AwardsList == null) {
			$awardsLinks = $this->getAwardsLinks();
			foreach ($awardsLinks as $linkItem) {
				$this->_AwardsList[] = $linkItem->getAward();
			}
		}
		return $this->_AwardsList;
	}

	public function getAwardsTitles()
	{
        $awardsList = $this->getAwards();
        $awardsTitles = array();
        if ($awardsList) {
            foreach ($awardsList as $roleItem) {
                $awardsTitles[] = $roleItem->name;
            }
        }
        return $awardsTitles;
	}

	public function getAwardsIds()
	{
        $awardsList = $this->getAwards();
        $awardsTitles = array();
        if ($awardsList) {
            foreach ($awardsList as $roleItem) {
                $awardsTitles[] = $roleItem->id;
            }
        }
        return $awardsTitles;
	}

    public function getNewsSubscribesCategoriesLinks()
    {
        if ($this->_NewsSubscribesCategoriesLinks == null) {
            $subscribeLinksModel = new Core_Db_SubscribeLinks();
            $this->_NewsSubscribesCategoriesLinks = $this->findDependentRowset($subscribeLinksModel);
        }
        return $this->_NewsSubscribesCategoriesLinks;
    }

    public function getNewsSubscribesCategories()
    {
        if ($this->_NewsSubscribesCategoriesList == null) {
            $subscribeLinks = $this->getNewsSubscribesCategoriesLinks();
            foreach ($subscribeLinks as $linkItem) {
                $this->_NewsSubscribesCategoriesList[] = $linkItem->getCategory();
            }
        }
        return $this->_NewsSubscribesCategoriesList;
    }

    public function getNewsSubscribesCategoriesTitles()
    {
        $getNewsSubscribesCategoriesList = $this->getNewsSubscribesCategories();
        $getNewsSubscribesCategoriesTitles = array();
        if ($getNewsSubscribesCategoriesList) {
            foreach ($getNewsSubscribesCategoriesList as $roleItem) {
                $getNewsSubscribesCategoriesTitles[] = $roleItem->title;
            }
        }
        return $getNewsSubscribesCategoriesTitles;
    }

    public function getNewsSubscribesCategoriesIds()
    {
        $getNewsSubscribesCategoriesList = $this->getNewsSubscribesCategories();
        $getNewsSubscribesCategoriesTitles = array();
        if ($getNewsSubscribesCategoriesList) {
            foreach ($getNewsSubscribesCategoriesList as $roleItem) {
                $getNewsSubscribesCategoriesTitles[] = $roleItem->id;
            }
        }
        return $getNewsSubscribesCategoriesTitles;
    }

    public function __get($name)
    {
        if ($name == 'login') {
            $user = $this->getUser();
            if ($user) {
                return $user->login;
            }
            return '';
        } else {
            return parent::__get($name);
        }
    }

}
