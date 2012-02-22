<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'env'.DIRECTORY_SEPARATOR.APPLICATION_ENV.'.php';

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'YourStudioApp',

    'language'=>'en',
	'sourceLanguage'=>'en',
	'charset'=>'utf-8',

	// preloading 'log' component
	'preload'=>array('log', 'maintenance'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.behaviors.*',
		'zii.behaviors.*',
		'application.components.*',
		'application.helpers.*',
		'application.widgets.*',
		'application.extensions.*',
		'application.extensions.mailer.*',
		'application.extensions.image.*',
		'application.extensions.phpsmug.*',
		'application.extensions.phpZenfolio.*',
		'application.extensions.twitteroauth.*',
		'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.services.*',
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
		'messages' => array(
			'class' => 'YsaMessageSource',
			'forceTranslation' => true,
			'sourceMessageTable' => 'translation_source',
			'translatedMessageTable' => 'translation',
			'language' => 'en',
		),
		'request'=>array(
			'class' => 'application.components.YsaHttpRequest',
		),
		'session' => array(
			'timeout' => 86400,
		),
		'user'=>array(
			'allowAutoLogin'=>true,
			'loginUrl' => array('/login'),
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
			//'useStrictParsing'=> true,
			'rules'=>array(
				// general routes
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

				// pages routes
				'contact' => 'page/contact',
				
				// auth, registration rotes
				'<action:(login|logout|loginoauth)>' => 'auth/<action>',
				'/recovery/k/<k>' => 'recovery',
				'/activate/k/<k>' => 'auth/activate',
				'/photo/v/<k>' => 'photo/view',
				'/register/oauth/complete'=>'register/oauthcomplete',

				// member routes
				'/member/event/<action:\w+>/<eventId>' => 'member/event/<action>',
				'/member/client/<action:\w+>/<clientId>' => 'member/client/<action>',
				'/member/notification/<action:\w+>/<notificationId>' => 'member/notification/<action>',
				'/member/inbox/<action:\w+>/<messageId>' => 'member/inbox/<action>',
				'/member/album/<action:\w+>/<albumId>' => 'member/album/<action>',
				'/member/photo/<action:\w+>/<photoId>' => 'member/photo/<action>',
				'/member/link/<action:\w+>/<linkId>' => 'member/link/<action>',
				'/member/person/<action:\w+>/<personId>' => 'member/person/<action>',
				'/member/settings/facebook/connect/<service_param>/<service>'=>'member/settings/facebookconnect',
				'/member/settings/facebook/unlink/'=>'member/settings/facebookunlink',
				'/member/help/<slug:[\w\d\-]+>' => 'member/help/view',
				
				//image route
				'/image/<action:\w+>/<imageId>' => 'image/<action>',

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
		'db' => $envDb,
		'cache' => array (
			'class' => 'system.caching.CFileCache'
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class' => 'YsaLogRouter',
			'routes' => $envLogRoutes,
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

		'CURL' =>array(
			'class' => 'application.extensions.Curl',
		),

		'clientScript'=>array(
			'packages'=>array(
				'jquery'=>array(
					'baseUrl'=>'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/',
					'js'=>array('jquery.min.js'),
				),
			),
		),
		
		'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
		
        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true,
            'services' => array(
                'facebook' => array(
                    'class' => 'FacebookOAuthService',
                ),
            ),
        ),
	),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'    =>'admin@yourstudioapp.com',
		'admin_per_page'=> 40,
		'member_per_page'=> 20,
		'salt'          => 'wel0veyourstud1oapp',
		'date_format'   => 'Y-m-d H:i:s',
		'date_format_short' => 'D, j M',
		'currency'      => 'USD',
		
		'languages'	=> array(
			'en' => "English",
		),

		'max_image_size' => 1024 * 1024 * 5, // 5MB
		
		'application'   => array(
			'logo'  => array(
				'width'  => 400,
				'height' => 400,
				'ext'    => 'png',
			),
			'icon'	=> array(
				'width'  => 72,
				'height' => 72,
				'ext'    => 'png',
			),
			'itunes_logo'	=> array(
				'width'  => 512,
				'height' => 512,
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
				'width'  => 1024,
				'height' => 768,
				'ext'    => 'png',
			),
		),
		'member_area' => array(
			'album' => array(
				'preview' => array(
					'width'  => 230,
					'height' => 230,
				),
			),
			'photo' => array(
				'preview' => array(
					'width'  => 230,
					'height' => 230,
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
					'width'  => 220,
					'height' => 220,
				),
			),
			'video' => array(
				'standart' => array(
					'width'	 => 640,
					'height' => 360,
				),
				'preview' => array(
					'width'	 => 640,
					'height' => 360,
				),				
			),
		),
		'studio_options'	=> array(
			'logo'		=> array(
				'logo'	=> array(
					'label'	=> 'Logo',
					'img'	=> TRUE,
				),
				'icon'	=> array(
					'label'	=> 'Ipad Icon',
					'img'	=> TRUE,
				),
				'itunes_logo'	=> array(
					'label'	=> 'iTunes Logo',
					'img'	=> TRUE,
				),
				'splash_bg_image'	=> array(
					'label'	=> 'Splash Background Image',
					'img'	=> TRUE,
				),
//				'splash_bg_color'	=> array(
//					'label'	=> 'Splash Background Color'
//				),
//				'splash_bg'	=> array(
//					'values'	=> array(
//						'image'	=> 'Background Image',
//						'color'	=> 'Background Color'
//					),
//					'label'		=> 'Splash Background Type'
//				)
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
						'Arial'			=> 'Arial',
						'Helvetica'		=> 'Helvetica',
						'Verdana'		=> 'Verdana',
						'Georgia'		=> 'Georgia',
						'Baskerville'	=> 'Baskerville',
					),
					'label'		=> 'Font'
				),
				'second_font' => array(
					'values'	=> array(
						'Arial'			=> 'Arial',
						'Helvetica'		=> 'Helvetica',
						'Verdana'		=> 'Verdana',
						'Georgia'		=> 'Georgia',
						'Baskerville'	=> 'Baskerville',
					),
					'label'		=> 'Font'
				),
				'main_font_color' => array(
					'label'		=> 'Color'
				),
				'second_font_color' => array(
					'label'		=> 'Color'
				),
			),
			'copyrights'	=> array(
				'copyright'	=> array(
					'label'		=> 'Copyright text'
				)
			),
			'styles' => array(
				'dark'	=> 'Dark',
				'light' => 'Light',
				'biege' => 'Biege'
			),
			'icon' => array(
				'width'  => 24,
				'height' => 24,
			),
		),
		
		'default_styles' => array(
			'dark' => array(
				'studio_bg_color'	=> '#000000',
				'studio_bg'			=> 'color',
				'generic_bg_color'	=> '#000000',
				'generic_bg'		=> 'color',
				'main_font'			=> 'Arial',
				'second_font'		=> 'Arial',
				'main_font_color'	=> '#ffffff',
				'second_font_color'	=> '#ffffff',
			),
			'light' => array(
				'studio_bg_color'	=> '#ffffff',
				'studio_bg'			=> 'color',
				'generic_bg_color'	=> '#ffffff',
				'generic_bg'		=> 'color',
				'main_font'			=> 'Arial',
				'second_font'		=> 'Arial',
				'main_font_color'	=> '#000000',
				'second_font_color'	=> '#000000',
			),
			'biege' => array(
				'studio_bg_color'	=> '#e5d9c7',
				'studio_bg'			=> 'color',
				'generic_bg_color'	=> '#e5d9c7',
				'generic_bg'		=> 'color',
				'main_font'			=> 'Arial',
				'second_font'		=> 'Arial',
				'main_font_color'	=> '#6a645c',
				'second_font_color'	=> '#6a645c',
			),
		),
		
		'application_ajax_fields' => array(
			'studio_bg_color', 'style', 'splash_bg_color', 'generic_bg_color',
			'main_font', 'main_font_color', 'second_font', 'second_font_color', 
			'copyright', 'splash_bg', 'generic_bg', 'studio_bg',
		)
	),	
);