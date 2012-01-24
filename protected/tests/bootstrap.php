<?php

// change the following paths if necessary
$config=dirname(__FILE__).'/../config/test.php';

require_once dirname(__FILE__).'/../framework/yiit.php';;
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);