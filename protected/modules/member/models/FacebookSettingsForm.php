<?php
class FacebookSettingsForm extends YsaFormModel 
{
    public $email;
    
    public $fb_id;
	
    public function rules() 
    {
        return array(
            array('email, fb_id', 'required'),
        );
    }
}