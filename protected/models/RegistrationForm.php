<?php
class RegistrationForm extends Member 
{
	public $verifyPassword;
	public $verifyCode;
		
	public $firstName;
	public $lastName;
		
	public function rules() 
	{
		return array(
			array('email','length','max'=>256),
			// make sure email is a valid email
			array('email','email'),
			array('password','length','max'=>64, 'min'=>6),
			array('verifyPassword','length','max'=>64, 'min'=>6),
			// compare password to repeated password
			array('password', 'compare', 'compareAttribute'=>'verifyPassword'),
			// make sure username and email are unique
			array('email', 'unique'),
			array('email, password, first_name, last_name, verifyPassword, verifyCode', 'required'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
		);
	}
	
}