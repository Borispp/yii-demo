<?php
class FacebookSettingsForm extends YsaFormModel 
{    
    public $fb_id;
	
    public function rules() 
    {
        return array(
            array('fb_id', 'required'),
        );
    }
}