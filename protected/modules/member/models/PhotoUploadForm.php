<?php
class PhotoUploadForm extends YsaFormModel 
{
	public $photo;
	
    public function rules() 
    {
        return array(
            array('photo', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=> Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.', 'allowEmpty' => true),
			array('photo', 'required'),
        );
    }
}