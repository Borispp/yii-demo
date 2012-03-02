<?php
class HelpController extends YsaMemberController
{
	public function init() {
		parent::init();
		
		$this->crumb(Yii::t('title', 'support'), array('support/'))
			->crumb(Yii::t('title', 'help'), array('help/'));
		
		$this->setMetaTitle(Yii::t('title', 'Tutorials'));
	}
	
	public function actionIndex()
	{
		$this->setMemberPageTitle(Yii::t('title', 'help'));
		
		$categories = TutorialCategory::model()->findAll(array(
			'order'		=> 'rank ASC',
			'condition' => 'state=:state',
			'params'	=> array(
				'state' => TutorialCategory::STATE_ACTIVE,
			)
		));

		$this->render('index', array(
			'categories' => $categories,
		));
	}
	
	public function actionView($slug)
	{
		$tutorial = Tutorial::model()->findBy('slug', $slug);
		
		if (!$tutorial || !$tutorial->isActive()) {
			$this->redirect(array('help/'));
		}
		
		$this->setMemberPageTitle(Yii::t('title', 'help'));
		
		$this->crumb($tutorial->title);
		
		$this->render('view', array(
			'tutorial' => $tutorial,
		));
	}
}