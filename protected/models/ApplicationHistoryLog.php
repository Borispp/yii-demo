<?php

/**
 * This is the model class for table "application_history_log".
 *
 * The followings are the available columns in table 'application_history_log':
 * @property string $id
 * @property integer $user_id
 * @property string $type
 * @property string $action
 * @property string $created
 * @property integer $app_id
 * @property Application $application
 */
class ApplicationHistoryLog extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ApplicationHistoryLog the static model class
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
		return 'application_history_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_id, type, created', 'required'),
			array('user_id', 'safe'),
			array('app_id, user_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>20),
			array('action', 'length', 'max'=>255),
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
			'application' => array(self::BELONGS_TO, 'Application', 'app_id'),
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
			'app_id' => 'Application',
			'type' => 'Type',
			'action' => 'Action',
			'created' => 'Created',
		);
	}
}