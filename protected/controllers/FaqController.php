<?php
class FaqController extends YsaFrontController
{
	public function actionIndex()
	{
		$page = Page::model()->findBySlug('faq');
		
		$this->setMeta($page->meta());
		
		$this->setFrontPageTitle($page->title);
		
		$this->render('index',array(
			'faq'	=> Faq::model()->findAll(array(
				'condition' => 'state=:state',
				'params'    => array(':state' => Faq::STATE_ACTIVE),
			)),
			'page' => $page,
		));
	}
}