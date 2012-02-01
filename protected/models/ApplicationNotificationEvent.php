<?php

/**
 * This is the model class for table "application_notification_event".
 *
 * The followings are the available columns in table 'application_notification_event':
 * @property string $id
 * @property string $app_notification_id
 * @property string $event_id
 *
 * The followings are the available model relations:
 * @property Event $event
 * @property ApplicationNotification $appNotification
 */
class ApplicationNotificationEvent extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ApplicationNotificationEvent the static model class
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
		return 'application_notification_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_notification_id, event_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, app_notification_id, event_id', 'safe', 'on'=>'search'),
			array('event_id', 'eventValidation')
		);
	}

	public function eventValidation()
	{
		$event = Event::model()->findByPk($this->event_id);
		if ($event->user_id != Yii::app()->user->getId())
		{
			$this->addError('event_id', 'Restricted access to selected event');
			return FALSE;
		}
		if (!$event->isActive())
		{
			$this->addError('event_id', 'Event is inactive');
			return FALSE;
		}
		if (!$event->isPortfolio())
		{
			$this->addError('event_id', 'You can\'t send notification to portfolio event');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'appNotification' => array(self::BELONGS_TO, 'ApplicationNotification', 'app_notification_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'app_notification_id' => 'App Notification',
			'event_id' => 'Event',
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
		$criteria->compare('app_notification_id',$this->app_notification_id,true);
		$criteria->compare('event_id',$this->event_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}