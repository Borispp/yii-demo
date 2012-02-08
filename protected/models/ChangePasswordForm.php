<?php
class ChangePasswordForm extends YsaFormModel 
{
	public $password;
	
	public $verifyPassword;
	
	public function rules() 
        {
            return array(
				array('password, verifyPassword','required'),
                array('password, verifyPassword','length','max'=>64, 'min'=>6),
                array('password', 'compare', 'compareAttribute'=>'verifyPassword', 'message' => "Please repeat your password correctly"),
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