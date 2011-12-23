<?php
class SpecialsUploadForm extends YsaFormModel 
{
	public $specials;
	
    public function rules() 
    {
        return array(
            array('specials', 'file', 'types'=>'jpg, gif, png, pdf', 'maxSize'=> Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.'),
			array('specials', 'required'),
        );
    }
	
	public function labels()
	{
		return array(
			'specials' => 'Specials File'
		);
	}
}