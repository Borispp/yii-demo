<?php
/**
 * This is the model class for table "application_auth".
 *
 * The followings are the available columns in table 'option_group':
  `type` set('client','proofing') NOT NULL DEFAULT 'client',

 * @property integer $id
 * @property string $device_id
 * @property integer $app_id
 * @property string $token
 * @property integer $state
 * @property string $type
 */
class ApplicationAuth extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OptionGroup the static model class
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
		return 'application_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array();
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
//		return array(
//			array('rank, hidden', 'numerical', 'integerOnly'=>true),
//			array('title, slug', 'length', 'max'=>100),
//			array('slug', 'unique'),
//			array('slug, title', 'required'),
//		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			//'option' => array(self::HAS_MANY, 'Option', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'		=> 'ID',
			'device_id'	=> 'Device ID',
			'app_id'	=> 'Application ID',
			'token'		=> 'Secure Token',
			'state'		=> 'State',
			'type'		=> 'Auth type'
		);
	}
}