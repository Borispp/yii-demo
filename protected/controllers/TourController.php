<?php
class TourController extends YsaFrontController
{
	public function actionIndex()
	{
		$this->setFrontPageTitle(Yii::t('title', 'Tour'));
		
		$this->render('index', array(
			
		));
	}
}