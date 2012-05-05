<?php
$router = new Core_Controller_Router_Rewrite();

$newsListRoute = new Zend_Controller_Router_Route(
		'list/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'list',
                'page' => 1,
		)
);

$newsAddedRoute = new Zend_Controller_Router_Route(
		'added',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'added'
		)
);

$newsCategoryListRoute = new Zend_Controller_Router_Route(
		'category/:category/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'category',
                'category' => null,
                'page' => 1,
		)
);

$newsTagsListRoute = new Zend_Controller_Router_Route(
		'tags/:tag/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'tags',
                'category' => null,
                'page' => 1,
		)
);

$newsMyRoute = new Zend_Controller_Router_Route(
		'my/:page',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'list',
                'page' => 1,
                'my' => 1
		)
);

$newsAddRoute = new Zend_Controller_Router_Route(
		'add',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'add'
		)
);
$newsShowRoute = new Zend_Controller_Router_Route(
		'show/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'show',
				'id'		 => '0'
		)
);

$newsRatingRoute = new Zend_Controller_Router_Route(
		'rating/:value/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'rating',
                'value'      => 'null',
                'id' => null
		)
);

$getNewsRatingRoute = new Zend_Controller_Router_Route(
		'rating/get/:id',
		array(
				'module' 	 => 'content',
				'controller' => 'news',
				'action'     => 'getrating',
                'id' => null
		)
);

$defaultRoute = new Zend_Controller_Router_Route(
		':module/:controller/:action/*',
		array(
				'module' => 'content',
				'controller' => 'news',
				'action' => 'index'
		)
);

$router->addRoutes(array(
        'default' => $defaultRoute,
        'news/list' => $newsListRoute,
        'news/category' => $newsCategoryListRoute,
        'news/tags' => $newsTagsListRoute,
        'news/my' => $newsMyRoute,
        'news/add' => $newsAddRoute,
        'news/added' => $newsAddedRoute,
        'news/show' => $newsShowRoute,
        'news/rating' => $newsRatingRoute,
        'news/rating/get' => $getNewsRatingRoute
));
?>
