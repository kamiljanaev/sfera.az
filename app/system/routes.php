<?php
$router = new Core_Controller_Router_Rewrite();
$adminRoute = new Zend_Controller_Router_Route(
		'admin/module/:admin_module/:admin_controller/:admin_action/*',
		array(
				'module'=>'admin',
				'controller'=>'module',
				'action'=>'index',
				'admin_module'=>'admin',
				'admin_controller'=>'index',
				'admin_action'=>'index'
		)
);
$defaultRoute = new Zend_Controller_Router_Route(
		':module/:controller/:action/*',
		array(
				'module' => 'default',
				'controller' => 'index',
				'action' => 'index'
		)
);
$loginRoute = new Zend_Controller_Router_Route(
		'login',
		array(
				'module' => 'users',
				'controller' => 'auth',
				'action' => 'login'
		)
);
$loginFbRoute = new Zend_Controller_Router_Route(
		'login/facebook',
		array(
				'module' => 'login',
				'controller' => 'facebook',
				'action' => 'index'
		)
);
$logoutRoute = new Zend_Controller_Router_Route(
		'logout',
		array(
				'module' => 'users',
				'controller' => 'auth',
				'action' => 'logout'
		)
);
$authRoute = new Zend_Controller_Router_Route(
		'auth',
		array(
				'module' => 'users',
				'controller' => 'auth',
				'action' => 'index'
		)
);
$deniedRoute = new Zend_Controller_Router_Route(
		'denied',
		array(
				'module' => 'users',
				'controller' => 'auth',
				'action' => 'denied'
		)
);
$registrationRoute = new Zend_Controller_Router_Route(
		'registration',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'registration'
		)
);
$resetRoute = new Zend_Controller_Router_Route(
		'reset',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'reset'
		)
);
$confirmationRoute = new Zend_Controller_Router_Route(
		'confirmation',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'confirmation'
		)
);
$profileEditRoute = new Zend_Controller_Router_Route(
		'profile/edit',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'edit'
		)
);

$profileSavedRoute = new Zend_Controller_Router_Route(
		'profile/saved',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'saved'
		)
);

$profileDocRoute = new Zend_Controller_Router_Route(
		'profile/senddocument',
		array(
				'module' => 'profile',
				'controller' => 'index',
				'action' => 'document'
		)
);
$profileAliasRoute = new Core_Controller_Router_Route_Regex_Profile(
		'([\d\w\-,_]+)',
		array(
				'module' 	 => 'profile',
				'controller' => 'index',
				'action'     => 'index'
		),
		array(
				1 => 'alias'
		)
);

$profileIdRoute = new Zend_Controller_Router_Route(
		'profile/id/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'index',
				'action'     => 'index',
                'id' => 0
		)
);

$profileMyRoute = new Zend_Controller_Router_Route(
		'profile/my',
		array(
				'module' 	 => 'profile',
				'controller' => 'index',
				'action'     => 'index'
		)
);

$addFriendRoute = new Zend_Controller_Router_Route(
		'friend/add/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'index',
				'action'     => 'friend',
                'id' => 0
		)
);

$delFriendRoute = new Zend_Controller_Router_Route(
		'friend/remove/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'index',
				'action'     => 'unfriend',
                'id' => 0
		)
);

$profileFriendsRoute = new Zend_Controller_Router_Route(
		'profile/friends/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'relations',
				'action'     => 'friends',
                'id' => null
		)
);

$profileSubscribersRoute = new Zend_Controller_Router_Route(
		'profile/subscribers/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'relations',
				'action'     => 'subscribers',
                'id' => null
		)
);



$newsListRoute = new Zend_Controller_Router_Route(
		'news/list/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'list',
                'page' => 1,
		)
);

$newsAddedRoute = new Zend_Controller_Router_Route(
		'news/added',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'added'
		)
);

$newsCategoryListRoute = new Zend_Controller_Router_Route(
		'news/category/:category/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'category',
                'category' => null,
                'page' => 1,
		)
);

$newsTagsListRoute = new Zend_Controller_Router_Route(
		'news/tags/:tag/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'tags',
                'category' => null,
                'page' => 1,
		)
);

$newsMyRoute = new Zend_Controller_Router_Route(
		'news/my/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'list',
                'page' => 1,
                'my' => 1
		)
);

$newsAddRoute = new Zend_Controller_Router_Route(
		'news/add',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'add'
		)
);
$newsShowRoute = new Zend_Controller_Router_Route(
		'news/show/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'show',
				'id'		 => '0'
		)
);

$adsListRoute = new Zend_Controller_Router_Route(
		'ads/list/:page',
		array(
				'module' => 'content',
				'controller' => 'ads',
				'action' => 'list',
                'page' => 1,
		)
);

$adsMyRoute = new Zend_Controller_Router_Route(
		'ads/my/:page',
		array(
				'module' => 'content',
				'controller' => 'ads',
				'action' => 'list',
                'page' => 1,
                'my' => 1
		)
);

