<?php
class WizardSubmit extends Wizard 
{
    public $finish;
    
    public function rules() 
    {
        return array(
            array('finish', 'safe'),
        );
    }
}