<?php
class AlbumPhotoAvailability extends YsaFormModel 
{
	public $can_order;
	
	public $can_share;
	
    public function rules() 
    {
        return array(
			array('can_order, can_share', 'safe'),
			array('can_order, can_share', 'numerical', 'integerOnly'=>true),
        );
    }
	
	public function attributeLabels()
	{
		return array(
			'can_order' => 'Available for order',
			'can_share'	=> 'Available for share',
		);
	}
}