<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class YsaUserIdentity extends CUserIdentity {

    /**
     * Logged user id
     * @var int
     */
    private $_id;
    
    public function authenticate() 
    {

	$user = User::model()->findByAttributes(array('email' => $this->username));
	if (null === $user) {
	    $this->errorCode = self::ERROR_USERNAME_INVALID;
	} else {
	    if ($user->password !== $user->encrypt($this->password)) {
		$this->errorCode = self::ERROR_PASSWORD_INVALID;
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
	
	return!$this->errorCode;
    }

    public function getId() 
    {
	return $this->_id;
    }
}