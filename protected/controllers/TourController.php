<?php
class TourController extends YsaFrontController
{
	public function actionIndex()
	{
		$page = Page::model()->findBySlug('tour');
		
		$this->setMeta($page->meta());
		
		$this->setFrontPageTitle(Yii::t('title', $page->title));
		
		$this->render('index', array(
			'page' => $page,
		));
	}
}