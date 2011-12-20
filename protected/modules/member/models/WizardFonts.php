<?php
class WizardFonts extends Wizard 
{
    public $main_font;

    public $second_font;
    
    public $main_font_color;
    
    public $second_font_color;

    public function rules() 
    {
        return array(
            array('main_font, second_font', 'required'),
            array('main_font_color, second_font_color', 'safe'),
        );
    }

    public function prepare()
    {
        parent::prepare();
        
        $this->prepareFont('main_font')
             ->prepareFont('second_font');
        
        return $this;
    }
}