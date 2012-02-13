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
		
		//TODO: move credintials to settings
		$this->zd = new Zendesk('yourstudioapp', 'eugen@flosites.com', 'lKsfb2$La4r[Kj', true, true);
		$this->zd->set_output(ZENDESK_OUTPUT_JSON);
		
		return true;
	}
	
	public function actionAdd()
	{
		
		
//		$result = $zd->create(ZENDESK_USERS, array(
//			'details' => array(
//				'email' => 'aljohson@example.com',
//				'name' => 'Al Johnson',
//				'roles' => 4,
//				'restriction-id' => 1,
//				'groups' => array(2, 3)
//			)
//		));
		
		$result = $zd->get('requests', array(
			'on-behalf-of' => 'george@flosites.org'
		));
		
		var_dump($result);
		Yii::app()->end();
		
//		Yii::setPathOfAlias('Versionable', Yii::getPathOfAlias('application.vendors.vZendesk.src.Versionable'));
//		$c = new Versionable\Zendesk\Tag\Collection;
//
//		$t = new Versionable\Zendesk\Tag\Tag('printer');
//		$t->setId(63);
//		$t->setcount(10);
//
//		$c->add($t);
//
//		var_dump($c);
	}
	
	public function actionListRequests()
	{
		$result = $this->zd->get('requests', array(
			'on-behalf-of' => $this->member()->email
		));
		
		if (!$result)
			throw new CException;
		
		$decoded = json_decode($result);
		if ($this->_json_last_error() !== JSON_ERROR_NONE)
			throw new CException;
		
		var_dump($this->member()->email,$result);
		Yii::app()->end();
	}
}