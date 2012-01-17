<?php

/**
 * This is the model class for table "client_events".
 *
 * The followings are the available columns in table 'client_events':
 * @property string $id
 * @property string $client_id
 * @property string $event_id
 * @property string $created
 * @property string $added_by
 *
 * The followings are the available model relations:
 * @property Client $id0
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, event_id', 'length', 'max'=>11),
			array('created, added_by', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, client_id, event_id, created, added_by', 'safe', 'on'=>'search'),
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
			'client' => array(self::BELONGS_TO, 'Client', 'id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client',
			'event_id' => 'Event',
			'created' => 'Created',
			'added_by' => 'Added By',
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
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('added_by',$this->added_by,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}