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
			$this->errorCode = self::ERROR_EMAIL_INVALID;
		} else {
			if ( $this->oauth_skip_passw_check === false && $user->password !== YsaHelpers::encrypt($this->password)) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} elseif ($user->state == User::STATE_BANNED) {
				$this->errorCode = self::ERROR_STATE_BANNED;
			} elseif ($user->state == User::STATE_INACTIVE) {
				$this->errorCode = self::ERROR_STATE_INACTIVE;
			} else {
				$this->_id = $user->id;
				if (null === $user->last_login) {
					$lastLogin = time();
				} else {
					$lastLogin = strtotime($user->last_login);
				}
				$this->setState('lastLoginTime', $lastLogin);
				$this->errorCode = self::ERROR_NONE;
			}
		}

		return !$this->errorCode;
	}
	
	public function getId() 
	{
		return $this->_id;
	}
}