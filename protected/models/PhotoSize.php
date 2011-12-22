<?php

/**
 * This is the model class for table "photo_size".
 *
 * The followings are the available columns in table 'photo_size':
 * @property string $id
 * @property string $title
 * @property integer $height
 * @property integer $width
 * @property string $description
 * @property integer $state
 */
class PhotoSize extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PhotoSize the static model class
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
		return 'photo_size';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('height, width, state', 'numerical', 'integerOnly'=>true),
			array('title, height, width', 'required'),
			array('title', 'length', 'max'=>100),
			array('description', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'height' => 'Height',
			'width' => 'Width',
			'description' => 'Description',
			'state' => 'State',
		);
	}
}