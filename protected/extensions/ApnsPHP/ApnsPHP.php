<?php
/**
 * Facade Class To Include AuthorizeNet library
 */
class ApnsPHP
{
	public function __construct()
	{
		Yii::import('ext.ApnsPHP.*');
	}
}