<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'YourStudioApp',
    
    // preloading 'log' component
    'preload'=>array('log', 'maintenance'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
        'application.widgets.*',
        'application.extensions.*',
        'application.extensions.mailer.*',
        'application.extensions.image.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'gii',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'=>array('127.0.0.1','::1'),
        ),
        'api',
        'member',
        'admin',
    ),

    // application components
    'components'=>array(
        'request'=>array(
//            'enableCsrfValidation'=>true,
			'class' => 'application.components.YsaHttpRequest',
        ),
		'session' => array(
			'timeout' => 86400,
		),
        'user'=>array(
            'allowAutoLogin'=>true,
            'loginUrl' => array('/auth/login'),
            'class' => 'YsaWebUser',
        ),
        'authManager' => array(
            'class' => 'YsaPhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
                'urlFormat'=>'path',
                'showScriptName'=>false,
                'urlSuffix'=>'/',
//                        'useStrictParsing'=> true,
                'rules'=>array(
                    // general routes
                    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

                    // auth, registration rotes
                    '<action:(login|logout)>' => 'auth/<action>',
                    '/recovery/k/<k>' => 'recovery',
                    '/activate/k/<k>' => 'auth/activate',

                    // member routes
					'/member/event/<action:\w+>/<eventId>' => 'member/event/<action>',
					'/member/inbox/<action:\w+>/<messageId>' => 'member/inbox/<action>',
					'/member/album/<action:\w+>/<albumId>' => 'member/album/<action>',
					'/member/photo/<action:\w+>/<photoId>' => 'member/photo/<action>',
					'/member/person/<action:\w+>/<personId>' => 'member/person/<action>',
					'/member/link/<action:\w+>/<linkId>' => 'member/link/<action>',
					
					'/member/portfolioAlbum/<action:\w+>/<albumId>' => 'member/portfolioAlbum/<action>',
					'/member/portfolioPhoto/<action:\w+>/<photoId>' => 'member/portfolioPhoto/<action>',

                    // page routes
                    array(
                        'class' => 'application.components.YsaPageUrlRule',
                        'connectionID' => 'db',
                    ),

                    // admin routes
                    'admin/settings/group/<group>' => 'admin/settings',

                    // gii activation
                    'gii'=>'gii',
                    'gii/<controller:\w+>'=>'gii/<controller>',
                    'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
                ),
        ),

        // uncomment the following to use a MySQL database
        'db'=>array(
                'connectionString' => 'mysql:host=office.flosites.com;dbname=yoursturioapp',
//			'connectionString' => 'mysql:host=localhost;dbname=ysadev',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'iloveflosites',
                'charset' => 'utf8',
				'enableProfiling'=>true,
				'enableParamLogging'=>true,
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
//					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
					'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters'=>array('127.0.0.1','192.168.1.215'),
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels'=>'error, warning, trace, profile, info',
                ),
            ),
        ),

       'mailer' => array(
          'class'       => 'application.extensions.mailer.EMailer',
          'pathViews'   => 'application.views.email',
          'pathLayouts' => 'application.views.email.layouts'
       ),

        'settings'  => array(
            'class' => 'application.components.YsaSettings'
        ),

        'maintenance' => array(
            'class' => 'application.components.YsaMaintenance',
        ),

		'clientScript'=>array(
			'packages'=>array(
				'jquery'=>array(
					'baseUrl'=>'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/',
					'js'=>array('jquery.min.js'),
				),
			),
		),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'    =>'webmaster@yourstudioapp.com',
        'admin_per_page'=> 10,
        'salt'          => 'wel0veyourstud1oapp',
        'date_format'   => 'Y-m-d H:i:s',
        'currency'      => 'USD',

        'max_image_size' => 1024 * 1024 * 5,

        'application'   => array(
            'logo'  => array(
                'width'  => 400,
                'height' => 300,
                'ext'    => 'png',
            ),
            'splash_bg_image' => array(
                'width'  => 1024,
                'height' => 768,
                'ext'    => 'png',
            ),
            'studio_bg_image' => array(
                'width'  => 1024,
                'height' => 768,
                'ext'    => 'png',
            ),
            'photographer_info' => array(
                'width'  => 1024,
                'height' => 768,
                'ext'    => 'png',
            ),
            'generic_bg_image' => array(
//                'width'  => 1024,
//                'height' => 768,
                'ext'    => 'png',
            ),
        ),
		'member_area' => array(
			'album' => array(
				'preview' => array(
					'width'  => 300,
					'height' => 200,
				),
			),
			'photo' => array(
				'preview' => array(
					'width'  => 300,
					'height' => 200,
				),
				'full' => array(
					'width'  => 1024,
					'height' => 768,
				),
			),
		),
		'studio' => array(
			'person' => array(
				'photo' => array(
					'width'  => 100,
					'height' => 100,
				),
			),
		),

		'studio_options'	=> array(
			'logo'		=> array(
				'logo'	=> array(
					'label'	=> 'Logo',
					'img'	=> TRUE,
				),
				'splash_bg_image'	=> array(
					'label'	=> 'Splash Background Image',
					'img'	=> TRUE,
				),
				'splash_bg_color'	=> array(
					'label'	=> 'Splash Background Color'
				),
				'splash_bg'	=> array(
					'values'	=> array(
						'image'	=> 'Background Image',
						'color'	=> 'Background Color'
					),
					'label'		=> 'Splash Background Type'
				)
			),
			'colors'	=> array(
				'studio_bg'	=> array(
					'values'	=> array(
						'image'	=> 'Background Image',
						'color'	=> 'Background Color'
					),
					'label'		=> 'Studio Background Type'
				),
				'studio_bg_image' => array(
					'label'		=> 'Studio Background Image',
					'img'	=> TRUE,
				),
				'studio_bg_color' => array(
					'label'		=> 'Studio Background Color'
				),
				'generic_bg' => array(
					'label'		=> 'Generic Background Type'
				),
				'generic_bg_color' => array(
					'label'		=> 'Generic Background Color'
				),
				'generic_bg_image' => array(
					'label'		=> 'Generic Background Image',
					'img'	=> TRUE,
				),
			),
			'fonts'		=> array(
				'main_font' => array(
					'values'	=> array(
						'arial'     => 'Arial',
						'helvetica' => 'Helvetica',
						'georgia'   => 'Georgia',
					),
					'label'		=> 'Main font'
				),
				'second_font' => array(
					'values'	=> array(
						'arial'     => 'Arial',
						'helvetica' => 'Helvetica',
						'georgia'   => 'Georgia',
					),
					'label'		=> 'Second font'
				),
				'main_font_color' => array(
					'label'		=> 'Color of the main font'
				),
				'second_font_color' => array(
					'label'		=> 'Color of the second font'
				),
			),
			'copyrights'	=> array(
				'copyright'	=> array(
					'label'		=> 'Copyright text'
				)
			),
			'copyrights'	=> array(
				'copyright'	=> array(
					'label'		=> 'Copyright text'
				)
			),
		)
    ),
);