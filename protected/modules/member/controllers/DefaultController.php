<?php
class DefaultController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->setMemberPageTitle(Yii::t('title', 'member_area'));
		$this->render('index');
	}
}