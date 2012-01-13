<?php

/**
 * OAuth Services UserIdentity
 *
 */
class ServiceUserIdentity extends YsaUserIdentity
{
	const ERROR_NOT_AUTHENTICATED = 33;

	/**
	 * @var EAuthServiceBase the authorization service instance.
	 */
	protected $service;

	/**
	 * @var boolean to skip password check in standart authentification routine
	 */
	protected $oauth_skip_passw_check = true;
	
	/**
	 * Constructor.
	 * @param EAuthServiceBase $service the authorization service instance.
	 */
	public function __construct($service) 
	{
		$this->service = $service;
	}

	/**
	 * Authenticates a user based on {@link username}.
	 * This method is required by {@link IUserIdentity}.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{		
		if ($this->service->isAuthenticated) 
		{
			$user = User::model()->findByAttributes(array('email' => $this->username = $this->service->getAttribute('email')));
			if (null !== $user) 
			{
				$this->password = $user->password;
				return parent::authenticate();
			}
			$this->errorCode = self::ERROR_EMAIL_INVALID;
		}
		else
		{
			$this->errorCode = self::ERROR_NOT_AUTHENTICATED;
		}
		
		return !$this->errorCode;
	}
}