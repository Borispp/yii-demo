<?php
Yii::import('CLinkPager');
class YsaAdminPager extends CLinkPager
{
    public $cssFile = false;
	
	public $htmlOptions = array(
		'class' => 'pager',
		'id'	=> 'data-pager',
	);
	
	public $header = '';
}