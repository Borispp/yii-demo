<?php

class DefaultController extends YsaAdminController
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->limit = 5;
//		$criteria->order = '';
		$applications = Application::model()->findAll($criteria);

		$this->setContentTitle('Dashboard');
//		$this->setContentDescription('view all applications.');

		$this->render('index',array(
			'applications'   => $applications,
		));
	}
}