<?php

require_once __DIR__.'/../../../framework/yiit.php';
require_once __DIR__.'/../../../extensions/goutte.phar';

Yii::createWebApplication( __DIR__.'/../../../config/main.php' );
Yii::import('system.test.CTestCase');
//Yii::import('system.test.CDbTestCase');
//Yii::import('system.test.CWebTestCase');

define( 'TEST_BASE_URL', 'http://yourstudioapp.dev/' );