<?php
class DefaultController extends YsaMemberController
{
	public function actionIndex()
	{
		$zd = new Zendesk;
		$requests = $zd->requests($this->member()->email);
		
		$this->setMemberPageTitle(Yii::t('title', 'member_area'));
		$this->render('index', array(
			'zd_requests' => $requests
		));
	}
}