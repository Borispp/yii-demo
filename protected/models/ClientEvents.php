<?php

/**
 * This is the model class for table "client_events".
 *
 * The followings are the available columns in table 'client_events':
 * @property string $client_id
 * @property string $event_id
 * @property string $created
 * @property string $added_by
 *
 * The followings are the available model relations:
 * @property Client $id
 * @property Event $event
 */
class ClientEvents extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ClientEvents the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return array('client_id', 'event_id');
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('client_id, event_id', 'required'),
			array('created, added_by', 'safe'),
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
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Client',
			'event_id' => 'Event',
			'created' => 'Created',
			'added_by' => 'Added By',
		);
	}
}