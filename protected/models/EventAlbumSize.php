<?php

/**
 * This is the model class for table "event_album_size".
 *
 * The followings are the available columns in table 'event_album_size':
 * @property integer $album_id
 * @property integer $size_id
 */
class EventAlbumSize extends YsaActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function primaryKey()
	{
		return array('album_id', 'size_id');
	}
	
	public function tableName()
	{
		return 'event_album_size';
	}

	public function rules()
	{
		return array(
			array('album_id, size_id', 'required'),
			array('album_id, size_id', 'numerical', 'integerOnly'=>true),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'album_id' => 'Album',
			'size_id' => 'Size',
		);
	}
}