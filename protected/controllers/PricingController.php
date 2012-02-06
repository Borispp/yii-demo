<?php
class PricingController extends YsaFrontController
{
	public function actionIndex()
	{
		$page = Page::model()->findBySlug('pricing');
		
		$this->setFrontPageTitle($page->title);
		
		$this->render('index', array(
			'page' => $page,
		));
	}
}