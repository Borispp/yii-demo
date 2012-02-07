<?php
class RegistrationForm extends Member 
{
	public $verifyPassword;
	public $verifyCode;
	
	public $subscribe;
		
	public function rules() 
	{
		return array(
			array('email','length','max'=>256),
			// make sure email is a valid email
			array('email','email'),
			array('email', 'unique'),
			
			array('password','length','max'=>64, 'min'=>6),
			// compare password to repeated password
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => 'Please repeat your password correctly'),
			// make sure username and email are unique
			array('email, password, first_name, last_name, verifyPassword', 'required'),
			array('subscribe', 'boolean'),
			array('subscribe', 'safe'),
			// verifyCode needs to be entered correctly verifyCode
//			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
		);
	}
	
	public function attributeLabels() {
		return array(
			'subscribe' => "Subscribe to newsletter",
		) + parent::attributeLabels();
	}
}