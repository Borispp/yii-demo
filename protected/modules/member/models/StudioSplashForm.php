<?php
class StudioSplashForm extends YsaFormModel 
{
	public $image;
	
	public $text;
	
    public function rules() 
    {
        return array(
            array('image', 'file', 'types'=>'jpg, jpeg, gif, png, pdf', 'maxSize'=> Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.', 'allowEmpty' => true),
			array('image, text', 'safe'),
        );
    }
	
	public function labels()
	{
		return array(
			'image' => 'Splash Image',
			'text'	=> 'Area',
		);
	}
}