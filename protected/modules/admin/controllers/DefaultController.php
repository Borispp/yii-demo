<?php

class DefaultController extends YsaAdminController
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->limit = 5;
		$criteria->order = 'updated DESC';
		$applications = Application::model()->findAll($criteria);

		$criteria = new CDbCriteria;
		$criteria->order = 'created DESC';
		$criteria->limit = 5;
		$c_messages = ContactMessage::model()->findAll($criteria);
		
		$criteria = new CDbCriteria;
		$criteria->order = 'created DESC';
		$criteria->limit = 5;
		$payments = PaymentTransaction::model()->findAll($criteria);
		
		$totals = array();
		$totals[] = array('title'=>'Members', 'count'=> Member::model()->count());
		$totals[] = array('title'=>'Events', 'count'=> Event::model()->count());
		$totals[] = array('title'=>'Albums', 'count'=> EventAlbum::model()->count());
		$totals[] = array('title'=>'Photos', 'count'=> EventPhoto::model()->count());
		$totals[] = array('title'=>'Clients', 'count'=> Client::model()->count());
		
		$this->setContentTitle('Dashboard');
//		$this->setContentDescription('view all applications.');
		$this->render('index',array(
			'applications' => $applications,
			'c_messages' => $c_messages,
			'totals' => $totals,
			'payments' => $payments
		));
	}
}