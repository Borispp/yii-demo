<?php
$envDb = array(
	'connectionString'      => 'mysql:host=localhost;dbname=yourstu2_test',
	'emulatePrepare'        => true,
	'username'              => 'yourstu2_test',
	'schemaCachingDuration' => 3600,
	'password'              => 'te5tdb',
	'charset'               => 'utf8',
	'enableProfiling'       => true,
	'enableParamLogging'    => true,
);

$envLogRoutes = array(
	array(
		'class'=>'CFileLogRoute',
		'levels'=>'error, warning',
		'filter'=>'CLogFilter',
	),
	array(
		'class' => 'CDbLogRoute',
		'connectionID' => 'db',
		'autoCreateLogTable' => 'true',
		'levels' => 'error, warning',
		'logTableName' => 'error_log',
	),				
	array(
		'class'=>'CEmailLogRoute',
		'levels'=>'error, warning',
//      'emails'=>'eugen@flosites.com',
	),
);

$envModules = array(
	'api',
	'member',
	'admin',
);