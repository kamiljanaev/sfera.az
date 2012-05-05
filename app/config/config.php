<?php

$root=dirname(__FILE__);
$root .= DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
$root = realpath($root).DIRECTORY_SEPARATOR;
$rootPublic = $root;
$baseUrl = '/';
$systemconfig = array (
        'site' => array(
                'domain' => 'micro.logual.net',
                'base_url' => 'http://localhost/',
                'title' => 'Сфера',
                'title_separator' => ' - ',
        ),
        'languages' => array(
            'default' => 'en',
            'locales' => array(
                'ru' => 'ru_RU',
                'en' => 'en_US'
            )
        ),
        'error' => array(
                'emailNotification' => false,
                'emailTo' => 'ilogual@gmail.com',
                'loggin' => true,
                'logFile' => $root.'app/logs/crash.log',
        ),
        'facebook' => array(
                'appID' => '399740023378257',
                'appSecret' => 'c12174bdad33777ece43de504a1cd180'
        ),
        'twitter' => array(
                'key' => 'dVDzqtuTasB5eHAmNoAVA',
                'secret' => 'zxT1KqTMo3rorJgmTQXlVV0FUePoWYlux4lDLIA9M'
        ),
        'loging' => array(
                'on' => false,
                'logFile' => $root.'logs/messages.log',
        ),
        'db'    => array (
                'adapter'   => 'PDO_MYSQL',
                'params'    => array(
                        'host'          => 'localhost',
//                        'username'      => 'artgenco_sphere',
//                        'password'      => 'artgenco_sphere',
//                        'dbname'        => 'artgenco_sphere',
                        'username'      => 'dbu_ilogual_2',
                        'password'      => 'sphera123',
                        'dbname'        => 'db_ilogual_2',
//                        'username'      => 'root',
//                        'password'      => 'passw0rdus',
//                        'dbname'        => 'sfera',

                        'driver_options'=> array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'),
                        'profiler'      => false,
                ),
        ),
        // setting URL address
        'url'   => array (
                'base'         => $baseUrl,
                'public'       => $baseUrl . 'public',
                'img'          => $baseUrl . 'img',
                'css'          => $baseUrl . 'css',
                'js'           => $baseUrl . 'js',
        ),
        'mail' => array(
                'default' => array(
                        'to_name' => 'Сфера',
                        'to_email' => 'ilogual@gmail.com',
                        'from_name' => 'Сфера',
                        'from_email' => 'ilogual@gmail.com',
                ),
		'useTransport' => true,
                'transport' => array(
                        'smtp' => array(
							'server' => 'smtp.gmail.com',
							'config' => array(
								'auth' => 'login',
								'username' => 'ilogual@gmail.com',
								'password' => '',
								'ssl' => 'tls',
								'port' => 587
							)
                        )
                ),
        ),
        'path'  => array (
                'root'         => $root,
                'rootPublic'   => $rootPublic,
                'library'      => $root . 'app/library',
                'app'          => $root . 'app/',
                'logs'         => $root . 'app/logs/',
                'engine'       => $root . 'app/library/',
                'cache'        => $root . 'app/cache/',
                'modules'      => $root . 'app/modules/',
                'layouts'      => $root . 'app/layouts/',
                'system'       => $root . 'app/system/',
                'settings'     => $root . 'app/settings/',
                'views'        => $root . 'app/views/',
                'templates'    => $root . 'app/config/templates/',
                'templates'    => $root . 'app/config/templates/',
                'base'         => $rootPublic,
                'public'       => $rootPublic . 'public',
                'upload'       => $rootPublic . 'upload',
                'img'          => $rootPublic . 'img',
                'css'          => $rootPublic . 'css',
                'js'           => $rootPublic . 'js',
        ),
        'debug' => array (
                'on' => true,
        ),
        'common' => array (
                'charset' => 'utf-8',
        ),
        'images' => array(
                'uploadBasePath' => $rootPublic . 'upload' . DIRECTORY_SEPARATOR,
                'thumbnailPath' => $rootPublic . 'upload-thumbnail' . DIRECTORY_SEPARATOR,
                                'fileExtensions' => '*.jpg;*.gif;*.png',
                                'subPath' => '',
//                                'subPath' => 'image' . DIRECTORY_SEPARATOR,
                                'maxWidth' => 800,
                                'maxHeight' => 600,
                                'quality' => 90,
                                'thumbnail' => array(
                                        'quality' => 90,
                                        'types' => array(
                                            'paragraphsFloat' =>array(
                                                    'subPath' => 'icons'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 240,
                                                    'maxHeight' => 160,
                                                    'quality' => 91
                                            ),
                                            'adsHomePayed' =>array(
                                                    'subPath' => 'adshomepayed'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 60,
                                                    'maxHeight' => 60,
                                                    'quality' => 91
                                            ),
                                            'adsHomeRealty' =>array(
                                                    'subPath' => 'adshomerealty'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 90,
                                                    'maxHeight' => 60,
                                                    'quality' => 91
                                            ),
                                            'newsHomeLast' =>array(
                                                    'subPath' => 'newshomelast'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 269,
                                                    'maxHeight' => 170,
                                                    'quality' => 91
                                            ),
                                            'newsHomeList' =>array(
                                                    'subPath' => 'newshomelist'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 60,
                                                    'maxHeight' => 60,
                                                    'quality' => 91
                                            ),
                                            'hotNewsFirst' =>array(
                                                    'subPath' => 'hotnewsfirst'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 378,
                                                    'maxHeight' => 239,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'hotNewsSecond' =>array(
                                                    'subPath' => 'hotnewsfirst'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 196,
                                                    'maxHeight' => 239,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'hotNewsThird' =>array(
                                                    'subPath' => 'hotnewsfirst'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 157,
                                                    'maxHeight' => 158,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'hotNewsForth' =>array(
                                                    'subPath' => 'hotnewsfirst'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 219,
                                                    'maxHeight' => 158,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'hotNewsFifth' =>array(
                                                    'subPath' => 'hotnewsfirst'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 196,
                                                    'maxHeight' => 158,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'icons' =>array(
                                                    'subPath' => 'icons'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 99,
                                                    'maxHeight' => 99,
                                                    'quality' => 91
                                            ),
                                            'avatar_100' =>array(
                                                    'subPath' => 'avatar'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 100,
                                                    'maxHeight' => 100,
                                                    'quality' => 91,
                                                    'crop' => true
                                            ),
                                            'avatar_70' =>array(
                                                    'subPath' => 'avatar70'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 70,
                                                    'maxHeight' => 70,
                                                    'quality' => 60,
                                                    'crop' => true
                                            ),
                                            'avatar_40' =>array(
                                                    'subPath' => 'avatar40'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 40,
                                                    'maxHeight' => 40,
                                                    'quality' => 40,
                                                    'crop' => true
                                            ),
                                            'news_small' =>array(
                                                    'subPath' => 'news_s'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 60,
                                                    'maxHeight' => 60,
                                                    'quality' => 60,
                                                    'crop' => true
                                            ),
                                            'news_list' =>array(
                                                    'subPath' => 'news_l'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 140,
                                                    'maxHeight' => 93,
                                                    'quality' => 80,
                                                    'crop' => false
                                            ),
                                            'news_medium' =>array(
                                                    'subPath' => 'news_m'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 270,
                                                    'maxHeight' => 170,
                                                    'quality' => 80,
                                                    'crop' => false
                                            ),
                                            'news_big' =>array(
                                                    'subPath' => 'news_b'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 380,
                                                    'maxHeight' => 240,
                                                    'quality' => 100,
                                                    'crop' => false
                                            ),
                                            'awards' =>array(
                                                    'subPath' => 'awards'.DIRECTORY_SEPARATOR,
                                                    'maxWidth' => 100,
                                                    'maxHeight' => 100,
                                                    'quality' => 91
                                            ),
                                                'lists' =>array(
                                                        'subPath' => 'lists'.DIRECTORY_SEPARATOR,
                                                        'maxWidth' => 80,
                                                        'maxHeight' => 60,
                                                        'quality' => 60
                                                )
                                        ),
                                ),
        ),
        'consts' => array(
                'default_list_count' => 5,
                'default_list_element' => '---',
		),
        'elFinder' => array(
			'mimeDetect'      => 'internal',
			'root'            => $rootPublic.'upload'.DIRECTORY_SEPARATOR,
			'uploadDir'		  => 'upload'.DIRECTORY_SEPARATOR,
			'URL'             => $baseUrl.'upload/',
			'rootAlias'       => 'Файлы',
			'tmbDir'		  => '..'.DIRECTORY_SEPARATOR.'upload-thumbnail'.DIRECTORY_SEPARATOR.'thumbnail'.DIRECTORY_SEPARATOR,
		)
);
date_default_timezone_set("Asia/Baku");
