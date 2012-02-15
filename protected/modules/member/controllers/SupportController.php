<?php
class SupportController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->crumb('Support');
		$this->setMemberPageTitle(Yii::t('title', 'support'));
		
		$this->render('index');
	}
}