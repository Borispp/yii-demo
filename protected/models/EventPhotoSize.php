<?php

/**
 * This is the model class for table "event_photo_size".
 *
 * The followings are the available columns in table 'event_photo_size':
 * @property integer $photo_id
 * @property integer $size_id
 */
class EventPhotoSize extends YsaActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return array('photo_id', 'size_id');
	}

	public function tableName()
	{
		return 'event_photo_size';
	}

	public function rules()
	{
		return array(
			array('photo_id, size_id', 'required'),
			array('photo_id, size_id', 'numerical', 'integerOnly'=>true),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'photo_id' => 'Photo',
			'size_id' => 'Size',
		);
	}
}