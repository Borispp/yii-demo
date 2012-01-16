<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class YsaUserIdentity extends CUserIdentity 
{
	const ERROR_EMAIL_INVALID = 3;
	const ERROR_STATE_INACTIVE = 4;
	const ERROR_STATE_BANNED = 5;	
	
	/**
	 * Logged user id
	 * @var int
	 */
	protected $_id;

	protected $oauth_skip_passw_check = false;
	
	public function authenticate() 
	{
		$user = User::model()->findByAttributes(array('email' => $this->username));
		if (null === $user) {
			$this->setError( self::ERROR_EMAIL_INVALID );
		} else {
			if ( $this->oauth_skip_passw_check === false && $user->password !== YsaHelpers::encrypt($this->password)) {
				$this->setError( self::ERROR_PASSWORD_INVALID );
			} elseif ($user->state == User::STATE_BANNED) {
				$this->setError( self::ERROR_STATE_BANNED );
			} elseif ($user->state == User::STATE_INACTIVE) {
				$this->setError( self::ERROR_STATE_INACTIVE );
			} else {
				$this->_id = $user->id;
				if (null === $user->last_login) {
					$lastLogin = time();
				} else {
					$lastLogin = strtotime($user->last_login);
				}
				$this->setState('lastLoginTime', $lastLogin);
				$this->setError( self::ERROR_NONE );
			}
		}

		return !$this->errorCode;
	}
	
	/**
	 * Set both error code and error message
	 * @param integer $code
	 * @throws CException 
	 */
	protected function setError( $code )
	{
		switch ($code)
		{
			case self::ERROR_EMAIL_INVALID: $this->errorMessage = 'Invalid email'; break;
			case self::ERROR_STATE_INACTIVE: $this->errorMessage = 'Account not activated by email'; break;
			case self::ERROR_STATE_BANNED: $this->errorMessage = 'Account is banned'; break;
			case self::ERROR_USERNAME_INVALID: $this->errorMessage = 'Username is invalid'; break;
			case self::ERROR_PASSWORD_INVALID: $this->errorMessage = 'Password is invalid'; break;
			case self::ERROR_UNKNOWN_IDENTITY: $this->errorMessage = 'Unknown identity'; break;
			case self::ERROR_NONE: $this->errorMessage = ''; break;
			default : throw new CException( "Unknown error code [{$code}], can't define error message" );
		}
		
		$this->errorCode = $code;
	}
	
	public function getId() 
	{
		return $this->_id;
	}
}