<?php

/**
 * OAuth Services UserIdentity
 *
 */
class ServiceUserIdentity extends YsaUserIdentity
{
	const ERROR_OAUTH_NOT_AUTHENTICATED = 33;

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
		if ( !$this->service->isAuthenticated )
			return ! $this->setError( self::ERROR_OAUTH_NOT_AUTHENTICATED );

		$user = User::model()->fetchByFacebookId( $this->service->getAttribute('id') );
		if (null === $user) 
			return ! $this->setError( self::ERROR_UNKNOWN_IDENTITY );
		
		$this->username = $user->email;
		$this->password = $user->password;
		return parent::authenticate();
	}
	
	protected function setError( $code )
	{
		switch ( $code )
		{
			case self::ERROR_OAUTH_NOT_AUTHENTICATED: 
				$this->errorCode = $code; 
				$this->errorMessage = 'Remote authentication failed'; 
				break;
			default : return parent::setError($code);
		}
		return $code;
	}
}