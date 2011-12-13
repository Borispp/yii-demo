<?php
class WizardFonts extends YsaFormModel 
{
    public $mainFont;

    public $secondFont;
    
    public function rules() 
    {
        return array(
            array('mainFont, secondFont', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'mainFont'         => 'Main Font',
            'secondFont'       => 'Second Font',
        );
    }
}