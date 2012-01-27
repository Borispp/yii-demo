<?php

/**
 * This is the model class for table "translation_source".
 *
 * The followings are the available columns in table 'translation_source':
 * @property integer $id
 * @property string $category
 * @property string $message
 */
class TranslationSource extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TranslationSource the static model class
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
		return 'translation_source';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('category', 'length', 'max'=>32),
			array('message', 'safe'),
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
//			'sources'		=> array(self::HAS_MANY, 'TranslationSource', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category' => 'Category',
			'message' => 'Message',
		);
	}

	public function categories()
	{
		
	}
}