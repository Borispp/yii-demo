<?php
class PricingController extends YsaFrontController
{
	public function actionIndex()
	{
		$page = Page::model()->findBySlug('pricing');
		
		$signupnow = Page::model()->findBySlug('pricing-sign-up-now');
		
		$this->setFrontPageTitle($page->title);
		
		$this->setMeta($page->meta());
		
		$this->render('index', array(
			'page' => $page,
			'signupnow' => $signupnow,
		));
	}
}