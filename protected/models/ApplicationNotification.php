<?php

/**
 * This is the model class for table "application_notification".
 *
 * The followings are the available columns in table 'application_notification':
 * @property string $id
 * @property string $created
 * @property integer $sent
 * @property string $message
 * @property integer $application_id
 * @property integer $event_id
 *
 * @property Application $application
 * @property Event $event
 */
class ApplicationNotification extends YsaActiveRecord
{
	public $events;
	public $clients;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ApplicationNotification the static model class
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
		return 'application_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('application_id, event_id', 'required'),
			array('sent, application_id, event_id', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>300),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created, sent, message, application_id, event_id', 'safe', 'on'=>'search'),
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
			'application'	=> array(self::BELONGS_TO, 'Application', 'application_id'),
			'event'			=> array(self::BELONGS_TO, 'Event', 'event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'created'			=> 'Created',
			'sent'				=> 'Sent',
			'message'			=> 'Message',
			'application_id'	=> 'Application',
			'event_id'			=> 'Event',
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
		$criteria->compare('created',$this->created,true);
		$criteria->compare('sent',$this->sent);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('application_id',$this->application_id);
		$criteria->compare('event_id',$this->event_id);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}


	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}
		$options = array(
			'keyword' => array(
				'label'     => 'Keyword',
				'type'      => 'text',
				'default'   => '',
				'value'     => isset($values['keyword']) ? $values['keyword'] : '',
			),
			'order_by' => array(
				'label'		=> 'Order By',
				'type'		=> 'select',
				'value'		=> isset($values['order_by']) ? $values['order_by'] : '',
				'options'	=> array(
					'id'		=> 'ID',
					'created'	=> 'Date',
				),
			),
			'order_sort' => array(
				'label' => 'Order Sort',
				'type'  => 'select',
				'options' => array(
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				),
				'value'     => isset($values['order_sort']) ? $values['order_sort'] : '',
			),
			'sent' => array(
				'label'             => 'State',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => array(1 => 'Sent', 0 => 'Unsent'),
				'value'     => isset($values['sent']) ? $values['sent'] : '',
			),
			'event_id' => array(
				'label'             => 'Event',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => CHtml::listData(Member::model()->findByPk(Yii::app()->user->getId())->event, 'id', 'name'),
				'value'     => isset($values['event_id']) ? $values['event_id'] : '',
			),
		);

		return $options;
	}


	public function searchCriteria()
	{
		$obApplication = Member::model()->findByPk(Yii::app()->user->getId())->application;
		$criteria = new CDbCriteria();
		$criteria->compare('application_id', $obApplication->id);
		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];

		if (null === $fields) {
			return $criteria;
		}
		extract($fields);
		// search by keyword
		if (isset($keyword) && $keyword) {
			$criteria->compare('message', $keyword, true, 'AND');
		}
		// search by state
		if (isset($sent)) {
			$criteria->compare('sent', $sent);
		}

		if (isset($event_id) && $event_id) {
			$criteria->compare('event_id', $event_id);
		}

		// sort entries
		if (isset($order_by) && isset($order_sort)) {
			if (!in_array($fields['order_by'], array_keys($this->attributes)))
				$order_by = 'id';
			if (!in_array($order_sort, array('ASC', 'DESC')))
				$order_sort = 'DESC';
			$criteria->order = $order_by . ' ' . $order_sort;
		}

		return $criteria;
	}

	public function findByApplication(Application $obApp)
	{
		return $this->findAll(array(
				'condition' => 'application_id='.$obApp->id
			));
	}

	/**
	 * @param Application $obApp
	 * @param Event $obEvent
	 * @return ApplicationNotification
	 */
	public function findByApplicationAndEvent(Application $obApp, Event $obEvent)
	{
		return $this->findAllByAttributes(array(
			'application_id'	=> $obApp->id,
			'event_id'			=> $obEvent->id,
			'sent'				=> 0
		));
	}

	/**
	 * Marks notification as sent (Creates application_notification_state record with link to device id)
	 * @param $deviceId
	 * @return void
	 */
	public function sent($deviceId)
	{
		$obApplicationNotificatioState = new ApplicationNotificationState();
		$obApplicationNotificatioState->app_notification_id = $this->id;
		$obApplicationNotificatioState->device_id = $deviceId;
		$this->sent = 1;
		$this->save();
	}

	/**
	 * @param Event $obEvent
	 * @return void
	 */
	public function appendToEvent(Event $obEvent)
	{
		$obApplicationNotificationEvent = new ApplicationNotificationEvent();
		$obApplicationNotificationEvent->app_notification_id = $this->id;
		$obApplicationNotificationEvent->event_id = $obEvent->id;
		$obApplicationNotificationEvent->save();
	}

	/**
	 * @param Client $obClient
	 * @return void
	 */
	public function appendToClient(Client $obClient)
	{
		$obApplicationNotificationClient = new ApplicationNotificationClient();
		$obApplicationNotificationClient->app_notification_id = $this->id;
		$obApplicationNotificationClient->clien_id = $obClient->id;
		$obApplicationNotificationClient->save();
	}
}