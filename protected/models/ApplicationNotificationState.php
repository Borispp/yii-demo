<?php

/**
 * This is the model class for table "application_notification_state".
 *
 * The followings are the available columns in table 'application_notification_state':
 * @property string $id
 * @property string $device_id
 * @property string $app_notification_id
 * @property string $created
 *
 * The followings are the available model relations:
 * @property ApplicationNotification $appNotification
 */
class ApplicationNotificationState extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ApplicationNotificationState the static model class
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
		return 'application_notification_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_notification_id', 'required'),
			array('device_id', 'length', 'max'=>100),
			array('app_notification_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, device_id, app_notification_id,created', 'safe', 'on'=>'search'),
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
			'appNotification' => array(self::BELONGS_TO, 'ApplicationNotification', 'app_notification_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'					=> 'ID',
			'device_id'				=> 'Device',
			'app_notification_id'	=> 'App Notification',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('app_notification_id',$this->app_notification_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}