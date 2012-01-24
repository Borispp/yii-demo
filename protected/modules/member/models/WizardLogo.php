<?php
class WizardLogo extends Wizard
{
	public $logo;

	public $icon;

	public $itunes_logo;

	public $splash_bg = 'image';

	public $splash_bg_image;

	public $splash_bg_color;
	
	public $style;

	public function rules()
	{
		return array(
			array('style', 'required'),
//			array('style', 'validatorStyle'),
			array('logo, itunes_logo,icon, splash_bg_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=> Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.', 'allowEmpty' => true),
			array('splash_bg_color', 'safe'),
		);
	}

	public function prepare()
	{
		parent::prepare();

		$this->prepareBackgroundType('splash_bg');

		if (self::BG_IMAGE == $this->splash_bg) {
			$this->prepareImage('splash_bg_image');
		}

		$this->prepareImage('logo');
		$this->prepareImage('itunes_logo');
		$this->prepareImage('icon');

		return $this;
	}
}