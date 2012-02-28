<?php
define('APPLICATION_ENV', @getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');

// change the following paths if necessary
$yiic=dirname(__FILE__).'/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
