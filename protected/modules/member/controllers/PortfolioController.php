<?php

class PortfolioController extends YsaMemberController
{
	public function actionIndex()
	{
//		$entries = 
		
		$a = new PortfolioPhoto();
		
		$this->setMemberPageTitle('Portfolio');
		
		$this->render('index');
	}
}