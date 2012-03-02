<?php
class AdminForm extends Admin
{
	public $verifyPassword;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules() 
	{
		return array(
			array('email','length','max'=>100),
			array('email','email'),
			array('email', 'unique'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('password','length','max'=>64, 'min'=>6),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => 'Please repeat your password correctly'),
			array('password, verifyPasword', 'safe'),
			array('email, first_name, last_name', 'required'),
		);
	}
}