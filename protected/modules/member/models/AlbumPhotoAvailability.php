<?php
class AlbumPhotoAvailability extends YsaFormModel 
{
	public $can_order;
	
	public $can_share;
	
	public $order_link;
	
    public function rules() 
    {
        return array(
			array('can_order, can_share, order_link', 'safe'),
			array('order_link', 'length', 'max'=>200),
			array('can_order, can_share', 'numerical', 'integerOnly'=>true),
        );
    }
	
	public function attributeLabels()
	{
		return array(
			'can_order' => 'Available for order',
			'can_share'	=> 'Available for share',
			'order_link' => 'Order Link',
		);
	}
}