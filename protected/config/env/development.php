<?php
$envDb = array(
	'connectionString'      => 'mysql:host=office.flosites.com;dbname=yoursturioapp',
	'emulatePrepare'        => true,
	'username'              => 'yourstudioapp',
	'schemaCachingDuration' => 3600,
	'password'              => '6ZpBcVrtA6LaEdrZ',
	'charset'               => 'utf8',
	'enableProfiling'       => true,
	'enableParamLogging'    => true,
);

$envLogRoutes = array(
	array(
		'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
		'levels'=>'error, warning',
		'ipFilters'=>array('127.0.0.1','192.168.1.215'),
	),
	array(
		'class'=>'CFileLogRoute',
		'levels'=>'error, warning',
		'filter'=>'CLogFilter',
	),
	array(
		'class' => 'CWebLogRoute',
		'categories' => 'application',
		'levels'=>'error, warning, trace, profile, info',
	)
);