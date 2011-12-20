<?php
class ChangePasswordForm extends YsaFormModel 
{
	public $password;
	public $verifyPassword;
	
	public function rules() 
        {
            return array(
                array('password, verifyPassword', 'required'),
                array('password', 'length', 'max'=>50, 'min' => 4,'message' => "Incorrect password (minimal length 4 symbols)."),
                array('password', 'compare', 'compareAttribute'=>'verifyPassword', 'message' => "Retype Password is incorrect."),
            );
	}
        
	public function attributeLabels()
	{
            return array(
                'password' => 'Password',
                'verifyPassword' => 'Retype Password',
            );
	}
}