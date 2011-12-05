<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'YourStudioApp',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
        'application.widgets.*',
        'application.extensions.*',
        'application.extensions.yii-mail.*',
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
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                ),

                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels'=>'error, warning, trace, profile, info',
                ),
            ),
        ),
        
        
        
        'mail' => array(
            'class' => 'application.extensions.yii-mail.YiiMail',
            'transportType' => 'php',
            'transportOptions' => array(),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
        
        'settings'  => array(
            'class' => 'application.components.YsaSettings'
        )
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'    =>'webmaster@yourstudioapp.com',
        'admin_per_page'=> 10,
        'salt'          => 'wel0veyourstud1oapp',
        'date_format'   => 'Y-m-d H:i:s',
    ),
);