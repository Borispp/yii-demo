<?php
class WizardColors extends Wizard 
{
    public $studio_bg = 'image';
    
    public $studio_bg_image;
    
    public $studio_bg_color;
    
    public $generic_bg = 'image';
    
    public $generic_bg_image;
    
    public $generic_bg_color;

    public function rules() 
    {
        return array(
            array('studio_bg_image, generic_bg_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.', 'allowEmpty' => true),
            array('studio_bg_color, generic_bg_color', 'safe')
        );
    }

    public function prepare()
    {
        parent::prepare();

        $this->prepareBackgroundType('generic_bg')
             ->prepareBackgroundType('studio_bg');

        if (self::BG_IMAGE == $this->studio_bg) {
            $this->prepareImage('studio_bg_image');
        }

        if (self::BG_IMAGE == $this->generic_bg) {
            $this->prepareImage('generic_bg_image');
        }

        return $this;
    }
}