<?php
class WizardSubmit extends YsaFormModel 
{
    public $finish;
    
    public function rules() 
    {
        return array(
            array('finish', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array();
    }
}