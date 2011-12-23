<?php

/**
 * This is the model class for table "user_option".
 *
 * The followings are the available columns in table 'user_option':
 * @property string $id
 * @property integer $user_id
 * @property string $name
 * @property string $value
 * @property integer $type_id
 * @property string $created
 * @property string $updated
 */
class UserOption extends YsaOptionActiveRecord
{
	const OPT_ABOUT_IMAGE = 'about_image';
	
	const OPT_ABOUT_TEXT = 'about_text';
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserOption the static model class
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
		return 'user_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, type_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('value, created, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, value, type_id, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'type'  => array(self::HAS_ONE, 'OptionGroup', 'type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'name' => 'Name',
			'value' => 'Value',
			'type_id' => 'Type',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}
}