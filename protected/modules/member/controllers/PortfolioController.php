<?php
class PortfolioController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->setMemberPageTitle('Portfolio');
		
		$this->crumb('Portfolio');
		
		$this->render('index');
	}
}