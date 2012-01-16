<?php
/**
 * @property UserSubscription $UserSubscription
 */
class Member extends User
{
	protected $_application = null;
	
	protected $_smugmug;
	
	protected $_five00px;
	
	/**
	 * @var phpZenfolio
	 */
	protected $_zenfolio;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function relations()
	{
		return array(
			'UserSubscription'	=> array(self::HAS_MANY, 'UserSubscription', 'user_id'),
			'clients'			=> array(self::HAS_MANY, 'Client', 'user_id'),
			'Membership'		=> array(self::MANY_MANY, 'Membership', 'user_subscription(user_id, membership_id)'),
			'event'				=> array(self::HAS_MANY, 'Event', 'user_id'),
		) + parent::relations();
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
				'APIKey' => $this->option(UserOption::SMUGMUG_API, '', $this->id), 
				'OAuthSecret' => $this->option(UserOption::SMUGMUG_SECRET, '', $this->id), 
				'AppName' => Yii::app()->settings->get('site_title'), 
				'APIVer' => '1.3.0',
			));
		}
		
		return $this->_smugmug;
	}
	
	public function smugmugSetRequestToken($token = null)
	{
		if (null === $token) {
			$token = $this->option(UserOption::SMUGMUG_REQUEST, '', $this->id);
		}
		$this->smugmug()->setToken("id={$token['Token']['id']}", "Secret={$token['Token']['Secret']}");
		
		return $this;
	}
	
	public function smugmugSetAccessToken($token = null)
	{
		if (null === $token) {
			$token = $this->option(UserOption::SMUGMUG_HASH, '', $this->id);
			$this->smugmug()->setToken("id={$token['Token']['id']}", "Secret={$token['Token']['Secret']}");
		}
		
		return $this;
	}
	
	public function smugmugAuthorized()
	{
		return $this->option(UserOption::SMUGMUG_HASH, '', $this->id) ? true : false;
	}
	
	public function zenfolio()
	{
		if (null === $this->_zenfolio) {
			$this->_zenfolio = new phpZenfolio(array(
				"AppName" => "YourStudioApp/1.0 (http://yourstudioapp.com)",
				"APIVer" => "1.4"
			));
		}
		
		return $this->_zenfolio;
	}
	
	public function zenfolioAuthorize()
	{
		$this->zenfolio()->setAuthToken($this->option(UserOption::ZENFOLIO_HASH));
		
		return $this;
	}
	
	public function zenfolioAuthorized()
	{
		return $this->option(UserOption::ZENFOLIO_HASH) ? true : false;
	}
	
	/**
	 *
	 * @return Five00pxOAuth
	 */
	public function five00px()
	{
		if (null === $this->_five00px) {
			
			if ($this->five00pxAuthorized()) {
				$hash = $this->option(UserOption::FIVE00_HASH);
				$this->_five00px = new Five00pxOAuth(
					Yii::app()->settings->get('500px_consumer_key'),
					Yii::app()->settings->get('500px_consumer_secret'),
					$hash['oauth_token'],
					$hash['oauth_token_secret']
				);
			} else {
				$this->_five00px = new Five00pxOAuth(Yii::app()->settings->get('500px_consumer_key'), Yii::app()->settings->get('500px_consumer_secret'));
			}
		}
		
		return $this->_five00px;
	}
	
	public function five00pxAuthorized()
	{
		return $this->option(UserOption::FIVE00_HASH) ? true : false;
	}
	
	/**
	 * return book
	 */
	public function five00pxAuthorize($token, $secret, $verifier)
	{
		$five = new Five00pxOAuth(
			Yii::app()->settings->get('500px_consumer_key'),
			Yii::app()->settings->get('500px_consumer_secret'), 
			$token,
			$secret
		);

		$accessToken = $five->getAccessToken($verifier);
		
		if (!$accessToken || !isset($accessToken['oauth_token']) || !isset($accessToken['oauth_token_secret'])) {
			return false;
		}
		
		$this->editOption(UserOption::FIVE00_HASH, $accessToken);
		
		return true;
	}
	
	/**
	 * @return boolean
	 */
	public function isFacebookConnected()
	{
		return $this->option(UserOption::FACEBOOK_ID, false, $this->id) ? true : false;
	}
}