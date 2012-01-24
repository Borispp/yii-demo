<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property integer $id
 * @property integer $user_id Member ID
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $description
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $added_with
 * @property integer $facebook_id Unsigned Bigint
 *
 * The followings are the available model relations:
 * @property Application $application
 */
class Client extends YsaActiveRecord
{
	public $eventList;
	
	public $selectedEvents = array();
	
	const ADDED_MEMBER = 'member';
	
	const ADDED_APP = 'ipad';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Client the static model class
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
		return 'client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state,user_id', 'numerical', 'integerOnly'=>true),
			array('email', 'unique'),
			array('name, email, password', 'required'),
			array('name, email, password, phone', 'length', 'max'=>100),
			array('added_with, description, created, updated, eventList, selectedEvents', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, email, password, phone, description, state, created, updated', 'safe', 'on'=>'search'),
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
			'member'			=> array(self::BELONGS_TO, 'Member', 'user_id'),
			'auth'				=> array(self::HAS_ONE, 'ClientAuth', 'client_id'),
			'events'			=> array(self::MANY_MANY, 'Event', 'client_events(client_id, event_id)'),
			'app_notification'	=> array(self::MANY_MANY, 'ApplicationNotification', 'application_notificaton(client_id, event_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Member',
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'phone' => 'Phone',
			'description' => 'Description',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function searchCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', Yii::app()->user->getId());
		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];

		if (null === $fields) {
			return $criteria;
		}

		extract($fields);

		// search by state
		if (isset($state) && $state != '')
		{
			$criteria->compare('state', $state);
		}

		if (isset($added_with) && $added_with != '')
		{
			$criteria->compare('added_with', $added_with);
		}

		// sort entries
		if (isset($order_by) && isset($order_sort)) {
			if (!in_array($fields['order_by'], array_keys($this->attributes))) {
				$order_by = 'id';
			}
			if (!in_array($order_sort, array('ASC', 'DESC'))) {
				$order_sort = 'DESC';
			}
			$criteria->order = $order_by . ' ' . $order_sort;
		}
		return $criteria;
	}

		/**
	 * Get search options for search panel
	 *
	 * @return array
	 */
	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}

		$options = array(
			'order_by' => array(
				'label' => 'Order By',
				'type'  => 'select',
				'options' => array(
					'id'    => 'ID',
					'name'  => 'Name',
					'email'  => 'Email',
					'created'  => 'Register Date',
				),
				'value'     => isset($values['order_by']) ? $values['order_by'] : '',
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
			'state' => array(
				'label'             => 'State',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Client::model()->getStates(),
				'value'     => isset($values['state']) ? $values['state'] : '',
			),
			'added_with' => array(
				'label'             => 'Added',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Client::model()->getAddedWithList(),
				'value'     => isset($values['added_with']) ? $values['added_with'] : '',
			),
		);

		return $options;
	}

	public function isOwner()
	{
		return $this->user_id == Yii::app()->user->id;
	}

	public function getAddedWithList()
	{
		return array(
			self::ADDED_MEMBER	=> 'By Photographer',
			self::ADDED_APP		=> 'Registered in Application'
		);
	}

	public function getAddedWith()
	{
		$addedWith = $this->getAddedWithList();
		return $addedWith[$this->added_with];
	}

	/**
	 * @param Member $obMember
	 * @param string $orderBy
	 * @return array
	 */
	public function findAllByMember(Member $obMember, $orderBy = 'id DESC')
	{
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', $obMember->id);
		$criteria->order = $orderBy;
		return $this->findAll($criteria);
	}


	/**
	 * Check if client has access to event
	 * @param Event $obEvent
	 * @return bool
	 */
	public function hasPhotoEvent(Event $obEvent)
	{
		return (bool)$this->_getClientEvent($obEvent);
	}

	/**
	 * @param Event $obEvent
	 * @return ClientEvent
	 */
	protected function _getClientEvent(Event $obEvent)
	{
		return ClientEvents::model()->findByAttributes(array(
			'event_id'	=> $obEvent->id,
			'client_id'	=> $this->id,
		));
	}

	/**
	 * Add client access to event
	 * @param Event $obEvent
	 * @param string $addedBy
	 * @return bool
	 */
	public function addPhotoEvent(Event $obEvent, $addedBy = 'member')
	{
		if (!$this->hasPhotoEvent($obEvent))
		{
			$obClientEvents = new ClientEvents();
			$obClientEvents->client_id = $this->id;
			$obClientEvents->event_id = $obEvent->id;
			$obClientEvents->added_by = $addedBy;
			if (!$obClientEvents->validate())
				return FALSE;
			$obClientEvents->save();
			return TRUE;
		}
	}

	/**
	 * Remove client access to event
	 * @param Event $obEvent
	 * @return bool
	 */
	public function removePhotoEvent(Event $obEvent, $addedBy = NULL)
	{
		if (!$obClientEvents = $this->_getClientEvent($obEvent))
		{
			return FALSE;
		}
		if (is_null($addedBy) || ($addedBy == $obClientEvents->added_by))
		{
			$obClientEvents->delete();
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Selected events for Client
	 * 
	 * @return array 
	 */
	public function selectedEvents()
	{
		$eventsIds = Yii::app()->db->createCommand()
			->select('event_id')
			->from(ClientEvents::model()->tableName() . ' ce')
			->where('client_id=:client_id', array(':client_id'=>$this->id))
			->queryColumn();
		
		return $this->prepareSelectedEvents($eventsIds);
	}
	
	/**
	 * Prepare options for selected Events
	 * 
	 * @param array $ids 
	 * @return array
	 */
	public function prepareSelectedEvents($eventsIds)
	{
		$events = array();
		foreach ($eventsIds as $eventId) {
			$events[$eventId] = array(
				'selected' => 'selected',
			);
		}
		
		return $events;
	}
	
	/**
	 * Save Client Events
	 * @param array $eventsIds
	 * @return Client 
	 */
	public function setEvents($eventsIds)
	{
		ClientEvents::model()->deleteAll('client_id=:client_id', array(
			':client_id' => $this->id,
		));
		foreach ($eventsIds as $eventId) {
			$s = new ClientEvents();
			$s->setAttributes(array(
				'client_id'  => $this->id,
				'event_id'   => (int) $eventId,
				'added_by'	 => self::ADDED_MEMBER,
			));
			
			$s->save();
			unset($s);
		}
		
		return $this;
	}
}