<?php
class HelpController extends YsaMemberController
{
	public function init() {
		parent::init();
		
		$this->crumb(Yii::t('title', 'support'), array('support/'))
			->crumb(Yii::t('title', 'help'), array('help/'));
	}
	
	public function actionIndex()
	{
		$this->setMemberPageTitle(Yii::t('title', 'help'));
		
		$this->render('index');
	}
}