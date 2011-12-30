<?php
class ZenFolioLogin extends YsaFormModel 
{
    public $username;
    
    public $password;
	
    public function rules() 
    {
        return array(
            array('username, password', 'required'),
        );
    }
}