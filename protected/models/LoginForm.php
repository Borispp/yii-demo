<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends YsaFormModel
{
	public $email;
	public $password;
	public $rememberMe;
        
        protected $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
            return array(
                // username and password are required
                array('email, password', 'required'),
                // rememberMe needs to be a boolean
                array('rememberMe', 'boolean'),
                // password needs to be authenticated
                array('password', 'authenticate'),
            );
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
            return array(
                'rememberMe'=>'Remember me next time',
            );
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
            if (!$this->hasErrors()) {
                $this->_identity = new YsaUserIdentity($this->email, $this->password);
                if (!$this->_identity->authenticate()) {
                    switch($this->_identity->errorCode) {
                        case YsaUserIdentity::ERROR_EMAIL_INVALID:
                            $this->addError("username", "Email is incorrect.");
                            break;
                        case YsaUserIdentity::ERROR_STATE_INACTIVE:
                            $this->addError("status", "You account is not activated. Please activate it.");
                            break;
                        case YsaUserIdentity::ERROR_STATE_BANNED:
                            $this->addError("status", "You account is blocked. Please contact administrator.");
                            break;
                        case YsaUserIdentity::ERROR_PASSWORD_INVALID:
                            $this->addError("password", "Password is incorrect.");
                            break;
                    }
                }
            }
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
        {
            if($this->_identity===null) {
                $this->_identity=new YsaUserIdentity($this->email,$this->password);
                $this->_identity->authenticate();
            }
            if($this->_identity->errorCode===YsaUserIdentity::ERROR_NONE) {
                    $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
                    Yii::app()->user->login($this->_identity, $duration);

                    User::model()->updateByPk(
                        $this->_identity->id, 
                        array(
                            'last_login'    => new CDbExpression('NOW()'),
                            'last_login_ip' => $_SERVER['REMOTE_ADDR'],
                        )
                    );
                    return true;
            } else {
                return false;
            }
	}
}



