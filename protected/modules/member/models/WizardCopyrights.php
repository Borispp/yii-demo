<?php
class WizardCopyrights extends YsaFormModel 
{
    public $copyright;
    
    public function rules() 
    {
        return array(
            array('copyright', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'copyright'         => 'Copyright',
        );
    }
}