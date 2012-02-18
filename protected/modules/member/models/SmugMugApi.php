<?php
class SmugMugApi extends YsaFormModel 
{
    public $smug_api;
    
    public $smug_secret;
	
    public function rules() 
    {
        return array(
            array('smug_api, smug_secret', 'required'),
			array('smug_api, smug_secret', 'filter', 'trim'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'smug_api' => 'API Key',
            'smug_secret' => 'Secret Key',
        );
    }
}