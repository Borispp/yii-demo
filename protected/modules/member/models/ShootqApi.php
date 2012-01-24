<?php
class ShootqApi extends YsaFormModel 
{
    public $shootq_abbr;
    
    public $shootq_key;
	
	public $shootq_enabled;

    public function rules() 
    {
        return array(
            array('shootq_abbr, shootq_key', 'required'),
			array('shootq_enabled', 'numerical', 'integerOnly' => true)
        );
    }

    public function attributeLabels()
    {
        return array(
            'shootq_key' => 'ShootQ API Key',
            'shootq_abbr' => 'ShootQ Brand Abbreviation',
			'shootq_enabled' => 'Send Messages to ShootQ',
        );
    }
}