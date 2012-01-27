<?php
class TranslationController extends YsaAdminController
{
	public function actionIndex($cat)
	{	
		$category = TranslationCategory::model()->findBy('name', $cat);
		
		if (!$category) {
			$this->redirect(array('/admin/'));
		}
		
		$this->setContentTitle('Translations');
		$this->setContentDescription('manage questions.');
		
		$this->render('index', array(
			
		));
	}
}