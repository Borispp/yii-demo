<?php

/**
 * This is the model class for table "ipad".
 *
 * The followings are the available columns in table 'ipad':
 * @property string $id
 * @property string $device_id
 * @property string $token
 * @property string $created
 * @property string $client_id
 * @property string $app_id
 *
 * The followings are the available model relations:
 * @property Client $client
 * @property Application $app
 */
class Ipad extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ipad the static model class
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
		return 'ipad';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_id', 'required'),
			array('device_id, token', 'length', 'max'=>100),
			array('client_id, app_id', 'length', 'max'=>11),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, device_id, token, created, client_id, app_id', 'safe', 'on'=>'search'),
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
			'device_id' => 'Device',
			'token' => 'Token',
			'created' => 'Created',
			'client_id' => 'Client',
			'app_id' => 'App',
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
		$criteria->compare('token',$this->token,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('app_id',$this->app_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Assign notification to all devices with same client logged in
	 * @param Client $client
	 * @param $notification
	 */
	public function addNotificatonByClient(Client $client, IpadNotification $notification)
	{
		$clientIpadList = $this->findAllByAttributes(array(
			'client_id' => $client->id,
			'app_id'    => $client->member->application->id
		));
		if (empty($clientIpadList))
			return 'No registered ipads for this client found';
		foreach($clientIpadList as $ipad)
		{
			$ipad->addNotification($notification);
		}
		return TRUE;
	}

	public function addNotificationByApplication(Application $app, IpadNotification $notification)
	{
		$ipadList = $this->findAllByAttributes(array('app_id' => $app->id));
		if (!$ipadList)
			return FALSE;
		foreach($this->findAllByAttributes(array('app_id' => $app->id)) as $ipad)
		{
			$ipad->addNotification($notification);
		}
		return TRUE;
	}

	/**
	 * Connect newly created message with ipad
	 * @param IpadNotification $notification
	 */
	public function addNotification(IpadNotification $notification)
	{
		$relation = new IpadNotificationRelation();
		$relation->ipad_id = $this->id;
		$relation->notification_id = $notification->id;
		$relation->state = IpadNotificationRelation::STATE_NOT_SENT;
		$relation->save();
	}
}