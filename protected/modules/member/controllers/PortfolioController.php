<?php
class PortfolioController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->setMemberPageTitle('Portfolio');
		
		$this->render('index');
	}
}