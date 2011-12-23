<?php
class ShootqApi extends YsaFormModel 
{
    public $shootq_abbr;
    
    public $shootq_key;

    public function rules() 
    {
        return array(
            array('shootq_abbr, shootq_key', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'shootq_key' => 'ShootQ API Key',
            'shootq_abbr' => 'ShootQ Brand Abbreviation',
        );
    }
}