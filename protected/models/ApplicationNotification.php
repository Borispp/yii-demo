<?php

/**
 * This is the model class for table "application_notification".
 *
 * The followings are the available columns in table 'application_notification':
 * @property string $id
 * @property string $created
 * @property string $message
 * @property integer $application_id
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
			array('application_id', 'required'),
			array('application_id', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>300),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created, message, application_id', 'safe', 'on'=>'search'),
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
			'event'			=> array(self::MANY_MANY, 'Event', 'application_notification_event(app_notification_id, event_id)'),
			'client'			=> array(self::MANY_MANY, 'Client', 'application_notification_client(app_notification_id, client_id)'),
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
			'message'			=> 'Message',
			'application_id'	=> 'Application',
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
		$criteria->compare('message',$this->message,true);
		$criteria->compare('application_id',$this->application_id);

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
			)
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
	 * Marks notification as sent (Creates application_notification_state record with link to device id)
	 * @param $deviceId
	 * @return void
	 */
	public function sent($deviceId)
	{
		$obApplicationNotificatioState = new ApplicationNotificationState();
		$obApplicationNotificatioState->app_notification_id = $this->id;
		$obApplicationNotificatioState->device_id = $deviceId;
		$obApplicationNotificatioState->save();
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
		$obApplicationNotificationClient->client_id = $obClient->id;
		$obApplicationNotificationClient->save();
	}

	/**
	 * @param Client $obClient
	 * @return array
	 */
	public function findByClient(Client $obClient, $deviceId = NULL)
	{
		$criteria=new CDbCriteria;
		$criteria->alias = 'app_n';
		$criteria->join = 'LEFT JOIN application_notification_client ON app_n.id = application_notification_client.app_notification_id'.
		' LEFT JOIN application_notification_event ON application_notification_event.app_notification_id = app_n.id'.
		' LEFT JOIN client_events ON client_events.event_id = application_notification_event.event_id';
		$criteria->params = array(':clientId' => $obClient->id);
		$criteria->condition = 'client_events.client_id = :clientId OR application_notification_client.client_id = :clientId';
		if ($deviceId)
		{
			$criteria->params[':deviceId'] = $deviceId;
			$criteria->condition = '('.$criteria->condition.') AND NOT (SELECT COUNT(*) FROM application_notification_state WHERE application_notification_state.app_notification_id = app_n.id AND device_id = :deviceId)';
		}
		return $this->findAll($criteria);
	}
}