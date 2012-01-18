<?php
class DefaultController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->setMemberPageTitle('Member Area');
		$this->render('index');
	}
}