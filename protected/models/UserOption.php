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
	const SMUGMUG_API = 'smug_api';	
	const SMUGMUG_SECRET = 'smug_secret';	
	const SMUGMUG_HASH = 'smug_hash';	
	const SMUGMUG_REQUEST = 'smug_request';	
	const SMUGMUG_AUTHORIZED = 'smug_authorized';
	
	const ZENFOLIO_HASH = 'zenf_hash';
	const ZENFOLIO_LOGIN = 'zenf_login';
	
	const FIVE00_API = '500px_api';	
	const FIVE00_SECRET = '500px_secret';	
	const FIVE00_HASH = '500px_hash';
	
	const FACEBOOK_ID = 'facebook_id';
	
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
	
	/**
	 * Get option value
	 * 
	 * @param string $name
	 * @param integer $userId
	 * @return mixed
	 */
	public function getOption($name, $default = null, $userId = null)
	{
		if (null === $userId) {
			$userId = Yii::app()->user->id;
		}
		
		$option = $this->findByName($name, $userId);
		
		if (!$option) {
			return $default;
		}
		
		return YsaHelpers::isSerialized($option->value) ? unserialize($option->value) : $option->value;
	}
	
	public function editOption($name, $value, $userId = null)
	{
		if (null === $userId) {
			$userId = Yii::app()->user->id;
		}
		
		if (is_array($value) || is_object($value)) {
			$value = serialize($value);
		}
		
		$entry = $this->findByName($name, $userId);
		
		if (!$entry) {
			$entry = new UserOption();
			$entry->name = $name;
			$entry->user_id = $userId;
		}
		
		$entry->value = $value;
		
		$entry->save();
		
		return $this;
	}
	
	/**
	 * Delete option
	 * 
	 * @param string $name
	 * @param integer $userId
	 * @return bool|integer boolean whether the deletion is successful or integer the number of rows deleted
	 */
	public function deleteOption($name, $userId = null)
	{
		if (null === $userId) {
			$userId = Yii::app()->user->id;
		}
		
		$option = $this->findByName($name, $userId);
		
		if ($option) {
			return $option->delete();
		}
		
		return true;
	}
	
	/**
	 * Find option by name and userID
	 * 
	 * @param string $name
	 * @param integer $userId
	 * @return UserOption
	 */
	public function findByName($name, $userId = null)
	{
		if (null === $userId) {
			$userId = Yii::app()->user->id;
		}
		
		return $this->findByAttributes(array(
			'name'		=> $name,
			'user_id'	=> $userId,
		));
	}
}