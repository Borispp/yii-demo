<?php

class ZendeskController extends YsaMemberController
{
	/**
	 * @var Zendesk 
	 */
	protected $zd;
	
	protected function beforeAction($action) 
	{
		if (!parent::beforeAction($action))
			return false;
		
		Yii::import('application.vendors.*');
		require_once('zendesk/Zendesk.lib.php');
		
		$this->zd = new ZendeskAPI(
			Yii::app()->settings->get('zendesk_account'),
			Yii::app()->settings->get('zendesk_user'),
			Yii::app()->settings->get('zendesk_password'),
			true, 
			true
		);
		$this->zd->set_output(ZENDESK_OUTPUT_JSON);
		
		return true;
	}
	
	public function actionAdd()
	{
		// try to create new user
		$this->zd->create('users', array(
			'details' => array(
				'email' => $email = $this->member()->email,
				'name' => $this->member()->name(),
				
				/**
				 * End user	0
				 * Administrator	2
				 * Agent	4 
				 */
				'roles' => 0,
				
				/**
				 * All tickets - 0
				 * Tickets in member groups - 1
				 * Tickets in member organization - 2
				 * Assigned tickets - 3
				 * Tickets requested by user - 4
				 */
				'restriction-id' => 0,
				
//				'groups' => array(2, 3)
			)
		), 'api/v1/users');	
		
		/**
		 * @see Zendesk::requests
		 */
		Zendesk::deleteRequestsCache($email);
		
		$zd_acc = Yii::app()->settings->get('zendesk_account');
		header("Location: https://{$zd_acc}.zendesk.com/anonymous_requests/new?email=".urlencode($email), true);
		Yii::app()->end();
	}
}