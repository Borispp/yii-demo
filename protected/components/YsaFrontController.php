<?php
class YsaFrontController extends YsaController
{
    public $layout='/layouts/general';
    
    public $menu = array();
	
	public $frontPageTitle;
    
    public function init()
    {
        parent::init();
    }
	
	public function setFrontPageTitle($title)
	{
		$this->frontPageTitle = $title;
	}
}