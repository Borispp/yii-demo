<?php

/**
 * This is the model class for table "event_photo_rate".
 *
 * The followings are the available columns in table 'event_photo_rate':
 * @property string $id
 * @property integer $photo_id
 * @property string $rated_by
 * @property integer $rate
 */
class EventPhotoRate extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EventPhotoRate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_photo_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('photo_id', 'required'),
			array('photo_id, rate', 'numerical', 'integerOnly'=>true),
			array('rated_by', 'length', 'max'=>255),
			array('rated_by', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'photo'  => array(self::BELONGS_TO, 'EventPhoto', 'photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'photo_id' => 'Photo',
			'rated_by' => 'Rated By',
			'rate' => 'Rate',
		);
	}
}