$adsAddRoute = new Zend_Controller_Router_Route(
		'ads/add',
		array(
				'module' => 'content',
				'controller' => 'ads',
				'action' => 'add'
		)
);
$adsShowRoute = new Zend_Controller_Router_Route(
		'ads/show/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'ads',
				'action'     => 'show',
				'id'		 => '0'
		)
);

$bookmarksListRoute = new Zend_Controller_Router_Route(
		'bookmarks/list',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'index'
		)
);

$bookmarksAddRoute = new Zend_Controller_Router_Route(
		'bookmarks/add/:category_id',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'edit',
                'id'         => null,
                'category_id'=> null
		)
);

$bookmarksEditRoute = new Zend_Controller_Router_Route(
		'bookmarks/edit/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'edit',
                'id'         => null
		)
);

$bookmarksRemoveRoute = new Zend_Controller_Router_Route(
		'bookmarks/remove/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'remove',
                'id'         => null
		)
);

$bookmarksCategoryAddRoute = new Zend_Controller_Router_Route(
		'bookmarks/category/add',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'category',
                'id'         => null
		)
);

$bookmarksCategoryEditRoute = new Zend_Controller_Router_Route(
		'bookmarks/category/edit/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'category',
                'id'         => null
		)
);

$bookmarksRemoveCatRoute = new Zend_Controller_Router_Route(
		'bookmarks/category/remove/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'bookmarks',
				'action'     => 'removecat',
                'id'         => null
		)
);


$favListRoute = new Zend_Controller_Router_Route(
		'favorites/list',
		array(
				'module' 	 => 'profile',
				'controller' => 'favorites',
				'action'     => 'index'
		)
);

$favAddRoute = new Zend_Controller_Router_Route(
		'favorites/add',
		array(
				'module' 	 => 'profile',
				'controller' => 'favorites',
				'action'     => 'add'
		)
);

$subscriptionsListRoute = new Zend_Controller_Router_Route(
		'subscriptions/list/:profile_id',
		array(
				'module' 	 => 'profile',
				'controller' => 'subscriptions',
				'action'     => 'list',
                'profile_id' => null
		)
);

$subscriptionsCategoryRoute = new Zend_Controller_Router_Route(
		'subscriptions/category/:id/:page',
		array(
				'module' 	 => 'profile',
				'controller' => 'subscriptions',
				'action'     => 'category',
                'page' => 1,
                'id' => null
		)
);

$subscriptionsRemoveRoute = new Zend_Controller_Router_Route(
		'subscriptions/remove/:id',
		array(
				'module' 	 => 'profile',
				'controller' => 'subscriptions',
				'action'     => 'remove',
                'id'         => null
		)
);

$newsRatingRoute = new Zend_Controller_Router_Route(
		'news/rating/:value/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'rating',
                'value'      => 'null',
                'id' => null
		)
);

$getNewsRatingRoute = new Zend_Controller_Router_Route(
		'news/rating/get/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'getrating',
                'id' => null
		)
);

$router->addRoutes(array(
		'default' => $defaultRoute,
        'admin'	=> $adminRoute,
        'login'	=> $loginRoute,
        'login/fb' => $loginFbRoute,
        'logout'	=> $logoutRoute,
        'auth'	=> $authRoute,
        'denied' => $deniedRoute,
        'registration' => $registrationRoute,
        'reset' => $resetRoute,
        'confirmation' => $confirmationRoute,
        'profile/edit' => $profileEditRoute,
        'profile/saved' => $profileSavedRoute,
        'profile/alias' => $profileAliasRoute,
		'profile/senddocument' => $profileDocRoute,
        'profile/id' => $profileIdRoute,
        'profile/my' => $profileMyRoute,
        'news/list' => $newsListRoute,
        'news/category' => $newsCategoryListRoute,
        'news/tags' => $newsTagsListRoute,
        'news/my' => $newsMyRoute,
        'news/add' => $newsAddRoute,
        'news/added' => $newsAddedRoute,
        'news/show' => $newsShowRoute,
        'ads/list' => $adsListRoute,
        'ads/my' => $adsMyRoute,
        'ads/add' => $adsAddRoute,
        'ads/show' => $adsShowRoute,
        'friend/add' => $addFriendRoute,
        'friend/del' => $delFriendRoute,
        'bookmarks/list' => $bookmarksListRoute,
        'bookmarks/add' => $bookmarksAddRoute,
        'bookmarks/edit' =>  $bookmarksEditRoute,
        'bookmarks/remove/' => $bookmarksRemoveRoute,
        'bookmarks/category/add' => $bookmarksCategoryAddRoute,
        'bookmarks/category/edit' => $bookmarksCategoryEditRoute,
        'bookmarks/category/remove/' => $bookmarksRemoveCatRoute,
        'fav/list' => $favListRoute,
        'fav/add' => $favAddRoute,
        'profile/friends' => $profileFriendsRoute,
        'profile/subscribers' => $profileSubscribersRoute,
        'subscriptions/list' => $subscriptionsListRoute,
        'subscriptions/category' => $subscriptionsCategoryRoute,
        'subscriptions/remove' => $subscriptionsRemoveRoute,
        'news/rating' => $newsRatingRoute,
        'news/rating/get' => $getNewsRatingRoute
));
?>
