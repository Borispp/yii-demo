<?php
class Five00Api extends YsaFormModel 
{
    public $five00_api;
    
    public $five00_secret;
	
    public function rules() 
    {
        return array(
            array('five00_api, five00_secret', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'five00_api' => 'API Key',
            'five00_secret' => 'Secret Key',
        );
    }
}