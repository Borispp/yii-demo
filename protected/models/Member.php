<?php
/**
 * @property UserSubscription $UserSubscription
 */
class Member extends User
{
	protected $_application = null;
	
	protected $_smugmug;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function relations()
	{
		return array(
			'UserSubscription'	=> array(self::HAS_MANY, 'UserSubscription', 'user_id'),
			'Membership'		=> array(self::MANY_MANY, 'Membership', 'user_subscription(user_id, membership_id)')
		)+parent::relations();
	}

	public function application()
	{
		if (null === $this->_application) {
			$this->_application = Application::model()->findByMember($this->id);
		}

		return $this->_application;
	}

	/**
	 * @TODO instead of userSubscription foreach use sql-query
	 * @return bool
	 */
	public function isExpired()
	{
		foreach($this->UserSubscription as $obUserSubscription)
		{
			if ($this->isExpired())
				return TRUE;
		}
		return FALSE;
	}

	/**
	 * @TODO instead of userSubscription foreach use sql-query
	 * @return bool
	 */
	public function hasSubscription()
	{
		foreach($this->UserSubscription as $obUserSubscription)
			if ($obUserSubscription->isActive())
				return TRUE;
		return FALSE;
	}

	public function getLastPaidSubscriptionDate()
	{
		$lastDate = time();
		foreach($this->UserSubscription as $obUserSubscription)
		{
			if ($obUserSubscription->state == UserSubscription::STATE_INACTIVE)
				continue;
			if ($lastDate < strtotime($obUserSubscription->expiry_date))
				$lastDate = strtotime($obUserSubscription->expiry_date);
		}
		return date('Y-m-d', $lastDate);
	}
	
	
	/**
	 * Retreive SmugMug Object
	 * @return phpSmug
	 */
	public function smugmug()
	{
		if (null === $this->_smugmug) {
			$this->_smugmug = new phpSmug(array(
				'APIKey' => $this->option(UserOption::SMUGMUG_API), 
				'OAuthSecret' => $this->option(UserOption::SMUGMUG_SECRET), 
				'AppName' => Yii::app()->settings->get('site_title'), 
				'APIVer' => '1.3.0',
			));
		}
		
		return $this->_smugmug;
	}
	
	public function smugmugSetRequestToken($token = null)
	{
		if (null === $token) {
			$token = $this->option(UserOption::SMUGMUG_REQUEST);
		}
		$this->smugmug()->setToken("id={$token['Token']['id']}", "Secret={$token['Token']['Secret']}");
	}
	
	public function smugmugSetAccessToken($token = null)
	{
		if (null === $token) {
			$token = $this->option(UserOption::SMUGMUG_HASH);
			$this->smugmug()->setToken("id={$token['Token']['id']}", "Secret={$token['Token']['Secret']}");
		}
	}
	
	public function smugmugAuthorized()
	{
		return (int) $this->option(UserOption::SMUGMUG_AUTHORIZED);
	}
}