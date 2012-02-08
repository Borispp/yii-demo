<?php
class TourController extends YsaFrontController
{
	public function actionIndex()
	{
		$page = Page::model()->findBySlug('tour');
		
		$this->setMeta($page->meta());
		
//		$this->setFrontPageTitle($page->title);
		
		$this->setFrontPageTitle('&nbsp;');
		
		$this->render('index', array(
			'page' => $page,
		));
	}
}