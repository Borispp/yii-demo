<?php
class NewsletterForm extends YsaFormModel
{
	public $email;
	
	public $name;
	
	public function rules()
	{
		return array(
			array('email, name', 'required'),
			array('email', 'email'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => 'Email',
			'name' => 'Name',
		);
	}
}



