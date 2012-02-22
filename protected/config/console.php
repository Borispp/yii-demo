<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'env'.DIRECTORY_SEPARATOR.APPLICATION_ENV.'.php';

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
	
		'db' => $envDb,
		
		'settings'  => array(
			'class' => 'application.components.YsaSettings'
		),
		
	),
	
	'commandMap'=>array(
        'migrate'=>array(
            'class'=>'system.cli.commands.MigrateCommand',
            'migrationPath'=>'application.db_migrations',
            'migrationTable'=>'sys_migration',
            'connectionID'=>'db',
            //'templateFile'=>'application.migrations.template',
        ),
    ),
	
);