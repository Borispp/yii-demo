<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array
(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'YourStudioApp',
	
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
		'application.extensions.twitteroauth.*',
	),
	
	// application components
	'components'=>array(
	
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=office.flosites.com;dbname=yoursturioapp',
			//			'connectionString' => 'mysql:host=localhost;dbname=ysadev',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'iloveflosites',
			'charset' => 'utf8',
			'enableProfiling' => true,
			'enableParamLogging' => true,
		),
		
		'settings'  => array(
			'class' => 'application.components.YsaSettings'
		),
		
	),	
);