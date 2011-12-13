<?php
class WizardColors extends YsaFormModel 
{
    public $mainColor;

    public $secondColor;
    
    public $backgroundColor;
    
    
    
    public function rules() 
    {
        return array(
            array('mainColor, secondColor, backgroundColor', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'mainColor'         => 'Main Color',
            'secondColor'       => 'Second Color',
            'backgroundColor'   => 'Background Color',
        );
    }
}