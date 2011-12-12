<?php
class WizardLogo extends YsaFormModel 
{
    public $logo;
    
    public function rules() 
    {
        return array(
            array('logo', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'logo' => 'Logo',
        );
    }
}