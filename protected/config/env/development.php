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

$log_routes = array(
	'class' => 'CWebLogRoute',
	'categories' => 'application',
	'levels'=>'error, warning, trace, profile, info',
);